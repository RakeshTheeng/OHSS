<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        $customer = auth()->user();

        $stats = [
            'total_requests' => ServiceRequest::where('customer_id', $customer->id)->count(),
            'pending_requests' => ServiceRequest::where('customer_id', $customer->id)
                                               ->where('status', 'pending')->count(),
            'accepted_requests' => ServiceRequest::where('customer_id', $customer->id)
                                                ->where('status', 'accepted')->count(),
            'completed_requests' => ServiceRequest::where('customer_id', $customer->id)
                                                 ->where('status', 'completed')->count(),
            'total_bookings' => Booking::where('customer_id', $customer->id)->count(),
            'upcoming_bookings' => Booking::where('customer_id', $customer->id)
                                         ->where('status', 'confirmed')
                                         ->where('scheduled_date', '>', now())
                                         ->count(),
            'completed_bookings' => Booking::where('customer_id', $customer->id)
                                          ->where('status', 'completed')->count(),
            'total_spent' => Payment::where('customer_id', $customer->id)
                                   ->where('status', 'completed')->sum('amount'),
            'reviews_given' => Review::where('customer_id', $customer->id)->count(),
        ];

        // Recent service requests
        $recent_requests = ServiceRequest::where('customer_id', $customer->id)
                                        ->with(['provider', 'serviceCategory'])
                                        ->latest()
                                        ->take(5)
                                        ->get();

        // Upcoming bookings
        $upcoming_bookings = Booking::where('customer_id', $customer->id)
                                   ->where('status', 'confirmed')
                                   ->where('scheduled_date', '>', now())
                                   ->with(['provider', 'serviceRequest'])
                                   ->orderBy('scheduled_date')
                                   ->take(5)
                                   ->get();

        // Service categories for quick access
        $service_categories = ServiceCategory::active()->ordered()->take(8)->get();

        // Top rated providers
        $top_providers = User::where('role', 'provider')
                            ->where('provider_status', 'approved')
                            ->where('is_available', true)
                            ->orderBy('rating', 'desc')
                            ->take(6)
                            ->get();

        // Get recent notifications
        $notifications = Notification::where('user_id', $customer->id)
                                   ->unread()
                                   ->latest()
                                   ->take(5)
                                   ->get();

        // Get accepted requests that need booking
        $accepted_requests = ServiceRequest::where('customer_id', $customer->id)
                                          ->where('status', 'accepted')
                                          ->whereDoesntHave('booking')
                                          ->with(['provider', 'serviceCategory'])
                                          ->latest()
                                          ->take(3)
                                          ->get();

        return view('customer.dashboard', compact(
            'stats',
            'recent_requests',
            'upcoming_bookings',
            'service_categories',
            'top_providers',
            'notifications',
            'accepted_requests'
        ));
    }
}
