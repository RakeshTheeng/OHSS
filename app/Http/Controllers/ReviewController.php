<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Booking;
use App\Models\Notification;

class ReviewController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isCustomer()) {
            $reviews = Review::where('customer_id', $user->id)
                           ->with(['provider', 'booking.serviceRequest.serviceCategory'])
                           ->latest()
                           ->paginate(10);
        } elseif ($user->isProvider()) {
            $reviews = Review::where('provider_id', $user->id)
                           ->with(['customer', 'booking.serviceRequest.serviceCategory'])
                           ->latest()
                           ->paginate(10);
        } else {
            $reviews = Review::with(['customer', 'provider', 'booking.serviceRequest.serviceCategory'])
                           ->latest()
                           ->paginate(10);
        }

        return view('reviews.index', compact('reviews'));
    }

    public function create(Booking $booking)
    {
        $user = auth()->user();

        if (!$user->isCustomer() || $booking->customer_id !== $user->id) {
            abort(403);
        }

        if ($booking->status !== 'completed') {
            return back()->with('error', 'You can only review completed services.');
        }

        if ($booking->review) {
            return back()->with('error', 'You have already reviewed this service.');
        }

        return view('reviews.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        $user = auth()->user();

        if (!$user->isCustomer() || $booking->customer_id !== $user->id) {
            abort(403);
        }

        if ($booking->status !== 'completed') {
            return back()->with('error', 'You can only review completed services.');
        }

        if ($booking->review) {
            return back()->with('error', 'You have already reviewed this service.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = Review::create([
            'booking_id' => $booking->id,
            'customer_id' => $user->id,
            'provider_id' => $booking->provider_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Create notification for provider
        Notification::reviewReceived($booking->provider_id, $review);

        return redirect()->route('bookings.show', $booking)
                        ->with('success', 'Review submitted successfully!');
    }

    public function show(Review $review)
    {
        $user = auth()->user();

        if (!$user->isAdmin() &&
            $review->customer_id !== $user->id &&
            $review->provider_id !== $user->id) {
            abort(403);
        }

        $review->load(['customer', 'provider', 'booking.serviceRequest.serviceCategory']);

        return view('reviews.show', compact('review'));
    }

    public function respond(Request $request, Review $review)
    {
        $user = auth()->user();

        if (!$user->isProvider() || $review->provider_id !== $user->id) {
            abort(403);
        }

        if (!$review->canBeResponded()) {
            return back()->with('error', 'This review has already been responded to.');
        }

        $request->validate([
            'response' => 'required|string|max:1000',
        ]);

        $review->respond($request->response);

        return back()->with('success', 'Response added successfully!');
    }

    public function flag(Request $request, Review $review)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $review->flag($request->reason);

        return back()->with('success', 'Review has been flagged for moderation.');
    }

    public function approve(Review $review)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $review->approve();

        return back()->with('success', 'Review approved successfully.');
    }

    public function reject(Review $review)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $review->reject();

        return back()->with('success', 'Review rejected successfully.');
    }
}
