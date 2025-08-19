@extends('layouts.customer')

@section('title', $provider->name . ' - Provider Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Provider Profile</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.providers.index') }}">Find Services</a></li>
                <li class="breadcrumb-item active">{{ $provider->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Provider Info -->
        <div class="col-lg-8">
            <!-- Main Profile Card -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($provider->profile_image)
                                <img src="{{ Storage::url($provider->profile_image) }}"
                                     alt="{{ $provider->name }}"
                                     class="rounded-circle mb-3"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto"
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-user text-white fa-3x"></i>
                                </div>
                            @endif
                            
                            <div class="text-warning mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= ($provider->rating ?? 0) ? '' : '-o' }} fa-lg"></i>
                                @endfor
                            </div>
                            <div class="text-muted">
                                {{ number_format($reviewStats['average'], 1) }} out of 5 
                                ({{ $reviewStats['total'] }} reviews)
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <h2 class="mb-3">{{ $provider->name }}</h2>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-money-bill-wave text-success me-2"></i>
                                        <span class="fw-bold">Rs. {{ number_format($provider->hourly_rate ?? 0, 0) }}/hour</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-calendar text-primary me-2"></i>
                                        <span>{{ $provider->experience_years ?? 0 }} years experience</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                        <span>{{ $provider->address ?? 'Location not specified' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-2">
                                        @if($provider->is_available)
                                            <i class="fas fa-circle text-success me-2"></i>
                                            <span class="text-success">Available</span>
                                        @else
                                            <i class="fas fa-circle text-danger me-2"></i>
                                            <span class="text-danger">Currently Busy</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($provider->description)
                                <div class="mb-3">
                                    <h6 class="text-primary">About</h6>
                                    <p class="text-muted">{{ $provider->description }}</p>
                                </div>
                            @endif

                            <!-- Service Categories -->
                            <div class="mb-3">
                                <h6 class="text-primary">Services Offered</h6>
                                <div>
                                    @foreach($provider->serviceCategories as $category)
                                        <span class="badge bg-primary me-2 mb-2">
                                            <i class="{{ $category->icon }} me-1"></i>
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('customer.service-requests.create', ['provider' => $provider->id]) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Send Service Request
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="toggleFavorite({{ $provider->id }})">
                                    <i class="fas fa-heart"></i> Add to Favorites
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Reviews</h6>
                </div>
                <div class="card-body">
                    @if($reviewStats['total'] > 0)
                        <!-- Review Summary -->
                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                <div class="display-4 text-primary">{{ number_format($reviewStats['average'], 1) }}</div>
                                <div class="text-warning mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= round($reviewStats['average']) ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                                <div class="text-muted">{{ $reviewStats['total'] }} reviews</div>
                            </div>
                            <div class="col-md-8">
                                @for($rating = 5; $rating >= 1; $rating--)
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="me-2">{{ $rating }} star</span>
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar bg-warning" 
                                                 style="width: {{ $reviewStats['total'] > 0 ? ($reviewStats['distribution'][$rating] / $reviewStats['total']) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        <span class="text-muted small">{{ $reviewStats['distribution'][$rating] }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Individual Reviews -->
                        <div class="reviews-list">
                            @foreach($provider->reviews as $review)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-3">
                                            @if($review->customer->profile_image)
                                                <img src="{{ Storage::url($review->customer->profile_image) }}"
                                                     alt="{{ $review->customer->name }}"
                                                     class="rounded-circle"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">{{ $review->customer->name }}</div>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="text-muted small">
                                            {{ $review->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-muted mb-0">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No reviews yet</h5>
                            <p class="text-muted">Be the first to review this provider!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <div class="h4 text-primary">{{ $provider->experience_years ?? 0 }}</div>
                            <div class="text-muted small">Years Experience</div>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-success">{{ $reviewStats['total'] }}</div>
                            <div class="text-muted small">Total Reviews</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        <span>{{ $provider->email }}</span>
                    </div>
                    @if($provider->phone)
                        <div class="mb-3">
                            <i class="fas fa-phone text-success me-2"></i>
                            <span>{{ $provider->phone }}</span>
                        </div>
                    @endif
                    @if($provider->address)
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                            <span>{{ $provider->address }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Verification Status -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Verification Status</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span>Identity Verified</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span>Background Checked</span>
                    </div>
                    @if($provider->kycDocuments->count() > 0)
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span>Documents Verified</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFavorite(providerId) {
    // This will be implemented when we add the favorites functionality
    alert('Favorites functionality will be implemented soon!');
}
</script>

<style>
.text-warning .fas.fa-star {
    color: #f6c23e;
}

.text-warning .fas.fa-star-o {
    color: #d1d3e2;
}

.progress {
    background-color: #e9ecef;
}

.border-end {
    border-right: 1px solid #e3e6f0 !important;
}
</style>
@endsection
