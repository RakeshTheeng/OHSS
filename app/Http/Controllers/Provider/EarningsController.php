<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EarningsController extends Controller
{
    /**
     * Display earnings summary for the provider
     */
    public function index(Request $request)
    {
        $provider = auth()->user();

        // Get date range filter
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        // Get earnings data
        $earningsQuery = Payment::where('provider_id', $provider->id)
                               ->where('status', 'completed')
                               ->whereBetween('paid_at', [$startDate, $endDate])
                               ->with(['booking.serviceRequest.serviceCategory', 'customer']);

        $earnings = $earningsQuery->orderBy('paid_at', 'desc')->paginate(15);

        // Calculate statistics
        $stats = [
            'total_earnings' => Payment::where('provider_id', $provider->id)
                                     ->where('status', 'completed')
                                     ->sum('amount'),
            'monthly_earnings' => Payment::where('provider_id', $provider->id)
                                        ->where('status', 'completed')
                                        ->whereBetween('paid_at', [$startDate, $endDate])
                                        ->sum('amount'),
            'total_services' => Booking::where('provider_id', $provider->id)
                                     ->where('status', 'completed')
                                     ->count(),
            'monthly_services' => Booking::where('provider_id', $provider->id)
                                        ->where('status', 'completed')
                                        ->whereBetween('completed_at', [$startDate, $endDate])
                                        ->count(),
            'average_per_service' => 0,
            'pending_payments' => Payment::where('provider_id', $provider->id)
                                        ->where('status', 'pending')
                                        ->sum('amount'),
        ];

        // Calculate average per service
        if ($stats['total_services'] > 0) {
            $stats['average_per_service'] = $stats['total_earnings'] / $stats['total_services'];
        }

        // Get monthly earnings chart data (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyEarnings = Payment::where('provider_id', $provider->id)
                                    ->where('status', 'completed')
                                    ->whereBetween('paid_at', [$monthStart, $monthEnd])
                                    ->sum('amount');

            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'earnings' => $monthlyEarnings
            ];
        }

        return view('provider.earnings.index', compact('earnings', 'stats', 'monthlyData', 'startDate', 'endDate'));
    }
}
