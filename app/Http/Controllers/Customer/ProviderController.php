<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ServiceCategory;
use App\Models\Review;
use Illuminate\Http\Request;

class ProviderController extends Controller
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
     * Display a listing of all available providers
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'provider')
                    ->where('provider_status', 'approved')
                    ->where('status', 'active')
                    ->where('is_available', true)
                    ->with(['serviceCategories', 'reviews']);

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by service category
        if ($request->filled('category')) {
            $query->whereHas('serviceCategories', function($q) use ($request) {
                $q->where('service_categories.id', $request->category);
            });
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        // Filter by hourly rate range
        if ($request->filled('min_rate')) {
            $query->where('hourly_rate', '>=', $request->min_rate);
        }
        if ($request->filled('max_rate')) {
            $query->where('hourly_rate', '<=', $request->max_rate);
        }

        // Sort options
        switch ($request->get('sort', 'rating')) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'rate_low':
                $query->orderBy('hourly_rate', 'asc');
                break;
            case 'rate_high':
                $query->orderBy('hourly_rate', 'desc');
                break;
            case 'experience':
                $query->orderBy('experience_years', 'desc');
                break;
            default:
                $query->orderBy('rating', 'desc');
        }

        $providers = $query->paginate(12);

        // Get service categories for filter dropdown
        $serviceCategories = ServiceCategory::active()->ordered()->get();

        return view('customer.providers.index', compact('providers', 'serviceCategories'));
    }

    /**
     * Display the specified provider profile
     */
    public function show(User $provider)
    {
        // Ensure the user is a provider and approved
        if ($provider->role !== 'provider' || $provider->provider_status !== 'approved') {
            abort(404);
        }

        // Load relationships
        $provider->load([
            'serviceCategories',
            'reviews' => function($query) {
                $query->with('customer')->latest()->take(10);
            },
            'kycDocuments'
        ]);

        // Calculate review statistics
        $reviewStats = [
            'total' => $provider->reviews->count(),
            'average' => $provider->reviews->avg('rating') ?? 0,
            'distribution' => [
                5 => $provider->reviews->where('rating', 5)->count(),
                4 => $provider->reviews->where('rating', 4)->count(),
                3 => $provider->reviews->where('rating', 3)->count(),
                2 => $provider->reviews->where('rating', 2)->count(),
                1 => $provider->reviews->where('rating', 1)->count(),
            ]
        ];

        return view('customer.providers.show', compact('provider', 'reviewStats'));
    }
}
