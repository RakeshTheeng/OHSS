@extends('layouts.app')

@section('title', 'Request Service')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-paper-plane me-2"></i>Request Service
                    </h1>
                    <p class="text-muted mb-0">Submit a service request to connect with providers</p>
                </div>
                <div>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Request Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('customer.service-requests.store') }}">
                        @csrf
                        
                        <!-- Service Category -->
                        <div class="mb-3">
                            <label for="service_category_id" class="form-label">Service Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('service_category_id') is-invalid @enderror" 
                                    id="service_category_id" name="service_category_id" required>
                                <option value="">Select a service category</option>
                                @foreach($serviceCategories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('service_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Provider Selection -->
                        <div class="mb-3">
                            <label for="provider_id" class="form-label">Preferred Provider (Optional)</label>
                            <select class="form-select @error('provider_id') is-invalid @enderror" 
                                    id="provider_id" name="provider_id">
                                <option value="">Any available provider</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id }}" 
                                            {{ (old('provider_id') ?? $selectedProvider?->id) == $provider->id ? 'selected' : '' }}>
                                        {{ $provider->name }} - Rs. {{ number_format($provider->hourly_rate ?? 0, 0) }}/hr
                                        @if($provider->reviews_avg_rating)
                                            ({{ number_format($provider->reviews_avg_rating, 1) }} ⭐)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('provider_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Leave blank to receive offers from multiple providers</div>
                        </div>

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Service Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="e.g., Kitchen deep cleaning" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required 
                                      placeholder="Describe what you need done in detail...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date and Time -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="preferred_date" class="form-label">Preferred Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('preferred_date') is-invalid @enderror" 
                                       id="preferred_date" name="preferred_date" value="{{ old('preferred_date') }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('preferred_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="preferred_time" class="form-label">Preferred Time <span class="text-danger">*</span></label>
                                <select class="form-select @error('preferred_time') is-invalid @enderror" 
                                        id="preferred_time" name="preferred_time" required>
                                    <option value="">Select time</option>
                                    <option value="morning" {{ old('preferred_time') == 'morning' ? 'selected' : '' }}>Morning (8 AM - 12 PM)</option>
                                    <option value="afternoon" {{ old('preferred_time') == 'afternoon' ? 'selected' : '' }}>Afternoon (12 PM - 5 PM)</option>
                                    <option value="evening" {{ old('preferred_time') == 'evening' ? 'selected' : '' }}>Evening (5 PM - 8 PM)</option>
                                    <option value="flexible" {{ old('preferred_time') == 'flexible' ? 'selected' : '' }}>Flexible</option>
                                </select>
                                @error('preferred_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Service Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" required 
                                      placeholder="Enter the complete address where service is needed">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Required Hours -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="required_hours" class="form-label">Required Hours <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('required_hours') is-invalid @enderror"
                                       id="required_hours" name="required_hours" value="{{ old('required_hours') }}"
                                       min="0.5" max="24" step="0.5" placeholder="e.g., 2" required>
                                @error('required_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Enter the estimated hours needed for this service</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="total_budget" class="form-label">Total Budget (Rs.)</label>
                                <input type="text" class="form-control" id="total_budget" readonly
                                       placeholder="Select provider and enter hours to calculate">
                                <div class="form-text">Automatically calculated: Hourly Rate × Required Hours</div>
                            </div>
                        </div>

                        <!-- Budget Calculation Display -->
                        <div id="budget-calculation" class="alert alert-info" style="display: none;">
                            <h6><i class="fas fa-calculator me-2"></i>Budget Calculation</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Provider Rate:</strong><br>
                                    <span id="display-hourly-rate">Rs. 0</span>/hr
                                </div>
                                <div class="col-md-4">
                                    <strong>Required Hours:</strong><br>
                                    <span id="display-hours">0</span> hours
                                </div>
                                <div class="col-md-4">
                                    <strong>Total Budget:</strong><br>
                                    <span id="display-total" class="text-success fw-bold">Rs. 0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Urgency -->
                        <div class="mb-3">
                            <label for="urgency" class="form-label">Urgency Level <span class="text-danger">*</span></label>
                            <select class="form-select @error('urgency') is-invalid @enderror" 
                                    id="urgency" name="urgency" required>
                                <option value="">Select urgency</option>
                                <option value="low" {{ old('urgency') == 'low' ? 'selected' : '' }}>Low - Can wait a week</option>
                                <option value="medium" {{ old('urgency') == 'medium' ? 'selected' : '' }}>Medium - Within 2-3 days</option>
                                <option value="high" {{ old('urgency') == 'high' ? 'selected' : '' }}>High - ASAP</option>
                            </select>
                            @error('urgency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-3">
                            <label for="additional_notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('additional_notes') is-invalid @enderror" 
                                      id="additional_notes" name="additional_notes" rows="3" 
                                      placeholder="Any special requirements or additional information...">{{ old('additional_notes') }}</textarea>
                            @error('additional_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Selected Provider Info -->
            @if($selectedProvider)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Selected Provider</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            @if($selectedProvider->profile_image)
                                <img src="{{ Storage::url($selectedProvider->profile_image) }}" 
                                     alt="{{ $selectedProvider->name }}" 
                                     class="rounded-circle me-3" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-1">{{ $selectedProvider->name }}</h6>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= ($selectedProvider->reviews_avg_rating ?? 0) ? '' : '-o' }}"></i>
                                    @endfor
                                    <span class="text-muted small ms-1">({{ $selectedProvider->reviews_count ?? 0 }})</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            @foreach($selectedProvider->serviceCategories->take(3) as $category)
                                <span class="badge bg-primary me-1 mb-1">{{ $category->name }}</span>
                            @endforeach
                        </div>
                        
                        <div class="text-success fw-bold mb-2">
                            Rs. {{ number_format($selectedProvider->hourly_rate ?? 0, 0) }}/hr
                        </div>
                        
                        <a href="{{ route('providers.profile', $selectedProvider) }}" 
                           class="btn btn-outline-primary btn-sm w-100" target="_blank">
                            <i class="fas fa-external-link-alt me-1"></i>View Full Profile
                        </a>
                    </div>
                </div>
            @endif

            <!-- Tips -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Tips for Better Results</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Be specific about your requirements
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Provide accurate address and timing
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Estimate required hours accurately
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Include any special requirements
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            Choose appropriate urgency level
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const providerSelect = document.getElementById('provider_id');
    const requiredHoursInput = document.getElementById('required_hours');
    const totalBudgetInput = document.getElementById('total_budget');
    const budgetCalculation = document.getElementById('budget-calculation');
    const displayHourlyRate = document.getElementById('display-hourly-rate');
    const displayHours = document.getElementById('display-hours');
    const displayTotal = document.getElementById('display-total');

    // Store provider rates for calculation
    const providerRates = {};

    // Populate provider rates from select options
    Array.from(providerSelect.options).forEach(option => {
        if (option.value) {
            // Extract hourly rate from option text (format: "Name - Rs. 500/hr")
            const text = option.textContent;
            const rateMatch = text.match(/Rs\.\s*(\d+(?:,\d+)*)/);
            if (rateMatch) {
                const rate = parseFloat(rateMatch[1].replace(/,/g, ''));
                providerRates[option.value] = rate;
            }
        }
    });

    function calculateBudget() {
        const providerId = providerSelect.value;
        const hours = parseFloat(requiredHoursInput.value) || 0;

        if (providerId && hours > 0 && providerRates[providerId]) {
            const hourlyRate = providerRates[providerId];
            const totalBudget = hourlyRate * hours;

            // Update display fields
            totalBudgetInput.value = `Rs. ${totalBudget.toLocaleString()}`;
            displayHourlyRate.textContent = `Rs. ${hourlyRate.toLocaleString()}`;
            displayHours.textContent = hours;
            displayTotal.textContent = `Rs. ${totalBudget.toLocaleString()}`;

            // Show calculation display
            budgetCalculation.style.display = 'block';
        } else {
            // Hide calculation display
            totalBudgetInput.value = '';
            budgetCalculation.style.display = 'none';
        }
    }

    // Event listeners
    providerSelect.addEventListener('change', calculateBudget);
    requiredHoursInput.addEventListener('input', calculateBudget);

    // Initial calculation if values are pre-selected
    calculateBudget();
});
</script>
@endpush

@endsection
