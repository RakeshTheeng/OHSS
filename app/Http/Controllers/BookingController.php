<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ServiceRequest;
use App\Models\Payment;
use App\Models\Notification;

class BookingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isCustomer()) {
            $bookings = Booking::where('customer_id', $user->id)
                              ->with(['provider', 'serviceRequest.serviceCategory'])
                              ->latest()
                              ->paginate(10);
        } elseif ($user->isProvider()) {
            $bookings = Booking::where('provider_id', $user->id)
                              ->with(['customer', 'serviceRequest.serviceCategory'])
                              ->latest()
                              ->paginate(10);
        } else {
            $bookings = Booking::with(['customer', 'provider', 'serviceRequest.serviceCategory'])
                              ->latest()
                              ->paginate(10);
        }

        return view('bookings.index', compact('bookings'));
    }

    public function create(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if (!$user->isCustomer() || $serviceRequest->customer_id !== $user->id) {
            abort(403);
        }

        if (!$serviceRequest->canBeBooked()) {
            return back()->with('error', 'This service request cannot be booked.');
        }

        return view('bookings.create', compact('serviceRequest'));
    }

    public function store(Request $request, ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if (!$user->isCustomer() || $serviceRequest->customer_id !== $user->id) {
            abort(403);
        }

        if (!$serviceRequest->canBeBooked()) {
            return back()->with('error', 'This service request cannot be booked.');
        }

        $request->validate([
            'scheduled_date' => 'required|date|after:now',
            'duration' => 'required|integer|min:30',
            'payment_method' => 'required|in:esewa,khalti,cash',
            'special_instructions' => 'nullable|string|max:500',
        ]);

        // Use stored total_budget if available, otherwise calculate from duration and hourly rate
        if ($serviceRequest->total_budget && $serviceRequest->required_hours) {
            // Use the pre-calculated budget from service request
            $totalAmount = $serviceRequest->total_budget;
        } else {
            // Fallback to duration-based calculation
            $totalAmount = ($request->duration / 60) * $serviceRequest->provider->hourly_rate;
        }

        $booking = Booking::create([
            'service_request_id' => $serviceRequest->id,
            'customer_id' => $user->id,
            'provider_id' => $serviceRequest->provider_id,
            'scheduled_date' => $request->scheduled_date,
            'duration' => $request->duration,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'special_instructions' => $request->special_instructions,
            'status' => 'confirmed',
        ]);

        // Update service request status
        $serviceRequest->update(['status' => 'booked']);

        // Create payment record
        Payment::create([
            'booking_id' => $booking->id,
            'customer_id' => $user->id,
            'provider_id' => $serviceRequest->provider_id,
            'amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'status' => $request->payment_method === 'cash' ? 'pending' : 'pending',
        ]);

        // Create notifications
        Notification::bookingConfirmed($user->id, $booking);
        Notification::createForUser(
            $serviceRequest->provider_id,
            'booking_received',
            'New Booking',
            "You have a new booking from {$user->name}",
            ['booking_id' => $booking->id]
        );

        if ($request->payment_method === 'esewa') {
            return redirect()->route('customer.payments.esewa', $booking->payment);
        } elseif ($request->payment_method === 'khalti') {
            return redirect()->route('customer.payments.khalti', $booking->payment);
        }

        return redirect()->route('bookings.show', $booking)
                        ->with('success', 'Booking created successfully!');
    }

    public function show(Booking $booking)
    {
        $user = auth()->user();

        if (!$user->isAdmin() &&
            $booking->customer_id !== $user->id &&
            $booking->provider_id !== $user->id) {
            abort(403);
        }

        $booking->load(['customer', 'provider', 'serviceRequest.serviceCategory', 'payment', 'review']);

        return view('bookings.show', compact('booking'));
    }

    public function start(Booking $booking)
    {
        $user = auth()->user();

        if (!$user->isProvider() || $booking->provider_id !== $user->id) {
            abort(403);
        }

        if (!$booking->canBeStarted()) {
            return back()->with('error', 'This booking cannot be started yet.');
        }

        $booking->start();

        // Create notification for customer
        Notification::createForUser(
            $booking->customer_id,
            'service_started',
            'Service Started',
            "Your service has been started by {$user->name}",
            ['booking_id' => $booking->id]
        );

        return back()->with('success', 'Service started successfully!');
    }

    public function complete(Request $request, Booking $booking)
    {
        $user = auth()->user();

        if (!$user->isProvider() || $booking->provider_id !== $user->id) {
            abort(403);
        }

        if (!$booking->canBeCompleted()) {
            return back()->with('error', 'This booking cannot be completed.');
        }

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
            "Your service has been completed by {$user->name}",
            ['booking_id' => $booking->id]
        );

        return back()->with('success', 'Service completed successfully!');
    }

    public function cancel(Booking $booking)
    {
        $user = auth()->user();

        if (!$user->isAdmin() &&
            $booking->customer_id !== $user->id &&
            $booking->provider_id !== $user->id) {
            abort(403);
        }

        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $booking->cancel();

        // Handle refund if payment was made
        if ($booking->payment && $booking->payment->status === 'completed') {
            $booking->payment->refund($booking->payment->amount, 'Booking cancelled');
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
