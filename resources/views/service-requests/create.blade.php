@extends('layouts.app')

@section('title', 'Create Service Request')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Create New Service Request</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('service-requests.store') }}">
                        @csrf
                        
                        <!-- Service Category Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="service_category_id" class="form-label">Service Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('service_category_id') is-invalid @enderror" id="service_category_id" name="service_category_id" required>
                                    <option value="">Select a service category</option>
                                    @foreach($serviceCategories as $category)
                                        <option value="{{ $category->id }}" {{ old('service_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="provider_id" class="form-label">Select Provider <span class="text-danger">*</span></label>
                                <select class="form-select @error('provider_id') is-invalid @enderror" id="provider_id" name="provider_id" required>
                                    <option value="">Choose a provider</option>
                                    @foreach($providers as $provider)
                                        <option value="{{ $provider->id }}" 
                                                data-rating="{{ $provider->rating }}" 
                                                data-rate="{{ $provider->hourly_rate }}"
                                                {{ old('provider_id') == $provider->id ? 'selected' : '' }}>
                                            {{ $provider->name }} - Rs. {{ number_format($provider->hourly_rate, 2) }}/hr
                                        </option>
                                    @endforeach
                                </select>
                                @error('provider_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Provider Info Display -->
                        <div id="provider-info" class="row mb-4" style="display: none;">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="mb-1" id="provider-name"></h6>
                                                <div class="text-warning mb-2" id="provider-rating"></div>
                                                <p class="mb-0 text-muted">Hourly Rate: <span class="font-weight-bold" id="provider-rate"></span></p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="viewProviderProfile()">
                                                    View Profile
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">Service Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Please describe the service you need in detail..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Be specific about what you need to help the provider understand your requirements.</div>
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <label for="address" class="form-label">Service Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="2" 
                                      placeholder="Enter the complete address where the service is needed..."
                                      required>{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Preferred Date and Duration -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="preferred_date" class="form-label">Preferred Date & Time</label>
                                <input type="datetime-local" 
                                       class="form-control @error('preferred_date') is-invalid @enderror" 
                                       id="preferred_date" 
                                       name="preferred_date" 
                                       value="{{ old('preferred_date') }}"
                                       min="{{ now()->format('Y-m-d\TH:i') }}">
                                @error('preferred_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Optional: When would you prefer the service to be done?</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="estimated_duration" class="form-label">Estimated Duration (minutes)</label>
                                <select class="form-select @error('estimated_duration') is-invalid @enderror" 
                                        id="estimated_duration" 
                                        name="estimated_duration">
                                    <option value="">Select duration</option>
                                    <option value="30" {{ old('estimated_duration') == '30' ? 'selected' : '' }}>30 minutes</option>
                                    <option value="60" {{ old('estimated_duration') == '60' ? 'selected' : '' }}>1 hour</option>
                                    <option value="90" {{ old('estimated_duration') == '90' ? 'selected' : '' }}>1.5 hours</option>
                                    <option value="120" {{ old('estimated_duration') == '120' ? 'selected' : '' }}>2 hours</option>
                                    <option value="180" {{ old('estimated_duration') == '180' ? 'selected' : '' }}>3 hours</option>
                                    <option value="240" {{ old('estimated_duration') == '240' ? 'selected' : '' }}>4 hours</option>
                                    <option value="480" {{ old('estimated_duration') == '480' ? 'selected' : '' }}>8 hours (Full day)</option>
                                </select>
                                @error('estimated_duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">This helps calculate estimated cost</div>
                            </div>
                        </div>

                        <!-- Estimated Cost Display -->
                        <div id="cost-estimate" class="mb-4" style="display: none;">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h6 class="card-title text-success">
                                        <i class="fas fa-calculator me-2"></i>
                                        Estimated Cost
                                    </h6>
                                    <p class="card-text mb-0">
                                        <span class="h4 text-success" id="estimated-cost">Rs. 0.00</span>
                                        <small class="text-muted">
                                            (<span id="duration-display"></span> × Rs. <span id="rate-display"></span>/hr)
                                        </small>
                                    </p>
                                    <small class="text-muted">This is an estimate. Final cost may vary based on actual work done.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('service-requests.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Requests
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>
                                Send Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const providerSelect = document.getElementById('provider_id');
    const durationSelect = document.getElementById('estimated_duration');
    const providerInfo = document.getElementById('provider-info');
    const costEstimate = document.getElementById('cost-estimate');
    
    // Provider selection handler
    providerSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const rating = parseFloat(selectedOption.dataset.rating) || 0;
            const rate = parseFloat(selectedOption.dataset.rate) || 0;
            
            // Show provider info
            document.getElementById('provider-name').textContent = selectedOption.text.split(' - ')[0];
            document.getElementById('provider-rate').textContent = 'Rs. ' + rate.toFixed(2) + '/hr';
            
            // Show rating stars
            let ratingHtml = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    ratingHtml += '<i class="fas fa-star"></i>';
                } else {
                    ratingHtml += '<i class="far fa-star"></i>';
                }
            }
            ratingHtml += ' (' + rating.toFixed(1) + '/5)';
            document.getElementById('provider-rating').innerHTML = ratingHtml;
            
            providerInfo.style.display = 'block';
            
            // Update cost estimate if duration is selected
            updateCostEstimate();
        } else {
            providerInfo.style.display = 'none';
            costEstimate.style.display = 'none';
        }
    });
    
    // Duration selection handler
    durationSelect.addEventListener('change', function() {
        updateCostEstimate();
    });
    
    function updateCostEstimate() {
        const providerSelect = document.getElementById('provider_id');
        const durationSelect = document.getElementById('estimated_duration');
        
        if (providerSelect.value && durationSelect.value) {
            const selectedOption = providerSelect.options[providerSelect.selectedIndex];
            const rate = parseFloat(selectedOption.dataset.rate) || 0;
            const duration = parseInt(durationSelect.value) || 0;
            
            const hours = duration / 60;
            const cost = hours * rate;
            
            document.getElementById('estimated-cost').textContent = 'Rs. ' + cost.toFixed(2);
            document.getElementById('duration-display').textContent = hours + ' hour' + (hours !== 1 ? 's' : '');
            document.getElementById('rate-display').textContent = rate.toFixed(2);
            
            costEstimate.style.display = 'block';
        } else {
            costEstimate.style.display = 'none';
        }
    }
    
    // Trigger initial update if values are pre-selected
    if (providerSelect.value) {
        providerSelect.dispatchEvent(new Event('change'));
    }
});

function viewProviderProfile() {
    // This would open a modal or navigate to provider profile
    alert('Provider profile feature will be implemented');
}
</script>
@endpush
@endsection
