@extends('layouts.admin')

@section('title', 'Service Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tools me-2"></i>Service Report
            </h1>
            <p class="text-muted mb-0">Service requests report from {{ $startDate }} to {{ $endDate }}</p>
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
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $services->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $services->where('status', 'completed')->count() }}</div>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $services->where('status', 'pending')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $services->where('status', 'in_progress')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Service Request Details</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Provider</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Budget</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->customer->name ?? 'N/A' }}</td>
                            <td>{{ $service->provider->name ?? 'Unassigned' }}</td>
                            <td>{{ $service->serviceCategory->name ?? 'N/A' }}</td>
                            <td>{{ Str::limit($service->title, 30) }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'accepted' => 'info',
                                        'in_progress' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$service->status] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $service->status)) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $priorityColors = [
                                        'low' => 'success',
                                        'medium' => 'warning',
                                        'high' => 'danger'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $priorityColors[$service->priority] ?? 'secondary' }}">
                                    {{ ucfirst($service->priority) }}
                                </span>
                            </td>
                            <td>Rs. {{ number_format($service->budget, 2) }}</td>
                            <td>{{ $service->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No service requests found for the selected period.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "pageLength": 25,
        "order": [[ 8, "desc" ]], // Sort by created date
        "columnDefs": [
            { "orderable": false, "targets": [4] } // Title column
        ]
    });
});
</script>
@endpush
@endsection
