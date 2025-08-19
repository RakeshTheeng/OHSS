<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ServiceCategory;
use App\Models\Review;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get approved service providers
        $providers = User::where('role', 'provider')
                        ->where('provider_status', 'approved')
                        ->where('status', 'active')
                        ->with(['serviceCategories', 'reviews'])
                        ->withAvg('reviews', 'rating')
                        ->withCount('reviews')
                        ->take(12)
                        ->get();

        // Get service categories
        $serviceCategories = ServiceCategory::where('is_active', true)
                                          ->withCount('providers')
                                          ->orderBy('name')
                                          ->get();

        // Get featured providers (highest rated)
        $featuredProviders = User::where('role', 'provider')
                                ->where('provider_status', 'approved')
                                ->where('status', 'active')
                                ->with(['serviceCategories', 'reviews'])
                                ->withAvg('reviews', 'rating')
                                ->withCount('reviews')
                                ->orderByDesc('reviews_avg_rating')
                                ->take(6)
                                ->get();

        // Get recent reviews
        $recentReviews = Review::with(['customer', 'provider'])
                              ->where('is_approved', true)
                              ->latest()
                              ->take(6)
                              ->get();

        // Get testimonials for homepage
        $testimonials = Testimonial::getForHomepage(6);

        // Statistics
        $stats = [
            'total_providers' => User::where('role', 'provider')
                                   ->where('provider_status', 'approved')
                                   ->count(),
            'total_categories' => ServiceCategory::where('is_active', true)->count(),
            'total_reviews' => Review::where('is_approved', true)->count(),
            'average_rating' => Review::where('is_approved', true)->avg('rating') ?? 0,
            'happy_customers' => Review::where('rating', '>=', 4)->distinct('customer_id')->count(),
            'services_completed' => \App\Models\Booking::where('status', 'completed')->count(),
        ];

        return view('welcome', compact(
            'providers',
            'serviceCategories',
            'featuredProviders',
            'recentReviews',
            'testimonials',
            'stats'
        ));
    }

    public function providers(Request $request)
    {
        $query = User::where('role', 'provider')
                    ->where('provider_status', 'approved')
                    ->where('status', 'active')
                    ->with(['serviceCategories', 'reviews'])
                    ->withAvg('reviews', 'rating')
                    ->withCount('reviews');

        // Filter by service category
        if ($request->filled('category')) {
            $query->whereHas('serviceCategories', function($q) use ($request) {
                $q->where('service_categories.id', $request->category);
            });
        }

        // Search by name or location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->having('reviews_avg_rating', '>=', $request->rating);
        }

        // Sort options
        $sortBy = $request->get('sort', 'rating');
        switch ($sortBy) {
            case 'rating':
                $query->orderByDesc('reviews_avg_rating');
                break;
            case 'reviews':
                $query->orderByDesc('reviews_count');
                break;
            case 'name':
                $query->orderBy('name');
                break;
            case 'newest':
                $query->latest();
                break;
        }

        $providers = $query->paginate(12);
        
        $serviceCategories = ServiceCategory::where('is_active', true)
                                          ->orderBy('name')
                                          ->get();

        return view('providers.index', compact('providers', 'serviceCategories'));
    }

    public function providerProfile(User $provider)
    {
        // Ensure this is an approved provider
        if ($provider->role !== 'provider' || $provider->provider_status !== 'approved') {
            abort(404);
        }

        $provider->load(['serviceCategories', 'reviews.customer']);
        $provider->loadAvg('reviews', 'rating');
        $provider->loadCount('reviews');

        // Get recent reviews
        $recentReviews = $provider->reviews()
                                 ->with('customer')
                                 ->where('is_approved', true)
                                 ->latest()
                                 ->take(10)
                                 ->get();

        // Get rating distribution
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = $provider->reviews()
                                              ->where('rating', $i)
                                              ->count();
        }

        // Get completed services
        $completedServices = $provider->getCompletedServices();

        return view('providers.profile', compact('provider', 'recentReviews', 'ratingDistribution', 'completedServices'));
    }
}
