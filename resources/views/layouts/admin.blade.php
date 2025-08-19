<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'OHSS') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        html, body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
            margin: 0;
            padding: 0;
            width: 100vw;
            height: 100vh;
            overflow-x: hidden;
            box-sizing: border-box;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }
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
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
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
        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 1rem;
        }
        .sidebar-brand {
            padding: 1.5rem;
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .brand-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .brand-icon:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        .brand-icon i {
            font-size: 1.5rem;
            color: #fff;
        }
        .brand-name {
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .brand-subtitle {
            font-size: 0.75rem;
            opacity: 0.8;
            font-weight: 400;
            letter-spacing: 0.5px;
        }
        .sidebar-divider {
            border-color: rgba(255, 255, 255, 0.15);
            margin: 1rem 1.5rem;
        }
        .topbar {
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        .border-left-danger {
            border-left: 0.25rem solid #e74a3b !important;
        }
        .border-left-secondary {
            border-left: 0.25rem solid #858796 !important;
        }
        .border-left-dark {
            border-left: 0.25rem solid #5a5c69 !important;
        }
        .text-gray-800 {
            color: #5a5c69 !important;
        }
        .text-gray-300 {
            color: #dddfeb !important;
        }
        .dropdown-toggle::after {
            display: none;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74a3b;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .main-content {
            margin-left: 250px;
            width: calc(100vw - 250px);
            min-height: 100vh;
            max-height: 100vh;
            background-color: #f8f9fc;
            transition: all 0.3s ease;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 0;
        }
        .top-navbar {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 0;
            margin: 0;
            margin-bottom: 1rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            width: 100%;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 0 1.5rem;
            margin: 0;
            box-sizing: border-box;
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

        /* Remove gaps and ensure full width usage */
        .main-content > * {
            width: 100%;
            max-width: 100%;
        }

        .main-content .container,
        .main-content .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
            margin: 0 !important;
        }

        /* Ensure content fills available space */
        .main-content main {
            width: 100%;
            padding: 0 1.5rem;
            margin: 0;
        }

        /* Remove any default margins/padding that create gaps */
        .main-content .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100%;
        }

        .main-content .col,
        .main-content [class*="col-"] {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        /* Ensure tables and cards use full width */
        .main-content .table-responsive,
        .main-content .card,
        .main-content .chart-container {
            width: 100%;
            max-width: 100%;
        }

        /* Remove all gaps and ensure full width usage */
        .main-content {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .main-content > div,
        .main-content > section,
        .main-content > main {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Ensure Bootstrap grid system doesn't create gaps */
        .main-content .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Remove any default Bootstrap margins */
        .main-content .row {
            --bs-gutter-x: 1.5rem;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        /* Ensure content sections use full available width */
        .main-content .d-sm-flex,
        .main-content .mb-4,
        .main-content .analytics-cards,
        .main-content .analytics-section,
        .main-content .row {
            width: 100% !important;
            max-width: 100% !important;
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            box-sizing: border-box !important;
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
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
            <div class="sidebar-brand">
                <div class="d-flex flex-column align-items-center text-center">
                    <div class="brand-icon mb-2">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="brand-text">
                        <div class="brand-name">OHSS</div>
                        <div class="brand-subtitle">Admin Panel</div>
                    </div>
                </div>
            </div>
            
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                       href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i>
                        User Management
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.service-categories.*') ? 'active' : '' }}"
                       href="{{ route('admin.service-categories.index') }}">
                        <i class="fas fa-tools"></i>
                        Service Categories
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.service-requests.*') ? 'active' : '' }}"
                       href="{{ route('admin.service-requests.index') }}">
                        <i class="fas fa-clipboard-list"></i>
                        Service Requests
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}"
                       href="{{ route('admin.bookings.index') }}">
                        <i class="fas fa-calendar-check"></i>
                        Bookings
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
                       href="{{ route('admin.reviews.index') }}">
                        <i class="fas fa-star"></i>
                        Reviews & Ratings
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"
                       href="{{ route('admin.payments.index') }}">
                        <i class="fas fa-credit-card"></i>
                        Payments
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}"
                       href="{{ route('admin.analytics.index') }}">
                        <i class="fas fa-chart-bar"></i>
                        Analytics
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                       href="{{ route('admin.settings.index') }}">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                </li>

                <hr class="sidebar-divider my-3">

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        View Website
                    </a>
                </li>

                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-start w-100 border-0">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
            <!-- Top Navigation -->
            <div class="top-navbar">
                <div class="admin-header">
                    <h1 class="admin-title">@yield('title', 'Dashboard')</h1>
                    <div class="admin-user-dropdown">
                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2"></i>
                                System Administrator
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
            <nav style="display: none;"
                <div class="container-fluid">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Nav Item - Alerts Dropdown -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                 aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="me-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-user-plus text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">New provider registration!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                                @if(auth()->user()->profile_image)
                                    <img class="img-profile rounded-circle" width="32" height="32"
                                         src="{{ Storage::url(auth()->user()->profile_image) }}" alt="Profile">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 32px; height: 32px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                 aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                                    <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="main-content p-4">
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

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar toggle functionality
        document.getElementById('sidebarToggleTop')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('d-none');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
