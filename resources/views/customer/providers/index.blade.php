@extends('layouts.customer')

@section('title', 'Find Services')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Find Services</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Find Services</li>
            </ol>
        </nav>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('customer.providers.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Providers</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by name...">
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
                
                <div class="col-md-3">
                    <label for="sort" class="form-label">Sort By</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="rate_low" {{ request('sort') == 'rate_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="rate_high" {{ request('sort') == 'rate_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="experience" {{ request('sort') == 'experience' ? 'selected' : '' }}>Most Experienced</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="min_rate" class="form-label">Min Rate (Rs/hr)</label>
                            <input type="number" class="form-control" id="min_rate" name="min_rate" 
                                   value="{{ request('min_rate') }}" placeholder="0">
                        </div>
                        <div class="col-md-3">
                            <label for="max_rate" class="form-label">Max Rate (Rs/hr)</label>
                            <input type="number" class="form-control" id="max_rate" name="max_rate" 
                                   value="{{ request('max_rate') }}" placeholder="10000">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="{{ route('customer.providers.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <span class="text-muted">
                Showing {{ $providers->firstItem() ?? 0 }} to {{ $providers->lastItem() ?? 0 }} 
                of {{ $providers->total() }} providers
            </span>
        </div>
    </div>

    <!-- Providers Grid -->
    @if($providers->count() > 0)
        <div class="row">
            @foreach($providers as $provider)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm provider-card">
                        <div class="card-body">
                            <!-- Provider Header -->
                            <div class="d-flex align-items-center mb-3">
                                @if($provider->profile_image)
                                    <img src="{{ Storage::url($provider->profile_image) }}"
                                         alt="{{ $provider->name }}"
                                         class="rounded-circle me-3"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-user text-white fa-lg"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">{{ $provider->name }}</h5>
                                    <div class="text-warning mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= ($provider->rating ?? 0) ? '' : '-o' }}"></i>
                                        @endfor
                                        <span class="text-muted small ms-1">
                                            ({{ number_format($provider->rating ?? 0, 1) }})
                                        </span>
                                    </div>
                                    <div class="text-success fw-bold">
                                        Rs. {{ number_format($provider->hourly_rate ?? 0, 0) }}/hr
                                    </div>
                                </div>
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
                                    <span class="badge bg-secondary">+{{ $provider->serviceCategories->count() - 3 }}</span>
                                @endif
                            </div>

                            <!-- Provider Stats -->
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <div class="text-primary fw-bold">{{ $provider->experience_years ?? 0 }}</div>
                                    <div class="text-muted small">Years Exp.</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-success fw-bold">{{ $provider->reviews->count() }}</div>
                                    <div class="text-muted small">Reviews</div>
                                </div>
                                <div class="col-4">
                                    @if($provider->is_available)
                                        <div class="text-success">
                                            <i class="fas fa-circle"></i>
                                        </div>
                                        <div class="text-muted small">Available</div>
                                    @else
                                        <div class="text-danger">
                                            <i class="fas fa-circle"></i>
                                        </div>
                                        <div class="text-muted small">Busy</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Description -->
                            @if($provider->description)
                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit($provider->description, 100) }}
                                </p>
                            @endif

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('customer.providers.show', $provider) }}" 
                                   class="btn btn-outline-primary flex-fill">
                                    <i class="fas fa-eye"></i> View Profile
                                </a>
                                <a href="{{ route('customer.service-requests.create', ['provider' => $provider->id]) }}" 
                                   class="btn btn-primary flex-fill">
                                    <i class="fas fa-paper-plane"></i> Send Request
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $providers->appends(request()->query())->links() }}
        </div>
    @else
        <!-- No Results -->
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No providers found</h4>
                <p class="text-muted">Try adjusting your search criteria or browse all available services.</p>
                <a href="{{ route('customer.providers.index') }}" class="btn btn-primary">
                    <i class="fas fa-refresh"></i> View All Providers
                </a>
            </div>
        </div>
    @endif
</div>

<style>
.provider-card {
    transition: transform 0.2s ease-in-out;
    border: 1px solid #e3e6f0;
}

.provider-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.badge {
    font-size: 0.75em;
}

.text-warning .fas.fa-star {
    color: #f6c23e;
}

.text-warning .fas.fa-star-o {
    color: #d1d3e2;
}
</style>
@endsection
