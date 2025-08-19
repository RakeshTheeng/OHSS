@extends('layouts.customer')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="mb-0">Welcome back, {{ auth()->user()->name }}!</h2>
                            <p class="mb-0">Find the best household services in your area.</p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('customer.service-requests.create') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                Request Service
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Banners -->
    @if($accepted_requests->count() > 0)
        <div class="alert alert-success alert-dismissible fade show mb-4 notification-banner" role="alert">
            <div class="d-flex align-items-center">
                <div class="notification-icon">
                    <i class="fas fa-check-circle fa-3x text-success"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h4 class="alert-heading mb-2">
                        <i class="fas fa-party-horn me-2"></i>
                        🎉 Great News! Your Request{{ $accepted_requests->count() > 1 ? 's Have' : ' Has' }} Been Accepted!
                    </h4>
                    @foreach($accepted_requests as $request)
                        <div class="accepted-request-card mb-3 p-3 bg-white rounded shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    @if($request->provider->profile_image)
                                        <img src="{{ Storage::url($request->provider->profile_image) }}"
                                             class="rounded-circle"
                                             width="60" height="60"
                                             alt="{{ $request->provider->name }}"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-user text-white fa-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1 text-primary">{{ $request->provider->name }}</h6>
                                    <p class="mb-1"><strong>Service:</strong> {{ $request->title }}</p>
                                    <p class="mb-1"><strong>Category:</strong> {{ $request->serviceCategory->name }}</p>
                                    <div class="text-warning small">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= ($request->provider->rating ?? 0) ? '' : '-o' }}"></i>
                                        @endfor
                                        <span class="text-muted ms-1">({{ number_format($request->provider->rating ?? 0, 1) }})</span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="mb-2">
                                        <span class="badge bg-success p-2">
                                            <i class="fas fa-check me-1"></i>Accepted
                                        </span>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('customer.bookings.create', ['service_request' => $request->id]) }}"
                                           class="btn btn-success btn-sm">
                                            <i class="fas fa-calendar-plus me-1"></i> Book Now
                                        </a>
                                        <a href="{{ route('customer.service-requests.show', $request) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Click "Book Now" to schedule your service and select payment method.
                        </small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Recent Notifications -->
    @if($notifications->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Notifications</h6>
                <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @foreach($notifications as $notification)
                    <div class="d-flex align-items-center p-3 border-bottom">
                        <div class="me-3">
                            <div class="icon-circle bg-primary">
                                <i class="{{ $notification->icon }} text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $notification->title }}</div>
                            <div class="text-muted small">{{ $notification->message }}</div>
                        </div>
                        <div class="text-muted small">
                            {{ $notification->time_ago }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

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
                                Upcoming Bookings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['upcoming_bookings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
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
                                Total Spent
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($stats['total_spent'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Categories -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Popular Services</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($service_categories as $category)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <div class="card text-center h-100 service-category-card">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="{{ $category->icon }} fa-3x text-primary"></i>
                                        </div>
                                        <h6 class="card-title">{{ $category->name }}</h6>
                                        <p class="card-text small text-muted">{{ Str::limit($category->description, 60) }}</p>
                                        <a href="{{ route('customer.providers.index', ['category' => $category->id]) }}" class="btn btn-primary btn-sm">Find Providers</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Rated Providers -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Top Rated Service Providers</h6>
                    <a href="{{ route('customer.providers.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($top_providers->count() > 0)
                        <div class="row">
                            @foreach($top_providers as $provider)
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="card h-100 provider-card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                @if($provider->profile_image)
                                                    <img src="{{ Storage::url($provider->profile_image) }}"
                                                         alt="{{ $provider->name }}"
                                                         class="rounded-circle me-3"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $provider->name }}</h6>
                                                    <div class="text-warning">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star{{ $i <= ($provider->rating ?? 0) ? '' : '-o' }}"></i>
                                                        @endfor
                                                        <span class="text-muted small ms-1">({{ $provider->rating ?? 0 }})</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                @foreach($provider->serviceCategories->take(2) as $category)
                                                    <span class="badge bg-primary me-1">
                                                        <i class="{{ $category->icon }} me-1"></i>
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                                @if($provider->serviceCategories->count() > 2)
                                                    <span class="badge bg-secondary">+{{ $provider->serviceCategories->count() - 2 }}</span>
                                                @endif
                                            </div>

                                            <div class="row text-center mb-3">
                                                <div class="col-4">
                                                    <div class="text-primary fw-bold">{{ $provider->experience_years ?? 0 }}</div>
                                                    <div class="text-muted small">Years</div>
                                                </div>
                                                <div class="col-4">
                                                    @if($provider->citizenship_number)
                                                        <div class="text-success">
                                                            <i class="fas fa-check-circle"></i>
                                                        </div>
                                                        <div class="text-muted small">Verified</div>
                                                    @else
                                                        <div class="text-muted">
                                                            <i class="fas fa-clock"></i>
                                                        </div>
                                                        <div class="text-muted small">Pending</div>
                                                    @endif
                                                </div>
                                                <div class="col-4">
                                                    <div class="text-warning">
                                                        <i class="fas fa-star"></i>
                                                    </div>
                                                    <div class="text-muted small">{{ number_format($provider->rating ?? 0, 1) }}</div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-success fw-bold">
                                                    Rs. {{ number_format($provider->hourly_rate ?? 0, 0) }}/hr
                                                </div>
                                                <div>
                                                    <a href="{{ route('customer.providers.show', $provider) }}"
                                                       class="btn btn-outline-primary btn-sm me-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('customer.service-requests.create', ['provider' => $provider->id]) }}"
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No providers available at the moment</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Service Requests -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Service Requests</h6>
                    <a href="{{ route('customer.service-requests.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recent_requests->count() > 0)
                        @foreach($recent_requests as $request)
                            <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="{{ $request->serviceCategory->icon }} text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">{{ $request->serviceCategory->name }}</div>
                                    <div class="text-gray-600">Provider: {{ $request->provider->name }}</div>
                                    <div class="small text-gray-500">{{ $request->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-{{ $request->status_badge }}">{{ ucfirst($request->status) }}</span>
                                    <div class="mt-1">
                                        <a href="{{ route('customer.service-requests.show', $request) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No service requests yet</p>
                            <a href="{{ route('customer.service-requests.create') }}" class="btn btn-primary">Create Your First Request</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Upcoming Bookings</h6>
                    <a href="{{ route('customer.bookings.index') }}" class="btn btn-sm btn-success">View All</a>
                </div>
                <div class="card-body">
                    @if($upcoming_bookings->count() > 0)
                        @foreach($upcoming_bookings as $booking)
                            <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                <div class="mr-3">
                                    <div class="icon-circle bg-success">
                                        <i class="fas fa-calendar text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">{{ $booking->serviceRequest->serviceCategory->name }}</div>
                                    <div class="text-gray-600">Provider: {{ $booking->provider->name }}</div>
                                    <div class="small text-gray-500">{{ $booking->scheduled_date->format('M d, Y H:i') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-weight-bold text-success">Rs. {{ number_format($booking->total_amount, 2) }}</div>
                                    <div class="mt-1">
                                        <a href="{{ route('customer.bookings.show', $booking) }}" class="btn btn-sm btn-success">View</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No upcoming bookings</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.service-category-card {
    transition: transform 0.3s ease;
    cursor: pointer;
}
.service-category-card:hover {
    transform: translateY(-5px);
}
.badge-pending { background-color: #f6c23e; }
.badge-accepted { background-color: #1cc88a; }
.badge-rejected { background-color: #e74a3b; }
.badge-completed { background-color: #1cc88a; }
.badge-confirmed { background-color: #36b9cc; }
.badge-cancelled { background-color: #858796; }
</style>
@endsection
