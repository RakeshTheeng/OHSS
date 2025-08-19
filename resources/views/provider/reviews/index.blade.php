@extends('layouts.provider')

@section('title', 'Reviews & Ratings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-star me-2"></i>
            Reviews & Ratings
        </h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Reviews</div>
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Average Rating</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['average_rating'], 1) }}
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= round($stats['average_rating']) ? '' : '-o' }}"></i>
                                    @endfor
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">5-Star Reviews</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['five_star'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-thumbs-up fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Response Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @php
                                    $responseRate = $stats['total'] > 0 ? 
                                        (App\Models\Review::where('provider_id', auth()->id())->whereNotNull('provider_response')->count() / $stats['total']) * 100 : 0;
                                @endphp
                                {{ number_format($responseRate, 0) }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-reply fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Distribution -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rating Distribution</h6>
        </div>
        <div class="card-body">
            @for($i = 5; $i >= 1; $i--)
                <div class="d-flex align-items-center mb-2">
                    <span class="me-2">{{ $i }}</span>
                    <i class="fas fa-star text-warning me-2"></i>
                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                        <div class="progress-bar bg-warning" 
                             style="width: {{ $stats['total'] > 0 ? ($stats[strtolower(number_to_words($i)) . '_star'] / $stats['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="text-muted">{{ $stats[strtolower(number_to_words($i)) . '_star'] ?? 0 }}</small>
                </div>
            @endfor
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Reviews</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('provider.reviews.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rating">Rating</label>
                            <select name="rating" id="rating" class="form-control">
                                <option value="">All Ratings</option>
                                <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 Stars</option>
                                <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4 Stars</option>
                                <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3 Stars</option>
                                <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>2 Stars</option>
                                <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>1 Star</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Search by customer name or comment..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('provider.reviews.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Customer Reviews</h6>
        </div>
        <div class="card-body">
            @if($reviews->count() > 0)
                @foreach($reviews as $review)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-3">
                                        @if($review->customer->profile_image)
                                            <img src="{{ Storage::url($review->customer->profile_image) }}" 
                                                 alt="{{ $review->customer->name }}" 
                                                 class="rounded-circle me-3" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $review->customer->name }}</h6>
                                            <div class="text-warning mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                                @endfor
                                                <span class="text-muted ms-2">{{ $review->rating }}/5</span>
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Service:</strong> {{ $review->booking->serviceRequest->title }}
                                        <span class="badge bg-primary ms-2">{{ $review->booking->serviceRequest->serviceCategory->name }}</span>
                                    </div>
                                    
                                    @if($review->comment)
                                        <div class="mb-3">
                                            <p class="mb-0">{{ $review->comment }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($review->provider_response)
                                        <div class="bg-light p-3 rounded">
                                            <strong>Your Response:</strong>
                                            <p class="mb-1 mt-2">{{ $review->provider_response }}</p>
                                            <small class="text-muted">Responded on {{ $review->provider_responded_at->format('M d, Y') }}</small>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 text-end">
                                    @if(!$review->provider_response)
                                        <button type="button" class="btn btn-primary btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#respondModal{{ $review->id }}">
                                            <i class="fas fa-reply"></i> Respond
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('provider.reviews.show', $review) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Response Modal -->
                    @if(!$review->provider_response)
                        <div class="modal fade" id="respondModal{{ $review->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('provider.reviews.respond', $review) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Respond to Review</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="response{{ $review->id }}" class="form-label">Your Response</label>
                                                <textarea name="response" 
                                                        id="response{{ $review->id }}" 
                                                        class="form-control" 
                                                        rows="4" 
                                                        placeholder="Thank you for your feedback..."
                                                        required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-reply"></i> Send Response
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $reviews->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No reviews yet</h5>
                    <p class="text-muted">Customer reviews will appear here after they rate your completed services.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@php
function number_to_words($number) {
    $words = ['one', 'two', 'three', 'four', 'five'];
    return $words[$number - 1] ?? '';
}
@endphp
@endsection
