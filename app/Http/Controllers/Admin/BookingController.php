<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings
     */
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'provider', 'serviceRequest.serviceCategory', 'payment', 'review']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($customerQuery) use ($search) {
                    $customerQuery->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('provider', function($providerQuery) use ($search) {
                    $providerQuery->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('serviceRequest', function($serviceQuery) use ($search) {
                    $serviceQuery->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->get('payment_status'));
        }

        // Service category filter
        if ($request->filled('category')) {
            $query->whereHas('serviceRequest', function($serviceQuery) use ($request) {
                $serviceQuery->where('service_category_id', $request->get('category'));
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_date', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_date', '<=', $request->get('date_to'));
        }

        $bookings = $query->latest('scheduled_date')->paginate(15);

        // Statistics
        $stats = [
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'in_progress_bookings' => Booking::where('status', 'in_progress')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            'total_revenue' => Booking::where('status', 'completed')->sum('total_amount'),
            'pending_payments' => Booking::where('payment_status', 'pending')->count(),
        ];

        // Get service categories for filter dropdown
        $serviceCategories = ServiceCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.bookings.index', compact('bookings', 'stats', 'serviceCategories'));
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        $booking->load([
            'customer',
            'provider',
            'serviceRequest.serviceCategory',
            'payment',
            'review',
            'chat'
        ]);

        return view('admin.bookings.show', compact('booking'));
    }
}
