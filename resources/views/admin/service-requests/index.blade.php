@extends('layouts.admin')

@section('title', 'Service Requests Management')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-list"></i> Service Requests Management
        </h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Requests
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Requests
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_requests'] }}</div>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Accepted Requests
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['accepted_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed Requests
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters & Search</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.service-requests.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search by title, customer, provider...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select class="form-control" id="category" name="category">
                                <option value="">All Categories</option>
                                @foreach($serviceCategories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_from">From Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_from" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_to">To Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_to" 
                                   name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Service Requests Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Service Requests</h6>
        </div>
        <div class="card-body">
            @if($serviceRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Customer</th>
                                <th>Provider</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Budget</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($serviceRequests as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>
                                        <strong>{{ $request->title }}</strong>
                                        @if($request->is_urgent)
                                            <span class="badge badge-danger ml-1">Urgent</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($request->customer->profile_image)
                                                <img src="{{ Storage::url($request->customer->profile_image) }}" 
                                                     alt="{{ $request->customer->name }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 30px; height: 30px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 30px; height: 30px;">
                                                    <i class="fas fa-user text-white fa-sm"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-bold">{{ $request->customer->name }}</div>
                                                <small class="text-muted">{{ $request->customer->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($request->provider)
                                            <div class="d-flex align-items-center">
                                                @if($request->provider->profile_image)
                                                    <img src="{{ Storage::url($request->provider->profile_image) }}" 
                                                         alt="{{ $request->provider->name }}" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 30px; height: 30px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 30px; height: 30px;">
                                                        <i class="fas fa-user text-white fa-sm"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-weight-bold">{{ $request->provider->name }}</div>
                                                    <small class="text-muted">{{ $request->provider->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $request->serviceCategory->name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $request->status === 'completed' ? 'success' : ($request->status === 'cancelled' ? 'danger' : ($request->status === 'accepted' ? 'info' : 'warning')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </td>
                                    <td>Rs. {{ number_format($request->budget, 0) }}</td>
                                    <td>{{ $request->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.service-requests.show', $request) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $serviceRequests->firstItem() }} to {{ $serviceRequests->lastItem() }} of {{ $serviceRequests->total() }} results
                    </div>
                    <div>
                        {{ $serviceRequests->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No service requests found</h5>
                    <p class="text-muted">No service requests match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
