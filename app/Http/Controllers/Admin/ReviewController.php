<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function index(Request $request)
    {
        $query = Review::with(['customer', 'provider', 'booking.serviceRequest.serviceCategory']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('provider', function($providerQuery) use ($search) {
                      $providerQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Rating filter
        if ($request->filled('rating')) {
            $query->where('rating', $request->get('rating'));
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->get('status') === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->get('status') === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->get('status') === 'flagged') {
                $query->where('is_flagged', true);
            }
        }

        // Service category filter
        if ($request->filled('category')) {
            $query->whereHas('booking.serviceRequest', function($serviceQuery) use ($request) {
                $serviceQuery->where('service_category_id', $request->get('category'));
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $reviews = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total_reviews' => Review::count(),
            'approved_reviews' => Review::where('is_approved', true)->count(),
            'pending_reviews' => Review::where('is_approved', false)->count(),
            'flagged_reviews' => Review::where('is_flagged', true)->count(),
            'average_rating' => Review::avg('rating') ?? 0,
            'five_star_reviews' => Review::where('rating', 5)->count(),
            'one_star_reviews' => Review::where('rating', 1)->count(),
            'reviews_with_response' => Review::whereNotNull('provider_response')->count(),
        ];

        // Get service categories for filter dropdown
        $serviceCategories = ServiceCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.reviews.index', compact('reviews', 'stats', 'serviceCategories'));
    }

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        $review->load([
            'customer',
            'provider',
            'booking.serviceRequest.serviceCategory',
            'booking.payment'
        ]);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve a review
     */
    public function approve(Review $review)
    {
        $review->update([
            'is_approved' => true,
            'is_flagged' => false,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Review approved successfully.');
    }

    /**
     * Flag a review for moderation
     */
    public function flag(Request $request, Review $review)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $review->update([
            'is_flagged' => true,
            'flag_reason' => $request->reason,
            'flagged_at' => now(),
            'flagged_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Review flagged for moderation.');
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
                        ->with('success', 'Review deleted successfully.');
    }
}
