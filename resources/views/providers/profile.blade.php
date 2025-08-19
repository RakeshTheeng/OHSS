@extends('layouts.app')

@section('title', $provider->name . ' - Service Provider Profile')

@section('content')
<div class="container py-5">
    <!-- Provider Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            @if($provider->profile_image)
                                <img src="{{ Storage::url($provider->profile_image) }}" 
                                     alt="{{ $provider->name }}" 
                                     class="rounded-circle mb-3" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-user text-white fa-4x"></i>
                                </div>
                            @endif
                            
                            @php
                                $availabilityStatus = $provider->getAvailabilityStatus();
                            @endphp
                            <span class="badge {{ $availabilityStatus['badge_class'] }} fs-6">
                                {{ $availabilityStatus['status'] }}
                            </span>
                            <div class="text-muted small mt-1">
                                {{ $availabilityStatus['message'] }}
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h1 class="mb-2">{{ $provider->name }}</h1>
                            
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-warning me-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= ($provider->reviews_avg_rating ?? 0) ? '' : '-o' }} fa-lg"></i>
                                    @endfor
                                </div>
                                <span class="text-muted">
                                    {{ number_format($provider->reviews_avg_rating ?? 0, 1) }} 
                                    ({{ $provider->reviews_count }} reviews)
                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                <span>{{ $provider->address ?? 'Location not specified' }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <i class="fas fa-clock text-muted me-2"></i>
                                <span>{{ $provider->experience_years ?? 0 }} years of experience</span>
                            </div>

                            @if($provider->citizenship_number)
                                <div class="mb-3">
                                    <i class="fas fa-id-card text-muted me-2"></i>
                                    <span>Verified ID: {{ substr($provider->citizenship_number, 0, 5) }}***</span>
                                    <i class="fas fa-check-circle text-success ms-1" title="Identity Verified"></i>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                @foreach($provider->serviceCategories as $category)
                                    <span class="badge bg-primary me-2 mb-1">
                                        <i class="{{ $category->icon }} me-1"></i>
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <h3 class="text-success mb-1">Rs. {{ number_format($provider->hourly_rate ?? 0, 0) }}</h3>
                                <small class="text-muted">per hour</small>
                            </div>
                            
                            @auth
                                @if(auth()->user()->role === 'customer')
                                    <a href="{{ route('customer.service-requests.create', ['provider' => $provider->id]) }}"
                                       class="btn btn-primary btn-lg w-100 mb-2">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Send Request
                                    </a>
                                @else
                                    <div class="alert alert-info text-center">
                                        <small>Only customers can send service requests</small>
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 mb-2">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Login to Send Request
                                </a>
                            @endauth
                            
                            <button class="btn btn-outline-secondary w-100" onclick="shareProfile()">
                                <i class="fas fa-share me-2"></i>
                                Share Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- About Section -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        About {{ $provider->name }}
                    </h4>
                </div>
                <div class="card-body">
                    @if($provider->bio)
                        <p class="lead">{{ $provider->bio }}</p>
                    @else
                        <p class="text-muted">No description provided.</p>
                    @endif
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-star me-2"></i>
                        Reviews ({{ $provider->reviews_count }})
                    </h4>
                </div>
                <div class="card-body">
                    @if($recentReviews->count() > 0)
                        @foreach($recentReviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
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
                                        <div>
                                            <h6 class="mb-0">{{ $review->customer->name }}</h6>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0">{{ $review->comment }}</p>
                                
                                @if($review->provider_response)
                                    <div class="mt-2 p-3 bg-light rounded">
                                        <strong>Provider Response:</strong>
                                        <p class="mb-0 mt-1">{{ $review->provider_response }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No reviews yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Rating Breakdown -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Rating Breakdown
                    </h5>
                </div>
                <div class="card-body">
                    @if($provider->reviews_count > 0)
                        @for($i = 5; $i >= 1; $i--)
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">{{ $i }}</span>
                                <i class="fas fa-star text-warning me-2"></i>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" 
                                         style="width: {{ $provider->reviews_count > 0 ? ($ratingDistribution[$i] / $provider->reviews_count) * 100 : 0 }}%"></div>
                                </div>
                                <small class="text-muted">{{ $ratingDistribution[$i] ?? 0 }}</small>
                            </div>
                        @endfor
                    @else
                        <p class="text-muted text-center">No ratings yet</p>
                    @endif
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Provider Info
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Member Since:</strong>
                        <div class="text-muted">{{ $provider->created_at->format('F Y') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Response Time:</strong>
                        <div class="text-muted">Usually responds within 2 hours</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Languages:</strong>
                        <div class="text-muted">English, Nepali</div>
                    </div>
                    
                    @if($provider->phone)
                        <div class="mb-3">
                            <strong>Phone:</strong>
                            <div class="text-muted">{{ $provider->phone }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Completed Services Portfolio -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Service Portfolio
                    </h5>
                </div>
                <div class="card-body">
                    @if($completedServices->count() > 0)
                        <div class="row">
                            @foreach($completedServices->take(6) as $service)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-2">{{ $service->title }}</h6>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>
                                                    {{ $service->customer->name }}
                                                </small>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $service->updated_at->format('M Y') }}
                                                </small>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>
                                                    Completed
                                                </span>
                                                @if($service->booking && $service->booking->review)
                                                    <div class="text-warning">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star{{ $i <= $service->booking->review->rating ? '' : '-o' }}"></i>
                                                        @endfor
                                                        <small class="text-muted ms-1">({{ $service->booking->review->rating }})</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($completedServices->count() > 6)
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    Showing 6 of {{ $completedServices->count() }} completed services
                                </small>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No completed services yet</p>
                            <small class="text-muted">Completed services will appear here once the provider finishes their first job.</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Similar Providers -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Similar Providers
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <a href="{{ route('providers.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-search me-2"></i>
                            Browse All Providers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function shareProfile() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $provider->name }} - Service Provider',
            text: 'Check out this service provider on {{ config("app.name") }}',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(function() {
            alert('Profile link copied to clipboard!');
        });
    }
}
</script>
@endsection
