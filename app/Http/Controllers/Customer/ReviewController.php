<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReviewController extends Controller
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
     * Display a listing of customer's reviews
     */
    public function index(Request $request)
    {
        $query = Review::where('customer_id', auth()->id())
                      ->with(['provider', 'booking.serviceRequest.serviceCategory']);

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Search by provider name or comment
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('provider', function($providerQuery) use ($search) {
                    $providerQuery->where('name', 'like', "%{$search}%");
                })->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $reviews = $query->latest()->paginate(10);

        // Get stats
        $stats = [
            'total' => Review::where('customer_id', auth()->id())->count(),
            'average_rating' => Review::where('customer_id', auth()->id())->avg('rating') ?? 0,
            'five_star' => Review::where('customer_id', auth()->id())->where('rating', 5)->count(),
            'pending' => Booking::where('customer_id', auth()->id())
                               ->where('status', 'completed')
                               ->whereDoesntHave('review')
                               ->count(),
        ];

        return view('customer.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Show the form for creating a new review
     */
    public function create()
    {
        // Get completed bookings without reviews
        $bookings = Booking::where('customer_id', auth()->id())
                          ->where('status', 'completed')
                          ->whereDoesntHave('review')
                          ->with(['provider', 'serviceRequest.serviceCategory'])
                          ->latest()
                          ->get();

        return view('customer.reviews.create', compact('bookings'));
    }

    /**
     * Show the form for creating a review from a specific booking
     */
    public function createFromBooking(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated customer
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        // Ensure the booking is completed
        if ($booking->status !== 'completed') {
            return back()->with('error', 'You can only review completed services.');
        }

        // Ensure no review exists yet
        if ($booking->review) {
            return back()->with('error', 'You have already reviewed this service.');
        }

        $booking->load(['provider', 'serviceRequest.serviceCategory']);

        return view('customer.reviews.create-from-booking', compact('booking'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Verify the booking belongs to the customer and is completed
        $booking = Booking::where('id', $validated['booking_id'])
                         ->where('customer_id', auth()->id())
                         ->where('status', 'completed')
                         ->with('provider')
                         ->first();

        if (!$booking) {
            return back()->with('error', 'Booking not found or not completed yet.');
        }

        // Check if review already exists
        if ($booking->review) {
            return back()->with('error', 'You have already reviewed this service.');
        }

        // Create review
        $review = Review::create([
            'booking_id' => $validated['booking_id'],
            'customer_id' => auth()->id(),
            'provider_id' => $booking->provider_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Update provider's average rating
        $this->updateProviderRating($booking->provider_id);

        // Create notification for provider
        \App\Models\Notification::reviewReceived($booking->provider_id, $review);

        // Add to testimonials if rating is 4 or 5 stars
        if ($review->rating >= 4 && $review->comment) {
            $this->addToTestimonials($review);
        }

        return redirect()->route('customer.reviews.show', $review)
                        ->with('success', 'Review submitted successfully! Thank you for your feedback. Your review helps other customers and improves our service quality.');
    }

    /**
     * Store a review from booking page
     */
    public function storeFromBooking(Request $request, Booking $booking)
    {
        // Ensure the booking belongs to the authenticated customer
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        // Ensure the booking is completed
        if ($booking->status !== 'completed') {
            return back()->with('error', 'You can only review completed services.');
        }

        // Ensure no review exists yet
        if ($booking->review) {
            return back()->with('error', 'You have already reviewed this service.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Create review
        $review = Review::create([
            'booking_id' => $booking->id,
            'customer_id' => auth()->id(),
            'provider_id' => $booking->provider_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Update provider's average rating
        $this->updateProviderRating($booking->provider_id);

        return redirect()->route('customer.bookings.show', $booking)
                        ->with('success', 'Review submitted successfully! Thank you for your feedback.');
    }

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        // Ensure the review belongs to the authenticated customer
        if ($review->customer_id !== auth()->id()) {
            abort(403);
        }

        $review->load(['provider', 'booking.serviceRequest.serviceCategory']);

        return view('customer.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review
     */
    public function edit(Review $review)
    {
        // Ensure the review belongs to the authenticated customer
        if ($review->customer_id !== auth()->id()) {
            abort(403);
        }

        // Reviews can only be edited within 24 hours
        if ($review->created_at->diffInHours(now()) > 24) {
            return back()->with('error', 'Reviews can only be edited within 24 hours of submission.');
        }

        $review->load(['provider', 'booking.serviceRequest.serviceCategory']);

        return view('customer.reviews.edit', compact('review'));
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, Review $review)
    {
        // Ensure the review belongs to the authenticated customer
        if ($review->customer_id !== auth()->id()) {
            abort(403);
        }

        // Reviews can only be edited within 24 hours
        if ($review->created_at->diffInHours(now()) > 24) {
            return back()->with('error', 'Reviews can only be edited within 24 hours of submission.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);

        // Update provider's average rating
        $this->updateProviderRating($review->provider_id);

        return redirect()->route('customer.reviews.show', $review)
                        ->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        // Ensure the review belongs to the authenticated customer
        if ($review->customer_id !== auth()->id()) {
            abort(403);
        }

        // Reviews can only be deleted within 24 hours
        if ($review->created_at->diffInHours(now()) > 24) {
            return back()->with('error', 'Reviews can only be deleted within 24 hours of submission.');
        }

        $providerId = $review->provider_id;
        $review->delete();

        // Update provider's average rating
        $this->updateProviderRating($providerId);

        return redirect()->route('customer.reviews.index')
                        ->with('success', 'Review deleted successfully.');
    }

    /**
     * Update provider's average rating and review count
     */
    private function updateProviderRating($providerId)
    {
        $reviews = Review::where('provider_id', $providerId);
        $averageRating = $reviews->avg('rating');
        $reviewCount = $reviews->count();

        \App\Models\User::where('id', $providerId)->update([
            'rating' => round($averageRating, 2),
            'total_reviews' => $reviewCount
        ]);
    }

    /**
     * Add high-rated reviews to testimonials
     */
    private function addToTestimonials($review)
    {
        // Check if testimonial already exists for this review
        $existingTestimonial = \App\Models\Testimonial::where('review_id', $review->id)->first();

        if (!$existingTestimonial) {
            \App\Models\Testimonial::create([
                'review_id' => $review->id,
                'customer_id' => $review->customer_id,
                'provider_id' => $review->provider_id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'customer_name' => $review->customer->name,
                'provider_name' => $review->provider->name,
                'service_category' => $review->booking->serviceRequest->serviceCategory->name,
                'is_featured' => $review->rating == 5, // Feature 5-star reviews
                'is_active' => true,
            ]);
        }
    }
}
