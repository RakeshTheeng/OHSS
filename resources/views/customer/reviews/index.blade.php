@extends('layouts.customer')

@section('title', 'My Reviews')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Reviews</h1>
        <a href="{{ route('customer.reviews.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Write Review
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Reviews
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
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
                                Average Rating
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['average_rating'], 1) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                                5-Star Reviews
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['five_star'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trophy fa-2x text-gray-300"></i>
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
                                Pending Reviews
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('customer.reviews.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" placeholder="Search reviews...">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="rating">
                        <option value="">All Ratings</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('customer.reviews.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Your Reviews</h6>
        </div>
        <div class="card-body">
            @if($reviews->count() > 0)
                <div class="row">
                    @foreach($reviews as $review)
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 review-card">
                                <div class="card-body">
                                    <!-- Service Info -->
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="{{ $review->booking->serviceRequest->serviceCategory->icon }} text-primary me-3 fa-lg"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $review->booking->serviceRequest->title }}</h6>
                                            <small class="text-muted">{{ $review->booking->serviceRequest->serviceCategory->name }}</small>
                                        </div>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                    </div>

                                    <!-- Provider Info -->
                                    <div class="d-flex align-items-center mb-3">
                                        @if($review->provider->profile_image)
                                            <img src="{{ Storage::url($review->provider->profile_image) }}" 
                                                 class="rounded-circle me-2" 
                                                 width="40" height="40" 
                                                 alt="{{ $review->provider->name }}"
                                                 style="object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $review->provider->name }}</div>
                                            <small class="text-muted">Service Provider</small>
                                        </div>
                                    </div>

                                    <!-- Review Comment -->
                                    @if($review->comment)
                                        <div class="mb-3">
                                            <p class="text-muted mb-0">"{{ $review->comment }}"</p>
                                        </div>
                                    @endif

                                    <!-- Review Date -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $review->created_at->format('M d, Y') }}
                                        </small>
                                        <small class="text-muted">
                                            Service Date: {{ $review->booking->scheduled_date->format('M d, Y') }}
                                        </small>
                                    </div>

                                    <!-- Actions -->
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('customer.reviews.show', $review) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                        
                                        @if($review->created_at->diffInHours(now()) <= 24)
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('customer.reviews.edit', $review) }}" 
                                                   class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form method="POST" 
                                                      action="{{ route('customer.reviews.destroy', $review) }}" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this review?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $reviews->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No reviews found</h5>
                    <p class="text-muted">
                        @if(request()->filled('search') || request()->filled('rating'))
                            Try adjusting your search criteria or 
                            <a href="{{ route('customer.reviews.index') }}">view all reviews</a>.
                        @else
                            You haven't written any reviews yet. Share your experience with completed services!
                        @endif
                    </p>
                    @if($stats['pending'] > 0)
                        <a href="{{ route('customer.reviews.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Write Your First Review
                        </a>
                    @else
                        <a href="{{ route('customer.providers.index') }}" class="btn btn-primary">
                            <i class="fas fa-search"></i> Find Services
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
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

.review-card {
    transition: transform 0.2s ease-in-out;
    border: 1px solid #e3e6f0;
}

.review-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.text-warning .fas.fa-star {
    color: #f6c23e;
}

.text-warning .fas.fa-star-o {
    color: #d1d3e2;
}
</style>
@endsection
