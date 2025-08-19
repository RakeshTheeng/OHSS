@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user me-2"></i>User Details
            </h1>
            <p class="text-muted mb-0">{{ $user->name }} - {{ ucfirst($user->role) }}</p>
        </div>
        <div class="d-sm-flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i>Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <!-- User Information -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($user->profile_image)
                            <img src="{{ Storage::url($user->profile_image) }}" 
                                 class="rounded-circle mb-3" width="120" height="120" alt="Profile">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                 style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <h5 class="font-weight-bold">{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <span class="badge bg-{{ $user->role === 'customer' ? 'primary' : 'success' }} p-2">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }} p-2">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>

                    @if($user->role === 'provider')
                        <div class="mb-3">
                            <span class="badge bg-{{ $user->provider_status === 'approved' ? 'success' : ($user->provider_status === 'pending' ? 'warning' : 'danger') }} p-2">
                                Provider: {{ ucfirst($user->provider_status) }}
                            </span>
                        </div>
                    @endif

                    <div class="btn-group w-100 mb-3" role="group">
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-{{ $user->status === 'active' ? 'warning' : 'success' }} btn-sm">
                                <i class="fas fa-{{ $user->status === 'active' ? 'pause' : 'play' }} me-1"></i>
                                {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-info btn-sm" 
                                    onclick="return confirm('Are you sure you want to reset this user\'s password?')">
                                <i class="fas fa-key me-1"></i>Reset Password
                            </button>
                        </form>
                    </div>

                    @if($user->role === 'provider' && $user->provider_status === 'pending')
                        <div class="btn-group w-100" role="group">
                            <form method="POST" action="{{ route('admin.users.approve-provider', $user) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check me-1"></i>Approve Provider
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.reject-provider', $user) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-times me-1"></i>Reject Provider
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Phone:</strong>
                        <div>{{ $user->phone ?? 'Not provided' }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Address:</strong>
                        <div>{{ $user->address ?? 'Not provided' }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Joined:</strong>
                        <div>{{ $user->created_at->format('M d, Y H:i A') }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <div>{{ $user->updated_at->format('M d, Y H:i A') }}</div>
                    </div>
                    @if($user->email_verified_at)
                        <div class="mb-3">
                            <strong>Email Verified:</strong>
                            <div>{{ $user->email_verified_at->format('M d, Y H:i A') }}</div>
                        </div>
                    @else
                        <div class="mb-3">
                            <strong>Email Status:</strong>
                            <span class="badge bg-warning">Not Verified</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Provider Details & Service Categories -->
        <div class="col-lg-8 mb-4">
            @if($user->role === 'provider')
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Provider Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Hourly Rate:</strong>
                                <div class="h5 text-success">Rs. {{ number_format($user->hourly_rate ?? 0, 2) }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Experience:</strong>
                                <div class="h5 text-info">{{ $user->experience_years ?? 0 }} years</div>
                            </div>
                        </div>
                        @if($user->bio)
                            <div class="mb-3">
                                <strong>Bio:</strong>
                                <div class="mt-2">{{ $user->bio }}</div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <strong>Availability:</strong>
                            <span class="badge bg-{{ $user->is_available ? 'success' : 'secondary' }}">
                                {{ $user->is_available ? 'Available' : 'Not Available' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Service Categories -->
                @if($user->serviceCategories->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-success">Service Categories</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($user->serviceCategories as $category)
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $category->icon }} fa-2x text-primary me-3"></i>
                                            <div>
                                                <div class="font-weight-bold">{{ $category->name }}</div>
                                                <div class="text-muted small">{{ Str::limit($category->description, 50) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Service Requests -->
            @php
                $serviceRequests = $user->role === 'customer' ? $user->customerRequests : $user->providerRequests;
            @endphp
            @if($serviceRequests && $serviceRequests->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Service Requests</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($serviceRequests->take(5) as $request)
                                        <tr>
                                            <td>{{ $request->serviceCategory->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.service-requests.show', $request) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Bookings -->
            @php
                $bookings = $user->role === 'customer' ? $user->customerBookings : $user->providerBookings;
            @endphp
            @if($bookings && $bookings->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ $user->role === 'customer' ? 'Provider' : 'Customer' }}</th>
                                        <th>Scheduled Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings->take(5) as $booking)
                                        <tr>
                                            <td>
                                                @if($user->role === 'customer')
                                                    {{ $booking->provider->name ?? 'N/A' }}
                                                @else
                                                    {{ $booking->customer->name ?? 'N/A' }}
                                                @endif
                                            </td>
                                            <td>{{ $booking->scheduled_date ? $booking->scheduled_date->format('M d, Y H:i') : 'N/A' }}</td>
                                            <td>Rs. {{ number_format($booking->total_amount ?? 0, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $booking->status === 'completed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Reviews -->
            @if($user->reviews->count() > 0)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Reviews</h6>
                    </div>
                    <div class="card-body">
                        @foreach($user->reviews->take(3) as $review)
                            <div class="mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong>{{ $review->customer->name ?? 'Anonymous' }}</strong>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                            @endfor
                                            <span class="text-muted">({{ $review->rating }}/5)</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
