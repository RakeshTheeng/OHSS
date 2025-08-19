@extends('layouts.app')

@section('title', 'Service Providers')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="text-center mb-4">
                <h1 class="display-4 fw-bold text-primary">Service Providers</h1>
                <p class="lead text-muted">Find the perfect professional for your household needs</p>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <form method="GET" action="{{ route('providers.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       placeholder="Provider name or location..." 
                                       value="{{ request('search') }}">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="category" class="form-label">Service Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">All Categories</option>
                                    @foreach($serviceCategories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="rating" class="form-label">Min Rating</label>
                                <select class="form-select" id="rating" name="rating">
                                    <option value="">Any Rating</option>
                                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2+ Stars</option>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="sort" class="form-label">Sort By</label>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                                    <option value="reviews" {{ request('sort') == 'reviews' ? 'selected' : '' }}>Most Reviews</option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                </select>
                            </div>
                            
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Providers Grid -->
    <div class="row">
        @if($providers->count() > 0)
            @foreach($providers as $provider)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <!-- Provider Header -->
                            <div class="d-flex align-items-center mb-3">
                                @if($provider->profile_image)
                                    <img src="{{ Storage::url($provider->profile_image) }}" 
                                         alt="{{ $provider->name }}" 
                                         class="rounded-circle me-3" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">{{ $provider->name }}</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="text-warning me-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= ($provider->reviews_avg_rating ?? 0) ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        <small class="text-muted">({{ $provider->reviews_count }})</small>
                                    </div>
                                </div>
                                @php
                                    $availabilityStatus = $provider->getAvailabilityStatus();
                                @endphp
                                <span class="badge {{ $availabilityStatus['badge_class'] }}">
                                    {{ $availabilityStatus['status'] }}
                                </span>
                            </div>

                            <!-- Service Categories -->
                            <div class="mb-3">
                                @foreach($provider->serviceCategories->take(3) as $category)
                                    <span class="badge bg-primary me-1 mb-1">
                                        <i class="{{ $category->icon }} me-1"></i>
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                                @if($provider->serviceCategories->count() > 3)
                                    <span class="badge bg-secondary">+{{ $provider->serviceCategories->count() - 3 }} more</span>
                                @endif
                            </div>

                            <!-- Bio -->
                            @if($provider->bio)
                                <p class="text-muted small mb-3">{{ Str::limit($provider->bio, 100) }}</p>
                            @endif

                            <!-- Experience and Verification -->
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <div class="text-primary fw-bold">{{ $provider->experience_years ?? 0 }}</div>
                                    <div class="text-muted small">Years Exp.</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-success fw-bold">Rs. {{ number_format($provider->hourly_rate ?? 0, 0) }}</div>
                                    <div class="text-muted small">Per Hour</div>
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
                            </div>

                            @if($provider->address)
                                <div class="mb-3">
                                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                    <small class="text-muted">{{ Str::limit($provider->address, 50) }}</small>
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer -->
                        <div class="card-footer bg-transparent">
                            <div class="d-flex gap-2">
                                <a href="{{ route('providers.profile', $provider) }}" 
                                   class="btn btn-outline-primary flex-grow-1">
                                    <i class="fas fa-eye me-1"></i>
                                    View Profile
                                </a>
                                
                                @auth
                                    @if(auth()->user()->role === 'customer')
                                        <a href="{{ route('customer.service-requests.create', ['provider' => $provider->id]) }}"
                                           class="btn btn-primary" title="Send Request">
                                            <i class="fas fa-paper-plane"></i>
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary" title="Login to Send Request">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No providers found</h4>
                        <p class="text-muted">Try adjusting your search criteria or browse all categories</p>
                        <a href="{{ route('providers.index') }}" class="btn btn-primary">
                            <i class="fas fa-refresh me-2"></i>
                            Clear Filters
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($providers->hasPages())
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $providers->appends(request()->query())->links() }}
            </div>
        </div>
    @endif

    <!-- Call to Action -->
    @guest
        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center py-4">
                        <h4 class="mb-3">Ready to get started?</h4>
                        <p class="mb-3">Join thousands of satisfied customers and find the perfect service provider for your needs.</p>
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                Sign Up as Customer
                            </a>
                            <a href="{{ route('provider.register.form') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-briefcase me-2"></i>
                                Become a Provider
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endguest
</div>


@endsection
