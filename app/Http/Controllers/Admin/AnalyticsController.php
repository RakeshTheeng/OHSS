<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = Carbon::now()->subDays($period);

        // User Analytics
        $userStats = [
            'total_users' => User::whereIn('role', ['customer', 'provider'])->count(),
            'new_users_period' => User::whereIn('role', ['customer', 'provider'])
                                     ->where('created_at', '>=', $startDate)->count(),
            'customers' => [
                'total' => User::where('role', 'customer')->count(),
                'new' => User::where('role', 'customer')->where('created_at', '>=', $startDate)->count(),
                'active' => User::where('role', 'customer')->where('status', 'active')->count(),
            ],
            'providers' => [
                'total' => User::where('role', 'provider')->count(),
                'new' => User::where('role', 'provider')->where('created_at', '>=', $startDate)->count(),
                'approved' => User::where('role', 'provider')->where('provider_status', 'approved')->count(),
                'pending' => User::where('role', 'provider')->where('provider_status', 'pending')->count(),
                'rejected' => User::where('role', 'provider')->where('provider_status', 'rejected')->count(),
            ]
        ];

        // Service Request Analytics
        $serviceStats = [
            'total_requests' => ServiceRequest::count(),
            'new_requests_period' => ServiceRequest::where('created_at', '>=', $startDate)->count(),
            'by_status' => ServiceRequest::select('status', DB::raw('count(*) as count'))
                                       ->groupBy('status')->get()->pluck('count', 'status'),
            'completion_rate' => $this->calculateCompletionRate(),
        ];

        // Booking Analytics
        $bookingStats = [
            'total_bookings' => Booking::count(),
            'new_bookings_period' => Booking::where('created_at', '>=', $startDate)->count(),
            'by_status' => Booking::select('status', DB::raw('count(*) as count'))
                                 ->groupBy('status')->get()->pluck('count', 'status'),
        ];

        // Revenue Analytics
        $revenueStats = [
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'period_revenue' => Payment::where('status', 'completed')
                                     ->where('created_at', '>=', $startDate)->sum('amount'),
            'average_order' => Payment::where('status', 'completed')->avg('amount'),
        ];

        // Chart Data
        $chartData = [
            'user_growth' => $this->getUserGrowthData($period),
            'revenue_trend' => $this->getRevenueTrendData($period),
            'service_distribution' => $this->getServiceDistributionData(),
            'provider_performance' => $this->getProviderPerformanceData(),
        ];

        return view('admin.analytics.index', compact(
            'userStats', 'serviceStats', 'bookingStats', 'revenueStats', 'chartData', 'period'
        ));
    }

    public function reports(Request $request)
    {
        $type = $request->get('type', 'users');
        $startDate = $request->get('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        switch ($type) {
            case 'users':
                return $this->generateUserReport($startDate, $endDate);
            case 'services':
                return $this->generateServiceReport($startDate, $endDate);
            case 'revenue':
                return $this->generateRevenueReport($startDate, $endDate);
            case 'providers':
                return $this->generateProviderReport($startDate, $endDate);
            default:
                return redirect()->back()->with('error', 'Invalid report type');
        }
    }

    private function calculateCompletionRate()
    {
        $total = ServiceRequest::count();
        $completed = ServiceRequest::where('status', 'completed')->count();

        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }

    private function getUserGrowthData($days)
    {
        $data = [];
        $startDate = Carbon::now()->subDays($days);

        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $customers = User::where('role', 'customer')
                           ->whereDate('created_at', $date)
                           ->count();
            $providers = User::where('role', 'provider')
                           ->whereDate('created_at', $date)
                           ->count();

            $data[] = [
                'date' => $date->format('M d'),
                'customers' => $customers,
                'providers' => $providers,
            ];
        }

        return collect($data);
    }

    private function getRevenueTrendData($days)
    {
        $data = [];
        $startDate = Carbon::now()->subDays($days);

        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Payment::where('status', 'completed')
                            ->whereDate('created_at', $date)
                            ->sum('amount');

            $data[] = [
                'date' => $date->format('M d'),
                'revenue' => $revenue,
            ];
        }

        return collect($data);
    }

    private function getServiceDistributionData()
    {
        return ServiceCategory::withCount('serviceRequests')
                            ->orderByDesc('service_requests_count')
                            ->take(10)
                            ->get()
                            ->map(function($category) {
                                return [
                                    'name' => $category->name,
                                    'count' => $category->service_requests_count,
                                ];
                            });
    }

    private function getProviderPerformanceData()
    {
        return User::where('role', 'provider')
                  ->where('provider_status', 'approved')
                  ->withCount(['bookings' => function($query) {
                      $query->where('status', 'completed');
                  }])
                  ->orderByDesc('bookings_count')
                  ->take(10)
                  ->get()
                  ->map(function($provider) {
                      return [
                          'name' => $provider->name,
                          'bookings' => $provider->bookings_count,
                          'rating' => $provider->rating ?? 0,
                      ];
                  });
    }

    private function generateUserReport($startDate, $endDate)
    {
        $users = User::whereIn('role', ['customer', 'provider'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->with('serviceCategories')
                    ->get();

        return view('admin.analytics.reports.users', compact('users', 'startDate', 'endDate'));
    }

    private function generateServiceReport($startDate, $endDate)
    {
        $services = ServiceRequest::whereBetween('created_at', [$startDate, $endDate])
                                 ->with(['customer', 'provider', 'serviceCategory'])
                                 ->get();

        return view('admin.analytics.reports.services', compact('services', 'startDate', 'endDate'));
    }

    private function generateRevenueReport($startDate, $endDate)
    {
        $payments = Payment::whereBetween('created_at', [$startDate, $endDate])
                          ->with(['customer', 'booking'])
                          ->get();

        return view('admin.analytics.reports.revenue', compact('payments', 'startDate', 'endDate'));
    }

    private function generateProviderReport($startDate, $endDate)
    {
        $providers = User::where('role', 'provider')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->withCount(['serviceRequests', 'bookings', 'reviews'])
                        ->with('serviceCategories')
                        ->get();

        return view('admin.analytics.reports.providers', compact('providers', 'startDate', 'endDate'));
    }
}
