@extends('layouts.admin')

@section('title', 'Analytics Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-area me-2"></i>Analytics Dashboard
            </h1>
            <p class="text-muted mb-0">Comprehensive system analytics and reports</p>
        </div>
        <div class="d-sm-flex gap-2">
            <form method="GET" action="{{ route('admin.analytics.index') }}" class="d-flex gap-2">
                <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="7" {{ $period == 7 ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 days</option>
                    <option value="365" {{ $period == 365 ? 'selected' : '' }}>Last year</option>
                </select>
            </form>
            <div class="dropdown">
                <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="reportsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-file-export me-1"></i>Generate Reports
                </button>
                <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                    <li><a class="dropdown-item" href="{{ route('admin.analytics.reports', ['type' => 'users']) }}">User Report</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.analytics.reports', ['type' => 'services']) }}">Service Report</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.analytics.reports', ['type' => 'revenue']) }}">Revenue Report</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.analytics.reports', ['type' => 'providers']) }}">Provider Report</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- User Analytics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>User Analytics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($userStats['total_users']) }}</div>
                                            <div class="text-xs text-success">+{{ $userStats['new_users_period'] }} this period</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Customers</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($userStats['customers']['total']) }}</div>
                                            <div class="text-xs text-success">+{{ $userStats['customers']['new'] }} new</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Providers</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($userStats['providers']['total']) }}</div>
                                            <div class="text-xs text-info">{{ $userStats['providers']['approved'] }} approved</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-cog fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Providers</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($userStats['providers']['pending']) }}</div>
                                            <div class="text-xs text-danger">{{ $userStats['providers']['rejected'] }} rejected</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service & Revenue Analytics -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list me-2"></i>Service Analytics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-primary">{{ number_format($serviceStats['total_requests']) }}</div>
                                <div class="text-muted small">Total Requests</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-success">{{ $serviceStats['completion_rate'] }}%</div>
                                <div class="text-muted small">Completion Rate</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($serviceStats['by_status'] as $status => $count)
                            <div class="col-6 mb-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-capitalize">{{ $status }}:</span>
                                    <span class="font-weight-bold">{{ $count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-dollar-sign me-2"></i>Revenue Analytics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-success">Rs. {{ number_format($revenueStats['total_revenue'], 2) }}</div>
                                <div class="text-muted small">Total Revenue</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-info">Rs. {{ number_format($revenueStats['period_revenue'], 2) }}</div>
                                <div class="text-muted small">Period Revenue</div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="h5 font-weight-bold text-warning">Rs. {{ number_format($revenueStats['average_order'], 2) }}</div>
                        <div class="text-muted small">Average Order Value</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Growth Trend</h6>
                </div>
                <div class="card-body">
                    <canvas id="userGrowthChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Trend</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueTrendChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Distribution & Provider Performance -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="serviceDistributionChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Providers</h6>
                </div>
                <div class="card-body">
                    @if($chartData['provider_performance']->count() > 0)
                        @foreach($chartData['provider_performance'] as $provider)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="font-weight-bold">{{ $provider['name'] }}</div>
                                    <div class="text-muted small">{{ $provider['bookings'] }} bookings</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $provider['rating'] ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="text-muted small">{{ number_format($provider['rating'], 1) }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-user-slash fa-3x mb-3"></i>
                            <p>No provider data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// User Growth Chart
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
const userGrowthChart = new Chart(userGrowthCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['user_growth']->pluck('date')) !!},
        datasets: [{
            label: 'Customers',
            data: {!! json_encode($chartData['user_growth']->pluck('customers')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Providers',
            data: {!! json_encode($chartData['user_growth']->pluck('providers')) !!},
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Revenue Trend Chart
const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');
const revenueTrendChart = new Chart(revenueTrendCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartData['revenue_trend']->pluck('date')) !!},
        datasets: [{
            label: 'Revenue (Rs.)',
            data: {!! json_encode($chartData['revenue_trend']->pluck('revenue')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Service Distribution Chart
const serviceDistributionCtx = document.getElementById('serviceDistributionChart').getContext('2d');
const serviceDistributionChart = new Chart(serviceDistributionCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($chartData['service_distribution']->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($chartData['service_distribution']->pluck('count')) !!},
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush
