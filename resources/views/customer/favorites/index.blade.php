@extends('layouts.customer')

@section('title', 'Favorite Providers')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-heart text-danger"></i> Favorite Providers
        </h1>
        <div class="text-muted">
            {{ $favorites->total() }} favorite{{ $favorites->total() !== 1 ? 's' : '' }}
        </div>
    </div>

    @if($favorites->count() > 0)
        <!-- Favorites Grid -->
        <div class="row">
            @foreach($favorites as $favorite)
                @php $provider = $favorite->provider; @endphp
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <!-- Provider Header -->
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    @if($provider->profile_image)
                                        <img src="{{ Storage::url($provider->profile_image) }}" 
                                             alt="{{ $provider->name }}" 
                                             class="rounded-circle" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" 
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-user fa-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">{{ $provider->name }}</h5>
                                    <div class="text-muted small">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ Str::limit($provider->address ?? 'Location not specified', 30) }}
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                            type="button" 
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('customer.providers.show', $provider) }}">
                                                <i class="fas fa-eye"></i> View Profile
                                            </a>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('customer.favorites.toggle', $provider) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-heart-broken"></i> Remove from Favorites
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Rating -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="text-warning me-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= ($provider->reviews_avg_rating ?? 0) ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-muted small">
                                        {{ number_format($provider->reviews_avg_rating ?? 0, 1) }} 
                                        ({{ $provider->reviews_count ?? 0 }} reviews)
                                    </span>
                                </div>
                            </div>

                            <!-- Services -->
                            <div class="mb-3">
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($provider->serviceCategories->take(3) as $category)
                                        <span class="badge bg-primary">{{ $category->name }}</span>
                                    @endforeach
                                    @if($provider->serviceCategories->count() > 3)
                                        <span class="badge bg-secondary">+{{ $provider->serviceCategories->count() - 3 }} more</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Hourly Rate -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i>
                                    <strong class="text-success">Rs. {{ number_format($provider->hourly_rate ?? 0) }}/hour</strong>
                                </div>
                            </div>

                            <!-- Experience -->
                            @if($provider->experience_years)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fas fa-clock me-2"></i>
                                        <span>{{ $provider->experience_years }} years experience</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Availability Status -->
                            <div class="mb-3">
                                @if($provider->is_available)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Available
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock"></i> Busy
                                    </span>
                                @endif
                            </div>

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

                        <!-- Card Footer -->
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                <i class="fas fa-heart text-danger"></i>
                                Added to favorites {{ $favorite->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $favorites->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-heart fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted mb-3">No Favorite Providers Yet</h4>
            <p class="text-muted mb-4">
                Start exploring our service providers and add your favorites for quick access!
            </p>
            <a href="{{ route('customer.providers.index') }}" class="btn btn-primary">
                <i class="fas fa-search"></i> Find Service Providers
            </a>
        </div>
    @endif
</div>
@endsection
