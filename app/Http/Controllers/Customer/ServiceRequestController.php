<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
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

    public function index(Request $request)
    {
        $query = ServiceRequest::where('customer_id', auth()->id())
                              ->with(['provider', 'serviceCategory', 'booking']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by title or provider name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('provider', function($providerQuery) use ($search) {
                      $providerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $requests = $query->latest()->paginate(10);

        // Get stats for dashboard
        $stats = [
            'total' => ServiceRequest::where('customer_id', auth()->id())->count(),
            'pending' => ServiceRequest::where('customer_id', auth()->id())->where('status', 'pending')->count(),
            'accepted' => ServiceRequest::where('customer_id', auth()->id())->where('status', 'accepted')->count(),
            'completed' => ServiceRequest::where('customer_id', auth()->id())->where('status', 'completed')->count(),
        ];

        return view('customer.service-requests.index', compact('requests', 'stats'));
    }

    public function create(Request $request)
    {
        $serviceCategories = ServiceCategory::where('is_active', true)->get();

        $providers = User::where('role', 'provider')
                        ->where('provider_status', 'approved')
                        ->where('status', 'active')
                        ->with('serviceCategories')
                        ->get();

        $selectedProvider = null;
        if ($request->has('provider')) {
            $selectedProvider = User::where('role', 'provider')
                                   ->where('provider_status', 'approved')
                                   ->where('id', $request->provider)
                                   ->first();
        }

        return view('customer.service-requests.create', compact(
            'serviceCategories',
            'providers',
            'selectedProvider'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'provider_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string',
            'address' => 'required|string|max:500',
            'required_hours' => 'required|numeric|min:0.5|max:24',
            'urgency' => 'required|in:low,medium,high',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        // Validate provider if specified and calculate budget
        if ($validated['provider_id']) {
            $provider = User::where('id', $validated['provider_id'])
                           ->where('role', 'provider')
                           ->where('provider_status', 'approved')
                           ->first();

            if (!$provider) {
                return back()->withErrors(['provider_id' => 'Selected provider is not available.']);
            }

            // Calculate total budget based on provider's hourly rate and required hours
            $validated['hourly_rate'] = $provider->hourly_rate;
            $validated['total_budget'] = $provider->hourly_rate * $validated['required_hours'];
        }

        $validated['customer_id'] = auth()->id();
        $validated['status'] = 'pending';

        $serviceRequest = ServiceRequest::create($validated);

        return redirect()->route('customer.service-requests.show', $serviceRequest)
                        ->with('success', 'Service request submitted successfully!');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        // Ensure the request belongs to the authenticated customer
        if ($serviceRequest->customer_id !== auth()->id()) {
            abort(403);
        }

        $serviceRequest->load(['provider', 'serviceCategory', 'booking']);

        return view('customer.service-requests.show', compact('serviceRequest'));
    }

    /**
     * Cancel a service request
     */
    public function cancel(ServiceRequest $serviceRequest)
    {
        // Ensure the request belongs to the authenticated customer
        if ($serviceRequest->customer_id !== auth()->id()) {
            abort(403);
        }

        // Only pending requests can be cancelled
        if ($serviceRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }

        $serviceRequest->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => 'Cancelled by customer'
        ]);

        return back()->with('success', 'Service request cancelled successfully.');
    }

    /**
     * Edit a service request (only if pending)
     */
    public function edit(ServiceRequest $serviceRequest)
    {
        // Ensure the request belongs to the authenticated customer
        if ($serviceRequest->customer_id !== auth()->id()) {
            abort(403);
        }

        // Only pending requests can be edited
        if ($serviceRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be edited.');
        }

        $serviceCategories = ServiceCategory::where('is_active', true)->get();
        $providers = User::where('role', 'provider')
                        ->where('provider_status', 'approved')
                        ->where('is_available', true)
                        ->with('serviceCategories')
                        ->get();

        return view('customer.service-requests.edit', compact('serviceRequest', 'serviceCategories', 'providers'));
    }

    /**
     * Update a service request
     */
    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        // Ensure the request belongs to the authenticated customer
        if ($serviceRequest->customer_id !== auth()->id()) {
            abort(403);
        }

        // Only pending requests can be updated
        if ($serviceRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be updated.');
        }

        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'provider_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string',
            'address' => 'required|string|max:500',
            'required_hours' => 'required|numeric|min:0.5|max:24',
            'urgency' => 'required|in:low,medium,high',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        // Validate provider if specified and recalculate budget
        if ($validated['provider_id']) {
            $provider = User::where('id', $validated['provider_id'])
                           ->where('role', 'provider')
                           ->where('provider_status', 'approved')
                           ->first();

            if (!$provider) {
                return back()->withErrors(['provider_id' => 'Selected provider is not available.']);
            }

            $validated['hourly_rate'] = $provider->hourly_rate;
            $validated['total_budget'] = $provider->hourly_rate * $validated['required_hours'];
        }

        $serviceRequest->update($validated);

        return redirect()->route('customer.service-requests.show', $serviceRequest)
                        ->with('success', 'Service request updated successfully!');
    }
}
