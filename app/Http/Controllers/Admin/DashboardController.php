<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\ServiceCategory;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_providers' => User::where('role', 'provider')->count(),
            'pending_providers' => User::where('role', 'provider')
                                      ->where('provider_status', 'awaiting')->count(),
            'approved_providers' => User::where('role', 'provider')
                                       ->where('provider_status', 'approved')->count(),
            'total_service_requests' => ServiceRequest::count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
            'completed_requests' => ServiceRequest::where('status', 'completed')->count(),
            'total_bookings' => Booking::count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'total_reviews' => Review::count(),
            'flagged_reviews' => Review::where('is_flagged', true)->count(),
        ];

        // Recent activities
        $recent_requests = ServiceRequest::with(['customer', 'provider', 'serviceCategory'])
                                        ->latest()
                                        ->take(5)
                                        ->get();

        $recent_bookings = Booking::with(['customer', 'provider'])
                                 ->latest()
                                 ->take(5)
                                 ->get();

        $pending_providers = User::where('role', 'provider')
                                ->where('provider_status', 'awaiting')
                                ->with('kycDocuments')
                                ->latest()
                                ->take(5)
                                ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_requests',
            'recent_bookings',
            'pending_providers'
        ));
    }
}
