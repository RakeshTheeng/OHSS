<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ServiceRequest;
use App\Models\Payment;
use App\Models\Chat;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'customer') {
                abort(403, 'Access denied. Customer access required.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of customer's bookings
     */
    public function index(Request $request)
    {
        $query = Booking::where('customer_id', auth()->id())
                       ->with(['provider', 'serviceRequest.serviceCategory', 'payment']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by provider name or service
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('provider', function($providerQuery) use ($search) {
                    $providerQuery->where('name', 'like', "%{$search}%");
                })->orWhereHas('serviceRequest', function($serviceQuery) use ($search) {
                    $serviceQuery->where('title', 'like', "%{$search}%");
                });
            });
        }

        $bookings = $query->latest()->paginate(10);

        // Get stats for dashboard
        $stats = [
            'total' => Booking::where('customer_id', auth()->id())->count(),
            'upcoming' => Booking::where('customer_id', auth()->id())
                                ->where('status', 'confirmed')
                                ->where('scheduled_date', '>', now())
                                ->count(),
            'completed' => Booking::where('customer_id', auth()->id())->where('status', 'completed')->count(),
            'cancelled' => Booking::where('customer_id', auth()->id())->where('status', 'cancelled')->count(),
        ];

        return view('customer.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Show the form for creating a new booking
     */
    public function create(Request $request)
    {
        $serviceRequest = null;

        // If service_request parameter is provided, load it
        if ($request->has('service_request')) {
            $serviceRequest = ServiceRequest::where('id', $request->service_request)
                                           ->where('customer_id', auth()->id())
                                           ->where('status', 'accepted')
                                           ->with(['provider', 'serviceCategory'])
                                           ->first();

            if (!$serviceRequest) {
                return redirect()->route('customer.service-requests.index')
                               ->with('error', 'Service request not found or not accepted yet.');
            }
        }

        return view('customer.bookings.create', compact('serviceRequest'));
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_request_id' => 'required|exists:service_requests,id',
            'scheduled_date' => 'required|date|after:now',
            'duration' => 'required|integer|min:1|max:24',
            'payment_method' => 'required|in:esewa,khalti,cash',
            'special_instructions' => 'nullable|string|max:1000',
        ]);

        // Verify the service request belongs to the customer and is accepted
        $serviceRequest = ServiceRequest::where('id', $validated['service_request_id'])
                                       ->where('customer_id', auth()->id())
                                       ->where('status', 'accepted')
                                       ->with('provider')
                                       ->first();

        if (!$serviceRequest) {
            return back()->with('error', 'Service request not found or not accepted yet.');
        }

        // Check if booking already exists for this service request
        if ($serviceRequest->booking) {
            return back()->with('error', 'A booking already exists for this service request.');
        }

        // Calculate total amount
        $totalAmount = $serviceRequest->provider->hourly_rate * $validated['duration'];

        // Create booking with different status based on payment method
        $bookingStatus = $validated['payment_method'] === 'cash' ? 'confirmed' : 'pending_payment';
        $paymentStatus = $validated['payment_method'] === 'cash' ? 'pending' : 'awaiting_payment';

        $booking = Booking::create([
            'service_request_id' => $validated['service_request_id'],
            'customer_id' => auth()->id(),
            'provider_id' => $serviceRequest->provider_id,
            'scheduled_date' => $validated['scheduled_date'],
            'duration' => $validated['duration'],
            'total_amount' => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'payment_status' => $paymentStatus,
            'status' => $bookingStatus,
            'special_instructions' => $validated['special_instructions'],
        ]);

        // Create payment record
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'customer_id' => auth()->id(),
            'provider_id' => $serviceRequest->provider_id,
            'amount' => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'status' => $validated['payment_method'] === 'cash' ? 'pending' : 'awaiting_payment',
        ]);

        // Handle different payment methods
        if ($validated['payment_method'] === 'esewa') {
            // For eSewa: redirect to payment processing - booking not confirmed yet
            return redirect()->route('customer.payments.esewa', $payment)
                           ->with('info', 'Please complete your eSewa payment to confirm the booking.');
        } elseif ($validated['payment_method'] === 'khalti') {
            // For Khalti: redirect to payment processing - booking not confirmed yet
            return redirect()->route('customer.payments.khalti', $payment)
                           ->with('info', 'Please complete your Khalti payment to confirm the booking.');
        } else {
            // For Cash: booking is confirmed immediately
            // Create chat for communication
            Chat::create([
                'booking_id' => $booking->id,
                'customer_id' => auth()->id(),
                'provider_id' => $serviceRequest->provider_id,
            ]);

            // Notify provider about confirmed booking
            Notification::createForUser(
                $serviceRequest->provider_id,
                'booking_received',
                'New Booking Confirmed',
                "You have a new confirmed booking from " . auth()->user()->name,
                ['booking_id' => $booking->id]
            );

            return redirect()->route('customer.bookings.show', $booking)
                            ->with('success', 'Booking confirmed successfully! You can now communicate with your provider.');
        }
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated customer
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['provider', 'serviceRequest.serviceCategory', 'payment', 'review']);

        return view('customer.bookings.show', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated customer
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        // Only confirmed bookings can be cancelled
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed bookings can be cancelled.');
        }

        // Check if booking is within cancellation window (e.g., 24 hours before)
        if ($booking->scheduled_date->diffInHours(now()) < 24) {
            return back()->with('error', 'Bookings cannot be cancelled less than 24 hours before the scheduled time.');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => 'Cancelled by customer'
        ]);

        // Update payment status
        if ($booking->payment) {
            $booking->payment->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
