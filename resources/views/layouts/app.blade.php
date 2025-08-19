<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
            margin-left: 250px;
            transition: all 0.3s ease;
        }
        .main-content-guest {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.2);
        }
        .navbar-brand {
            font-weight: 600;
            color: #667eea !important;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .badge {
            border-radius: 20px;
        }
        .navbar-nav .nav-link.active {
            color: #667eea !important;
            font-weight: 600;
        }
        .top-navbar {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-title {
            margin: 0;
            color: #5a5c69;
            font-weight: 400;
        }
        .admin-user-dropdown {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .admin-user-dropdown .dropdown-toggle {
            background: none;
            border: none;
            color: #5a5c69;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.35rem;
            transition: all 0.3s ease;
        }
        .admin-user-dropdown .dropdown-toggle:hover {
            background-color: #f8f9fc;
        }
    </style>
</head>
<body class="font-sans antialiased">
    @auth
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="position-sticky pt-3">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <div class="brand-icon mb-3 mx-auto" style="width: 50px; height: 50px; background: rgba(255, 255, 255, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); transition: all 0.3s ease;">
                            <i class="fas fa-home" style="font-size: 1.5rem; color: #fff;"></i>
                        </div>
                        <h4 class="text-white mb-1">OHSS</h4>
                        <small class="text-white-50">{{ ucfirst(auth()->user()->role) }} Panel</small>
                    </div>

                    <ul class="nav flex-column">
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                    <i class="fas fa-users me-2"></i>
                                    User Management
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.service-categories.*') ? 'active' : '' }}" href="{{ route('admin.service-categories.index') }}">
                                    <i class="fas fa-tools me-2"></i>
                                    Service Categories
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.service-requests.*') ? 'active' : '' }}" href="{{ route('admin.service-requests.index') }}">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    Service Requests
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    Bookings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                                    <i class="fas fa-star me-2"></i>
                                    Reviews & Ratings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                                    <i class="fas fa-credit-card me-2"></i>
                                    Payments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}" href="{{ route('admin.analytics.index') }}">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Analytics
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                                    <i class="fas fa-cog me-2"></i>
                                    Settings
                                </a>
                            </li>

                            <hr class="text-white-50">

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}" target="_blank">
                                    <i class="fas fa-external-link-alt me-2"></i>
                                    View Website
                                </a>
                            </li>
                        @elseif(auth()->user()->isProvider())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('provider.dashboard') ? 'active' : '' }}" href="{{ route('provider.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('service-requests.*') ? 'active' : '' }}" href="{{ route('service-requests.index') }}">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    Service Requests
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    Bookings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fas fa-star me-2"></i>
                                    Reviews
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fas fa-search me-2"></i>
                                    Find Services
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('service-requests.*') ? 'active' : '' }}" href="{{ route('service-requests.index') }}">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    My Requests
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    My Bookings
                                </a>
                            </li>
                        @endif

                        <hr class="text-white-50">

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2"></i>
                                Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="nav-link" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Logout
                                </a>
                            </form>
                        </li>
                    </ul>
        </div>
    </nav>
    @endauth

    <!-- Main content -->
    <main class="@auth main-content @else main-content-guest @endauth px-4">
                @guest
                <!-- Public Navigation -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="{{ route('home') }}">
                            <i class="fas fa-home me-2 text-primary"></i>
                            {{ config('app.name') }}
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                        <i class="fas fa-home me-1"></i>
                                        Home
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('providers.*') ? 'active' : '' }}" href="{{ route('providers.index') }}">
                                        <i class="fas fa-users me-1"></i>
                                        Service Providers
                                    </a>
                                </li>
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
                            </ul>
                        </div>
                    </div>
                </nav>
                @endguest

                @auth
                <!-- Top navigation -->
                <div class="top-navbar">
                    <div class="admin-header">
                        <h1 class="admin-title">@yield('title', 'Dashboard')</h1>
                        <div class="admin-user-dropdown">
                            <div class="dropdown">
                                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-2"></i>
                                    {{ ucfirst(auth()->user()->role) }}
                                    <i class="fas fa-chevron-down ms-2"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user me-2"></i>Profile
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth

                <!-- Flash messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page content -->
                @yield('content')
                {{ $slot ?? '' }}
            </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    @stack('scripts')
</body>
</html>
