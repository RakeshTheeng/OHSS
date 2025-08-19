@extends('layouts.provider')

@section('title', 'Earnings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-dollar-sign me-2"></i>
            Earnings
        </h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Earnings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($stats['total_earnings'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($stats['monthly_earnings'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Avg Per Service</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($stats['average_per_service'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($stats['pending_payments'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Earnings Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Monthly Earnings Trend</h6>
        </div>
        <div class="card-body">
            <canvas id="earningsChart" width="100%" height="30"></canvas>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Earnings</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('provider.earnings.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" 
                                   value="{{ $startDate->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" 
                                   value="{{ $endDate->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('provider.earnings.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Earnings List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Earnings History</h6>
        </div>
        <div class="card-body">
            @if($earnings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Service</th>
                                <th>Customer</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($earnings as $payment)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $payment->paid_at->format('M d, Y') }}</div>
                                        <div class="text-muted small">{{ $payment->paid_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $payment->booking->serviceRequest->title }}</div>
                                        <div class="text-muted small">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $payment->booking->serviceRequest->serviceCategory->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($payment->customer->profile_image)
                                                <img src="{{ Storage::url($payment->customer->profile_image) }}" 
                                                     alt="{{ $payment->customer->name }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 30px; height: 30px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 30px; height: 30px;">
                                                    <i class="fas fa-user text-white small"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-bold">{{ $payment->customer->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payment->payment_method === 'esewa' ? 'success' : 'info' }}">
                                            {{ ucfirst($payment->payment_method) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-success">Rs. {{ number_format($payment->amount, 2) }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Completed</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $earnings->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-dollar-sign fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No earnings found</h5>
                    <p class="text-muted">Your earnings will appear here once customers pay for completed services.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Earnings Chart
const ctx = document.getElementById('earningsChart').getContext('2d');
const earningsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
        datasets: [{
            label: 'Monthly Earnings (Rs.)',
            data: {!! json_encode(array_column($monthlyData, 'earnings')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Monthly Earnings Trend'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value, index, values) {
                        return 'Rs. ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
@endpush
