<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'OHSS') }} - Online Household Services System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .hero-section {
            padding: 100px 0;
            color: white;
        }
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        .navbar {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }

        /* Testimonial Styles */
        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .testimonial-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }

        .testimonial-rating {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .testimonial-text {
            font-style: italic;
            line-height: 1.6;
            color: #555;
            font-size: 16px;
        }

        .testimonial-footer {
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .customer-avatar {
            position: relative;
        }

        .customer-avatar::after {
            content: '';
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 16px;
            height: 16px;
            background: #28a745;
            border: 2px solid white;
            border-radius: 50%;
        }
        .navbar-brand {
            font-weight: 700;
            color: white !important;
        }
        .nav-link {
            color: white !important;
            font-weight: 500;
        }
        .stats-section {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            margin: 50px 0;
        }
        .stat-item {
            text-align: center;
            color: white;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            display: block;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-home me-2"></i>
                {{ config('app.name') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-1"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link p-0 border-0">
                                    <i class="fas fa-sign-out-alt me-1"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>
                                Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>
                                Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Find Trusted Household Services
                    </h1>
                    <p class="lead mb-4">
                        Connect with verified professionals for all your household needs. From plumbing to cleaning, 
                        we've got you covered with reliable, rated service providers.
                    </p>
                    <div class="d-flex gap-3">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-custom btn-lg">
                                <i class="fas fa-rocket me-2"></i>
                                Get Started
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Sign In
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-custom btn-lg">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Go to Dashboard
                            </a>
                        @endguest
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-home fa-10x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <div class="container">
        <div class="stats-section">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span>Verified Providers</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">1000+</span>
                        <span>Happy Customers</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">5000+</span>
                        <span>Services Completed</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">4.8★</span>
                        <span>Average Rating</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-search fa-3x text-primary mb-3"></i>
                        <h4>Find Services</h4>
                        <p>Browse through our wide range of household services and find the perfect provider for your needs.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                        <h4>Verified Providers</h4>
                        <p>All our service providers are thoroughly vetted and verified to ensure quality and reliability.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-star fa-3x text-warning mb-3"></i>
                        <h4>Rated & Reviewed</h4>
                        <p>Read reviews from real customers and choose providers based on their ratings and feedback.</p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-calendar-check fa-3x text-info mb-3"></i>
                        <h4>Easy Booking</h4>
                        <p>Book services with just a few clicks and schedule them at your convenience.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-credit-card fa-3x text-danger mb-3"></i>
                        <h4>Secure Payments</h4>
                        <p>Pay securely through eSewa or choose cash on service completion. Your choice!</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-comments fa-3x text-secondary mb-3"></i>
                        <h4>Real-time Chat</h4>
                        <p>Communicate directly with service providers through our built-in chat system.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5" style="background: rgba(255,255,255,0.1);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="text-white fw-bold">Popular Services</h2>
                <p class="text-white-50">Choose from our wide range of household services</p>
            </div>
            
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-wrench fa-2x text-primary mb-3"></i>
                        <h6>Plumbing</h6>
                        <p class="small">Professional plumbing services</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-bolt fa-2x text-warning mb-3"></i>
                        <h6>Electrical</h6>
                        <p class="small">Electrical repairs and installation</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-broom fa-2x text-success mb-3"></i>
                        <h6>Cleaning</h6>
                        <p class="small">House cleaning and maintenance</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-hammer fa-2x text-info mb-3"></i>
                        <h6>Carpentry</h6>
                        <p class="small">Wood work and furniture repair</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Service Providers -->
    @if(isset($providers) && $providers->count() > 0)
    <section class="py-5" style="background: white;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="text-dark fw-bold">Available Service Providers</h2>
                <p class="text-muted">Connect with verified professionals in your area</p>
            </div>

            <div class="row">
                @foreach($providers as $provider)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feature-card">
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
                                <div>
                                    <h5 class="mb-1">{{ $provider->name }}</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="text-warning me-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= ($provider->reviews_avg_rating ?? 0) ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        <small class="text-muted">({{ $provider->reviews_count }} reviews)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                @foreach($provider->serviceCategories->take(3) as $category)
                                    <span class="badge bg-primary me-1 mb-1">{{ $category->name }}</span>
                                @endforeach
                            </div>

                            @if($provider->bio)
                                <p class="text-muted small mb-3">{{ Str::limit($provider->bio, 80) }}</p>
                            @endif

                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <div class="text-primary fw-bold">{{ $provider->experience_years ?? 0 }}</div>
                                    <div class="text-muted small">Years Exp.</div>
                                </div>
                                <div class="col-6">
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

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="text-success fw-bold">
                                    Rs. {{ number_format($provider->hourly_rate, 0) }}/hr
                                </div>
                                @php
                                    $availabilityStatus = $provider->getAvailabilityStatus();
                                @endphp
                                <span class="badge {{ $availabilityStatus['badge_class'] }}">
                                    {{ $availabilityStatus['status'] }}
                                </span>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('providers.profile', $provider) }}"
                                   class="btn btn-outline-primary btn-sm w-100">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('providers.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-users me-2"></i>
                    View All Providers
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- About Our Clients Section -->
    <section class="py-5" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-white mb-3">
                    <i class="fas fa-users me-2"></i>
                    About Our Clients
                </h2>
                <p class="lead text-white-50">
                    Trusted by thousands of satisfied customers across Nepal
                </p>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center text-white">
                        <div class="display-4 fw-bold text-warning mb-2">{{ number_format($stats['happy_customers']) }}+</div>
                        <h5 class="mb-2">Happy Customers</h5>
                        <p class="text-white-50">Customers who rated us 4+ stars</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center text-white">
                        <div class="display-4 fw-bold text-success mb-2">{{ number_format($stats['services_completed']) }}+</div>
                        <h5 class="mb-2">Services Completed</h5>
                        <p class="text-white-50">Successfully completed services</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center text-white">
                        <div class="display-4 fw-bold text-info mb-2">{{ number_format($stats['total_reviews']) }}+</div>
                        <h5 class="mb-2">Customer Reviews</h5>
                        <p class="text-white-50">Genuine reviews from real customers</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center text-white">
                        <div class="display-4 fw-bold text-warning mb-2">{{ number_format($stats['average_rating'], 1) }}</div>
                        <h5 class="mb-2">Average Rating</h5>
                        <div class="text-warning mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= round($stats['average_rating']) ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                        <p class="text-white-50">Out of 5 stars</p>
                    </div>
                </div>
            </div>

            <!-- Client Trust Indicators -->
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="d-flex align-items-center text-white">
                        <div class="bg-success rounded-circle p-3 me-3">
                            <i class="fas fa-shield-alt fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Verified Providers</h5>
                            <p class="text-white-50 mb-0">All providers are background checked and verified</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="d-flex align-items-center text-white">
                        <div class="bg-primary rounded-circle p-3 me-3">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">24/7 Support</h5>
                            <p class="text-white-50 mb-0">Round-the-clock customer support for your peace of mind</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="d-flex align-items-center text-white">
                        <div class="bg-warning rounded-circle p-3 me-3">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Fair Pricing</h5>
                            <p class="text-white-50 mb-0">Transparent pricing with no hidden charges</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Testimonials Section -->
    @if($testimonials->count() > 0)
    <section class="py-5" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-white mb-3">
                    <i class="fas fa-quote-left me-2"></i>
                    What Our Customers Say
                </h2>
                <p class="lead text-white-50">
                    Real experiences from our satisfied customers
                </p>
            </div>

            <div class="row">
                @foreach($testimonials as $testimonial)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="testimonial-card h-100">
                            <div class="testimonial-content">
                                <!-- Rating Stars -->
                                <div class="text-center mb-3">
                                    <div class="testimonial-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    @if($testimonial->rating == 5)
                                        <div class="badge bg-success mt-2">
                                            <i class="fas fa-crown me-1"></i>5-Star Review
                                        </div>
                                    @endif
                                </div>

                                <!-- Comment -->
                                <div class="testimonial-text mb-4">
                                    <i class="fas fa-quote-left text-primary me-2"></i>
                                    <span class="text-dark">{{ $testimonial->short_comment ?? $testimonial->comment }}</span>
                                    <i class="fas fa-quote-right text-primary ms-2"></i>
                                </div>

                                <!-- Customer Info -->
                                <div class="testimonial-footer">
                                    <div class="d-flex align-items-center">
                                        <div class="customer-avatar me-3">
                                            @if($testimonial->customer && $testimonial->customer->profile_image)
                                                <img src="{{ Storage::url($testimonial->customer->profile_image) }}"
                                                     alt="{{ $testimonial->customer_name }}"
                                                     class="rounded-circle"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                                     style="width: 50px; height: 50px; font-size: 18px;">
                                                    {{ $testimonial->customer_initials }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-dark">{{ $testimonial->customer_name }}</div>
                                            <div class="small text-muted">
                                                <i class="fas fa-tools me-1"></i>
                                                {{ $testimonial->service_category }}
                                            </div>
                                            <div class="small text-muted">
                                                <i class="fas fa-user-tie me-1"></i>
                                                Service by {{ $testimonial->provider_name }}
                                            </div>
                                        </div>
                                        @if($testimonial->is_recent)
                                            <div class="badge bg-success">New</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Service Date -->
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $testimonial->time_ago }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- View All Reviews Button -->
            <div class="text-center mt-4">
                <a href="{{ route('providers.index') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-star me-2"></i>
                    View All Reviews
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center text-white">
                <h2 class="fw-bold mb-4">Ready to Get Started?</h2>
                <p class="lead mb-4">Join thousands of satisfied customers who trust our platform for their household needs.</p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-custom btn-lg me-3">
                        <i class="fas fa-user-plus me-2"></i>
                        Sign Up Now
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Already have an account?
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-custom btn-lg">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Go to Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4" style="background: rgba(0,0,0,0.2);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-white mb-0">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="text-white-50 mb-0">
                        <!-- Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}) -->
                        Laravel OHSS Project
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
