<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments
     */
    public function index(Request $request)
    {
        $query = Payment::with(['customer', 'provider', 'booking.serviceRequest.serviceCategory']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('esewa_ref_id', 'like', "%{$search}%")
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

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->get('payment_method'));
        }

        // Service category filter
        if ($request->filled('category')) {
            $query->whereHas('booking.serviceRequest', function($serviceQuery) use ($request) {
                $serviceQuery->where('service_category_id', $request->get('category'));
            });
        }

        // Amount range filter
        if ($request->filled('amount_from')) {
            $query->where('amount', '>=', $request->get('amount_from'));
        }
        if ($request->filled('amount_to')) {
            $query->where('amount', '<=', $request->get('amount_to'));
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $payments = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total_payments' => Payment::count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'completed_payments' => Payment::where('status', 'completed')->count(),
            'failed_payments' => Payment::where('status', 'failed')->count(),
            'refunded_payments' => Payment::where('status', 'refunded')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'esewa_payments' => Payment::where('payment_method', 'esewa')->count(),
            'cash_payments' => Payment::where('payment_method', 'cash')->count(),
            'total_refunds' => Payment::where('status', 'refunded')->sum('refund_amount'),
        ];

        // Get service categories for filter dropdown
        $serviceCategories = ServiceCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.payments.index', compact('payments', 'stats', 'serviceCategories'));
    }

    /**
     * Display the specified payment
     */
    public function show(Payment $payment)
    {
        $payment->load([
            'customer',
            'provider',
            'booking.serviceRequest.serviceCategory',
            'booking.review'
        ]);

        return view('admin.payments.show', compact('payment'));
    }
}
