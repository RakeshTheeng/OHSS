@extends('layouts.admin')

@section('title', 'Revenue Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-dollar-sign me-2"></i>Revenue Report
            </h1>
            <p class="text-muted mb-0">Payment and revenue report from {{ $startDate }} to {{ $endDate }}</p>
        </div>
        <div class="d-sm-flex gap-2">
            <a href="{{ route('admin.analytics.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back to Analytics
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="fas fa-print me-1"></i>Print Report
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($payments->where('status', 'completed')->sum('amount'), 2) }}</div>
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Payments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $payments->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Completed Payments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $payments->where('status', 'completed')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Average Payment</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($payments->where('status', 'completed')->avg('amount') ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Payment Details</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Booking ID</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Transaction ID</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->customer->name ?? 'N/A' }}</td>
                            <td>{{ $payment->booking->id ?? 'N/A' }}</td>
                            <td>Rs. {{ number_format($payment->amount, 2) }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ ucfirst($payment->payment_method) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'completed' => 'success',
                                        'failed' => 'danger',
                                        'cancelled' => 'secondary'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$payment->status] ?? 'secondary' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                            <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No payments found for the selected period.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Payment Method Distribution -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Methods</h6>
                </div>
                <div class="card-body">
                    @php
                        $paymentMethods = $payments->groupBy('payment_method');
                    @endphp
                    @foreach($paymentMethods as $method => $methodPayments)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>{{ ucfirst($method) }}</span>
                            <span>{{ $methodPayments->count() }} payments</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ ($methodPayments->count() / $payments->count()) * 100 }}%">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Status</h6>
                </div>
                <div class="card-body">
                    @php
                        $paymentStatuses = $payments->groupBy('status');
                    @endphp
                    @foreach($paymentStatuses as $status => $statusPayments)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>{{ ucfirst($status) }}</span>
                            <span>Rs. {{ number_format($statusPayments->sum('amount'), 2) }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-{{ $statusColors[$status] ?? 'secondary' }}" role="progressbar" 
                                 style="width: {{ ($statusPayments->sum('amount') / $payments->sum('amount')) * 100 }}%">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "pageLength": 25,
        "order": [[ 7, "desc" ]], // Sort by payment date
        "columnDefs": [
            { "orderable": false, "targets": [6] } // Transaction ID column
        ]
    });
});
</script>
@endpush
@endsection
