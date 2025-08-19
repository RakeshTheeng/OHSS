@extends('layouts.admin')

@section('title', 'Reviews Management')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-star"></i> Reviews Management
        </h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Reviews
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_reviews'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved_reviews'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_reviews'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Flagged
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['flagged_reviews'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-flag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
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

        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                With Response
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['reviews_with_response'] }}</div>
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
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                5-Star Reviews
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['five_star_reviews'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                1-Star Reviews
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['one_star_reviews'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
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
            <form method="GET" action="{{ route('admin.reviews.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search by comment, customer, provider...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="rating">Rating</label>
                            <select class="form-control" id="rating" name="rating">
                                <option value="">All Ratings</option>
                                <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 Stars</option>
                                <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4 Stars</option>
                                <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3 Stars</option>
                                <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>2 Stars</option>
                                <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>1 Star</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="flagged" {{ request('status') === 'flagged' ? 'selected' : '' }}>Flagged</option>
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
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="date_from">From Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_from" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-1">
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

    <!-- Reviews Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Reviews</h6>
        </div>
        <div class="card-body">
            @if($reviews->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Provider</th>
                                <th>Service</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>{{ $review->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($review->customer->profile_image)
                                                <img src="{{ Storage::url($review->customer->profile_image) }}" 
                                                     alt="{{ $review->customer->name }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 30px; height: 30px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 30px; height: 30px;">
                                                    <i class="fas fa-user text-white fa-sm"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-bold">{{ $review->customer->name }}</div>
                                                <small class="text-muted">{{ $review->customer->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($review->provider->profile_image)
                                                <img src="{{ Storage::url($review->provider->profile_image) }}" 
                                                     alt="{{ $review->provider->name }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 30px; height: 30px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 30px; height: 30px;">
                                                    <i class="fas fa-user text-white fa-sm"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-bold">{{ $review->provider->name }}</div>
                                                <small class="text-muted">{{ $review->provider->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $review->booking->serviceRequest->title }}</strong><br>
                                            <span class="badge badge-info">{{ $review->booking->serviceRequest->serviceCategory->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                            @endfor
                                            <br><small class="text-muted">({{ $review->rating }}/5)</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($review->comment)
                                            <div style="max-width: 200px;">
                                                {{ Str::limit($review->comment, 100) }}
                                                @if(strlen($review->comment) > 100)
                                                    <br><small class="text-muted">...</small>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">No comment</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            @if($review->is_flagged)
                                                <span class="badge badge-danger">Flagged</span>
                                            @elseif($review->is_approved)
                                                <span class="badge badge-success">Approved</span>
                                            @else
                                                <span class="badge badge-warning">Pending</span>
                                            @endif
                                            
                                            @if($review->provider_response)
                                                <br><span class="badge badge-info mt-1">Has Response</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $review->created_at->format('M d, Y') }}</strong><br>
                                            <small class="text-muted">{{ $review->created_at->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.reviews.show', $review) }}" 
                                               class="btn btn-sm btn-primary" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if(!$review->is_approved && !$review->is_flagged)
                                                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success" 
                                                            title="Approve Review"
                                                            onclick="return confirm('Are you sure you want to approve this review?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if(!$review->is_flagged)
                                                <button type="button" 
                                                        class="btn btn-sm btn-warning" 
                                                        title="Flag Review"
                                                        data-toggle="modal" 
                                                        data-target="#flagModal{{ $review->id }}">
                                                    <i class="fas fa-flag"></i>
                                                </button>
                                            @endif
                                            
                                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger" 
                                                        title="Delete Review"
                                                        onclick="return confirm('Are you sure you want to delete this review? This action cannot be undone.')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Flag Modal -->
                                        <div class="modal fade" id="flagModal{{ $review->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('admin.reviews.flag', $review) }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Flag Review</h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="reason{{ $review->id }}">Reason for flagging:</label>
                                                                <textarea class="form-control" 
                                                                          id="reason{{ $review->id }}" 
                                                                          name="reason" 
                                                                          rows="3" 
                                                                          required 
                                                                          placeholder="Please provide a reason for flagging this review..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-warning">Flag Review</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} results
                    </div>
                    <div>
                        {{ $reviews->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-star fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No reviews found</h5>
                    <p class="text-muted">No reviews match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
