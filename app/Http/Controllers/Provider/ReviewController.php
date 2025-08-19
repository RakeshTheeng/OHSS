<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display all reviews for the provider
     */
    public function index(Request $request)
    {
        $provider = auth()->user();

        $query = Review::where('provider_id', $provider->id)
                      ->with(['customer', 'booking.serviceRequest.serviceCategory']);

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Search by customer name or comment
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($customerQuery) use ($search) {
                    $customerQuery->where('name', 'like', "%{$search}%");
                })->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $reviews = $query->latest()->paginate(10);

        // Get review statistics
        $stats = [
            'total' => Review::where('provider_id', $provider->id)->count(),
            'average_rating' => Review::where('provider_id', $provider->id)->avg('rating') ?? 0,
            'five_star' => Review::where('provider_id', $provider->id)->where('rating', 5)->count(),
            'four_star' => Review::where('provider_id', $provider->id)->where('rating', 4)->count(),
            'three_star' => Review::where('provider_id', $provider->id)->where('rating', 3)->count(),
            'two_star' => Review::where('provider_id', $provider->id)->where('rating', 2)->count(),
            'one_star' => Review::where('provider_id', $provider->id)->where('rating', 1)->count(),
        ];

        return view('provider.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        // Ensure the review belongs to the authenticated provider
        if ($review->provider_id !== auth()->id()) {
            abort(403);
        }

        $review->load(['customer', 'booking.serviceRequest.serviceCategory']);

        return view('provider.reviews.show', compact('review'));
    }

    /**
     * Respond to a review
     */
    public function respond(Request $request, Review $review)
    {
        // Ensure the review belongs to the authenticated provider
        if ($review->provider_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'response' => 'required|string|max:1000',
        ]);

        $review->update([
            'provider_response' => $request->response,
            'provider_responded_at' => now(),
        ]);

        return back()->with('success', 'Response added successfully!');
    }
}
