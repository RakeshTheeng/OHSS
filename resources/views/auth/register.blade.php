<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - {{ config('app.name', 'OHSS') }}</title>

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
        .role-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e3e6f0;
        }
        .role-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }
        .role-card.selected {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
        }
        .provider-fields {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="text-primary">Create Account</h2>
                            <p class="text-muted">Join our household services platform</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" id="registrationForm" enctype="multipart/form-data">
                            @csrf

                            <!-- Role Selection -->
                            <div class="mb-4">
                                <label class="form-label">I want to:</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="role-card p-3 text-center rounded" data-role="customer">
                                            <i class="fas fa-user fa-2x text-primary mb-2"></i>
                                            <h6>Find Services</h6>
                                            <p class="small text-muted mb-0">I need household services</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="role-card p-3 text-center rounded" data-role="provider">
                                            <i class="fas fa-tools fa-2x text-success mb-2"></i>
                                            <h6>Provide Services</h6>
                                            <p class="small text-muted mb-0">I offer household services</p>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="role" id="role" value="{{ old('role') }}">
                                @error('role')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Basic Information -->
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
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                           id="address" name="address" value="{{ old('address') }}">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Profile Image for all users -->
                            <div class="mb-3">
                                <label for="profile_image_general" class="form-label">Profile Image</label>
                                <input type="file" class="form-control @error('profile_image') is-invalid @enderror"
                                       id="profile_image_general" name="profile_image" accept="image/*">
                                @error('profile_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Upload your profile photo (Max: 2MB, JPG/PNG)</div>
                            </div>

                            <!-- Provider-specific fields -->
                            <div class="provider-fields">
                                <hr class="my-4">
                                <h6 class="text-success mb-3">Service Provider Information</h6>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="hourly_rate" class="form-label">Hourly Rate (Rs.) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('hourly_rate') is-invalid @enderror"
                                               id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}"
                                               min="100" max="10000" step="50">
                                        @error('hourly_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="experience_years" class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('experience_years') is-invalid @enderror"
                                               id="experience_years" name="experience_years" value="{{ old('experience_years') }}"
                                               min="0" max="50">
                                        @error('experience_years')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>



                                <!-- Bio/Description -->
                                <div class="mb-3">
                                    <label for="bio" class="form-label">About Yourself <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror"
                                              id="bio" name="bio" rows="4"
                                              placeholder="Describe your experience, skills, and what makes you a great service provider...">{{ old('bio') }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- KYC Document -->
                                <div class="mb-3">
                                    <label for="kyc_document" class="form-label">KYC Document (Citizenship/License) <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('kyc_document') is-invalid @enderror"
                                           id="kyc_document" name="kyc_document" accept=".pdf,.jpg,.jpeg,.png">
                                    @error('kyc_document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Upload citizenship certificate or professional license (Max: 5MB)</div>
                                </div>

                                <!-- Citizenship Number -->
                                <div class="mb-3">
                                    <label for="citizenship_number" class="form-label">Citizenship Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('citizenship_number') is-invalid @enderror"
                                           id="citizenship_number" name="citizenship_number" value="{{ old('citizenship_number') }}"
                                           placeholder="e.g., 12-34-56-78901">
                                    @error('citizenship_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Services You Provide <span class="text-danger">*</span></label>
                                    <p class="text-muted small">Select at least one service category you specialize in</p>
                                    <div class="row" id="service-categories">
                                        @foreach($serviceCategories as $category)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="service_categories[]" value="{{ $category->id }}"
                                                           id="category_{{ $category->id }}"
                                                           {{ in_array($category->id, old('service_categories', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="category_{{ $category->id }}">
                                                        <i class="{{ $category->icon }} me-1"></i>
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

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Note:</strong> Your account will be reviewed by our admin team before activation. You'll receive an email once approved.
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
                                <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                    Already have an account?
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Create Account
                                </button>
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
            const roleCards = document.querySelectorAll('.role-card');
            const roleInput = document.getElementById('role');
            const providerFields = document.querySelector('.provider-fields');
            const hourlyRateInput = document.getElementById('hourly_rate');
            const serviceCategoryCheckboxes = document.querySelectorAll('input[name="service_categories[]"]');

            // Role selection
            roleCards.forEach(card => {
                card.addEventListener('click', function() {
                    roleCards.forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    roleInput.value = this.dataset.role;

                    if (this.dataset.role === 'provider') {
                        providerFields.style.display = 'block';
                        hourlyRateInput.required = true;
                        // Service categories validation will be handled in form submit
                    } else {
                        providerFields.style.display = 'none';
                        hourlyRateInput.required = false;
                        // No service categories required for customers
                    }
                });
            });

            // Pre-select role if old value exists
            if (roleInput.value) {
                const selectedCard = document.querySelector(`[data-role="${roleInput.value}"]`);
                if (selectedCard) {
                    selectedCard.click();
                }
            }

            // Form validation
            document.getElementById('registrationForm').addEventListener('submit', function(e) {
                if (roleInput.value === 'provider') {
                    const checkedCategories = document.querySelectorAll('input[name="service_categories[]"]:checked');
                    if (checkedCategories.length === 0) {
                        e.preventDefault();
                        alert('Please select at least one service category you provide.');
                        return false;
                    }
                }
            });
        });
    </script>
</body>
</html>
