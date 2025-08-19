@extends('layouts.admin')

@section('title', 'Provider Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-tie me-2"></i>Provider Report
            </h1>
            <p class="text-muted mb-0">Provider registration and performance report from {{ $startDate }} to {{ $endDate }}</p>
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Providers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $providers->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $providers->where('provider_status', 'approved')->count() }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $providers->where('provider_status', 'pending')->count() }}</div>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Bookings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $providers->sum('bookings_count') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Providers Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Provider Details</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Service Requests</th>
                            <th>Bookings</th>
                            <th>Reviews</th>
                            <th>Service Categories</th>
                            <th>Joined Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($providers as $provider)
                        <tr>
                            <td>{{ $provider->id }}</td>
                            <td>{{ $provider->name }}</td>
                            <td>{{ $provider->email }}</td>
                            <td>{{ $provider->phone ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$provider->provider_status] ?? 'secondary' }}">
                                    {{ ucfirst($provider->provider_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ $provider->service_requests_count }}</span>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $provider->bookings_count }}</span>
                            </td>
                            <td>
                                <span class="badge badge-success">{{ $provider->reviews_count }}</span>
                            </td>
                            <td>
                                @if($provider->serviceCategories->count() > 0)
                                    @foreach($provider->serviceCategories as $category)
                                        <span class="badge badge-secondary">{{ $category->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                            <td>{{ $provider->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">No providers found for the selected period.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Provider Performance Charts -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Performing Providers</h6>
                </div>
                <div class="card-body">
                    @php
                        $topProviders = $providers->sortByDesc('bookings_count')->take(5);
                    @endphp
                    @foreach($topProviders as $provider)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>{{ $provider->name }}</span>
                            <span>{{ $provider->bookings_count }} bookings</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $providers->max('bookings_count') > 0 ? ($provider->bookings_count / $providers->max('bookings_count')) * 100 : 0 }}%">
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
                    <h6 class="m-0 font-weight-bold text-primary">Provider Status Distribution</h6>
                </div>
                <div class="card-body">
                    @php
                        $statusGroups = $providers->groupBy('provider_status');
                    @endphp
                    @foreach($statusGroups as $status => $statusProviders)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>{{ ucfirst($status) }}</span>
                            <span>{{ $statusProviders->count() }} providers</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-{{ $statusColors[$status] ?? 'secondary' }}" role="progressbar" 
                                 style="width: {{ ($statusProviders->count() / $providers->count()) * 100 }}%">
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
        "order": [[ 9, "desc" ]], // Sort by joined date
        "columnDefs": [
            { "orderable": false, "targets": [8] } // Service categories column
        ]
    });
});
</script>
@endpush
@endsection
