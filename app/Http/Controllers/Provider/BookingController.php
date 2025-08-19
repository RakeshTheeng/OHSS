<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ServiceRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display all booking requests for the provider
     */
    public function index(Request $request)
    {
        $provider = auth()->user();

        $query = Booking::where('provider_id', $provider->id)
                       ->with(['customer', 'serviceRequest.serviceCategory', 'payment', 'review']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by customer name or service
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($customerQuery) use ($search) {
                    $customerQuery->where('name', 'like', "%{$search}%");
                })->orWhereHas('serviceRequest', function($serviceQuery) use ($search) {
                    $serviceQuery->where('title', 'like', "%{$search}%")
                               ->orWhere('description', 'like', "%{$search}%");
                });
            });
        }

        $bookings = $query->orderBy('scheduled_date', 'desc')->paginate(10);

        // Get booking statistics
        $stats = [
            'total' => Booking::where('provider_id', $provider->id)->count(),
            'confirmed' => Booking::where('provider_id', $provider->id)->where('status', 'confirmed')->count(),
            'in_progress' => Booking::where('provider_id', $provider->id)->where('status', 'in_progress')->count(),
            'completed' => Booking::where('provider_id', $provider->id)->where('status', 'completed')->count(),
        ];

        return view('provider.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated provider
        if ($booking->provider_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['customer', 'serviceRequest.serviceCategory', 'payment', 'review']);

        return view('provider.bookings.show', compact('booking'));
    }

    /**
     * Start a booking service
     */
    public function start(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated provider
        if ($booking->provider_id !== auth()->id()) {
            abort(403);
        }

        // Check if booking can be started
        if (!$booking->canBeStarted()) {
            return redirect()->back()->with('error', 'This booking cannot be started at this time.');
        }

        // Use the model's start method
        $booking->start();

        // Create notification for customer
        Notification::createForUser(
            $booking->customer_id,
            'service_started',
            'Service Started',
            "Your service has been started by {$booking->provider->name}",
            ['booking_id' => $booking->id]
        );

        return redirect()->route('provider.bookings.show', $booking)
                        ->with('success', 'Service has been started successfully!');
    }

    /**
     * Complete a booking service
     */
    public function complete(Request $request, Booking $booking)
    {
        // Ensure the booking belongs to the authenticated provider
        if ($booking->provider_id !== auth()->id()) {
            abort(403);
        }

        // Check if booking can be completed
        if (!$booking->canBeCompleted()) {
            return redirect()->back()->with('error', 'This booking cannot be completed at this time.');
        }

        // Validate completion notes (optional)
        $request->validate([
            'completion_notes' => 'nullable|string|max:1000',
        ]);

        // Use the model's complete method
        $booking->complete($request->completion_notes);

        // Update payment status for cash payments
        if ($booking->payment && $booking->payment->payment_method === 'cash') {
            $booking->payment->markAsPaid();
        }

        // Create notification for customer
        Notification::createForUser(
            $booking->customer_id,
            'service_completed',
            'Service Completed',
            "Your service has been completed by {$booking->provider->name}",
            ['booking_id' => $booking->id]
        );

        return redirect()->route('provider.bookings.show', $booking)
                        ->with('success', 'Service has been completed successfully!');
    }
}
