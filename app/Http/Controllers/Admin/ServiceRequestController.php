<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    /**
     * Display a listing of service requests
     */
    public function index(Request $request)
    {
        $query = ServiceRequest::with(['customer', 'provider', 'serviceCategory', 'booking']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
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

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Service category filter
        if ($request->filled('category')) {
            $query->where('service_category_id', $request->get('category'));
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $serviceRequests = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total_requests' => ServiceRequest::count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
            'accepted_requests' => ServiceRequest::where('status', 'accepted')->count(),
            'completed_requests' => ServiceRequest::where('status', 'completed')->count(),
            'cancelled_requests' => ServiceRequest::where('status', 'cancelled')->count(),
        ];

        // Get service categories for filter dropdown
        $serviceCategories = ServiceCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.service-requests.index', compact('serviceRequests', 'stats', 'serviceCategories'));
    }

    /**
     * Display the specified service request
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load([
            'customer',
            'provider',
            'serviceCategory',
            'booking.payment',
            'booking.review'
        ]);

        return view('admin.service-requests.show', compact('serviceRequest'));
    }
}
