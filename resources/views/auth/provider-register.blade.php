<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Provider Registration - {{ config('app.name', 'OHSS') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #e3e6f0;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            position: relative;
        }
        .step.active {
            background: #667eea;
            color: white;
        }
        .step.completed {
            background: #28a745;
            color: white;
        }
        .step::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            width: 20px;
            height: 2px;
            background: #e9ecef;
            transform: translateY(-50%);
        }
        .step:last-child::after {
            display: none;
        }
        .step.completed::after {
            background: #28a745;
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="text-primary">Become a Service Provider</h2>
                            <p class="text-muted">Join our platform and start earning by providing household services</p>
                        </div>

                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            <div class="step active" data-step="1">1</div>
                            <div class="step" data-step="2">2</div>
                            <div class="step" data-step="3">3</div>
                            <div class="step" data-step="4">4</div>
                        </div>

                        <form method="POST" action="{{ route('provider.register') }}" enctype="multipart/form-data" id="providerRegistrationForm">
                            @csrf

                            <!-- Step 1: Basic Information -->
                            <div class="form-step active" data-step="1">
                                <h5 class="mb-4">Basic Information</h5>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" name="password_confirmation" required>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="experience_years" class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                        <select class="form-select @error('experience_years') is-invalid @enderror" 
                                                id="experience_years" name="experience_years" required>
                                            <option value="">Select experience</option>
                                            @for($i = 0; $i <= 20; $i++)
                                                <option value="{{ $i }}" {{ old('experience_years') == $i ? 'selected' : '' }}>
                                                    {{ $i }} {{ $i == 1 ? 'year' : 'years' }}
                                                </option>
                                            @endfor
                                            <option value="20+" {{ old('experience_years') == '20+' ? 'selected' : '' }}>20+ years</option>
                                        </select>
                                        @error('experience_years')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Step 2: Service Information -->
                            <div class="form-step" data-step="2">
                                <h5 class="mb-4">Service Information</h5>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="hourly_rate" class="form-label">Hourly Rate (Rs.) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                               id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}" 
                                               min="100" max="10000" step="50" required>
                                        @error('hourly_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Set your hourly rate between Rs. 100 - Rs. 10,000</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Services You Provide <span class="text-danger">*</span></label>
                                    <div class="row">
                                        @foreach($serviceCategories as $category)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="service_categories[]" value="{{ $category->id }}" 
                                                           id="category_{{ $category->id }}"
                                                           {{ in_array($category->id, old('service_categories', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="category_{{ $category->id }}">
                                                        <i class="{{ $category->icon }} me-2"></i>
                                                        {{ $category->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('service_categories')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="bio" class="form-label">Bio/Description</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror" 
                                              id="bio" name="bio" rows="4" 
                                              placeholder="Tell customers about yourself, your experience, and what makes you special...">{{ old('bio') }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Step 3: Profile & Documents -->
                            <div class="form-step" data-step="3">
                                <h5 class="mb-4">Profile & Documents</h5>
                                <div class="mb-3">
                                    <label for="profile_image" class="form-label">Profile Image</label>
                                    <input type="file" class="form-control @error('profile_image') is-invalid @enderror" 
                                           id="profile_image" name="profile_image" accept="image/*">
                                    @error('profile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Upload a professional photo (JPEG, PNG, max 2MB)</div>
                                </div>

                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>KYC Documents</h6>
                                    <p class="mb-0">After registration, you'll be able to upload your KYC documents (citizenship, license, etc.) from your dashboard. These documents are required for account verification.</p>
                                </div>
                            </div>

                            <!-- Step 4: Review & Submit -->
                            <div class="form-step" data-step="4">
                                <h5 class="mb-4">Review & Submit</h5>
                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Information</h6>
                                    <ul class="mb-0">
                                        <li>Your account will be reviewed by our admin team</li>
                                        <li>You'll receive an email notification once approved</li>
                                        <li>Make sure all information is accurate</li>
                                        <li>You can update your profile after approval</li>
                                    </ul>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="terms_accepted" required>
                                    <label class="form-check-label" for="terms_accepted">
                                        I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                                    <i class="fas fa-arrow-left me-2"></i>Previous
                                </button>
                                <div class="ms-auto">
                                    <button type="button" class="btn btn-primary" id="nextBtn">
                                        Next<i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                    <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                        <i class="fas fa-user-plus me-2"></i>Register as Provider
                                    </button>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('login') }}" class="text-muted">Already have an account? Sign in</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            const totalSteps = 4;
            
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const submitBtn = document.getElementById('submitBtn');
            
            function showStep(step) {
                // Hide all steps
                document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
                document.querySelectorAll('.step').forEach(s => s.classList.remove('active', 'completed'));
                
                // Show current step
                document.querySelector(`[data-step="${step}"]`).classList.add('active');
                
                // Update step indicators
                for (let i = 1; i <= totalSteps; i++) {
                    const stepEl = document.querySelector(`.step[data-step="${i}"]`);
                    if (i < step) {
                        stepEl.classList.add('completed');
                    } else if (i === step) {
                        stepEl.classList.add('active');
                    }
                }
                
                // Update buttons
                prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
                nextBtn.style.display = step === totalSteps ? 'none' : 'inline-block';
                submitBtn.style.display = step === totalSteps ? 'inline-block' : 'none';
            }
            
            function validateStep(step) {
                const currentStepEl = document.querySelector(`[data-step="${step}"]`);
                const requiredFields = currentStepEl.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                // Special validation for step 2 (service categories)
                if (step === 2) {
                    const checkedCategories = document.querySelectorAll('input[name="service_categories[]"]:checked');
                    if (checkedCategories.length === 0) {
                        alert('Please select at least one service category.');
                        isValid = false;
                    }
                }
                
                return isValid;
            }
            
            nextBtn.addEventListener('click', function() {
                if (validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
            
            prevBtn.addEventListener('click', function() {
                currentStep--;
                showStep(currentStep);
            });
            
            // Initialize
            showStep(currentStep);
        });
    </script>
</body>
</html>
