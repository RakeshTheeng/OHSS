@extends('layouts.admin')

@section('title', 'Payments Management')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-credit-card"></i> Payments Management
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
                                Total Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_payments'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_payments'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Completed
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_payments'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Failed
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['failed_payments'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
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
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($stats['total_revenue'], 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                Total Refunds
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($stats['total_refunds'], 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-undo fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Method Statistics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                eSewa Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['esewa_payments'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-mobile-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Cash Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['cash_payments'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
            <form method="GET" action="{{ route('admin.payments.index') }}">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Transaction ID, customer, provider...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select class="form-control" id="payment_method" name="payment_method">
                                <option value="">All Methods</option>
                                <option value="esewa" {{ request('payment_method') === 'esewa' ? 'selected' : '' }}>eSewa</option>
                                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="amount_from">Min Amount</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="amount_from" 
                                   name="amount_from" 
                                   value="{{ request('amount_from') }}" 
                                   placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="amount_to">Max Amount</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="amount_to" 
                                   name="amount_to" 
                                   value="{{ request('amount_to') }}" 
                                   placeholder="10000">
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="category">Service Category</label>
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

    <!-- Payments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Payments</h6>
        </div>
        <div class="card-body">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Transaction ID</th>
                                <th>Customer</th>
                                <th>Provider</th>
                                <th>Service</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>
                                        @if($payment->transaction_id)
                                            <code>{{ $payment->transaction_id }}</code>
                                        @elseif($payment->esewa_ref_id)
                                            <code>{{ $payment->esewa_ref_id }}</code>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($payment->customer->profile_image)
                                                <img src="{{ Storage::url($payment->customer->profile_image) }}" 
                                                     alt="{{ $payment->customer->name }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 30px; height: 30px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 30px; height: 30px;">
                                                    <i class="fas fa-user text-white fa-sm"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-bold">{{ $payment->customer->name }}</div>
                                                <small class="text-muted">{{ $payment->customer->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($payment->provider->profile_image)
                                                <img src="{{ Storage::url($payment->provider->profile_image) }}" 
                                                     alt="{{ $payment->provider->name }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 30px; height: 30px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 30px; height: 30px;">
                                                    <i class="fas fa-user text-white fa-sm"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-bold">{{ $payment->provider->name }}</div>
                                                <small class="text-muted">{{ $payment->provider->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $payment->booking->serviceRequest->title }}</strong><br>
                                            <span class="badge badge-info">{{ $payment->booking->serviceRequest->serviceCategory->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-success">Rs. {{ number_format($payment->amount, 2) }}</strong>
                                        @if($payment->refund_amount > 0)
                                            <br><small class="text-danger">Refund: Rs. {{ number_format($payment->refund_amount, 2) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->payment_method === 'esewa')
                                            <span class="badge badge-info">
                                                <i class="fas fa-mobile-alt"></i> eSewa
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-money-bill-wave"></i> Cash
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'danger' : ($payment->status === 'refunded' ? 'secondary' : 'warning')) }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $payment->created_at->format('M d, Y') }}</strong><br>
                                            <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.payments.show', $payment) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.bookings.show', $payment->booking) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="View Booking">
                                            <i class="fas fa-calendar-check"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} results
                    </div>
                    <div>
                        {{ $payments->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-credit-card fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No payments found</h5>
                    <p class="text-muted">No payments match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
