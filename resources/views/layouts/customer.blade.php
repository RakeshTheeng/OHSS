<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer Panel') - {{ config('app.name', 'OHSS') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            border-radius: 0.35rem;
            margin: 0.25rem 1rem;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
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
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-brand">
                <i class="fas fa-user me-2"></i>
                Customer Panel
            </div>
            
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" 
                       href="{{ route('customer.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.providers.*') ? 'active' : '' }}"
                       href="{{ route('customer.providers.index') }}">
                        <i class="fas fa-search"></i>
                        Find Services
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.service-requests.*') ? 'active' : '' }}" 
                       href="{{ route('customer.service-requests.index') }}">
                        <i class="fas fa-clipboard-list"></i>
                        My Requests
                        @if(isset($pending_requests_count) && $pending_requests_count > 0)
                            <span class="notification-badge">{{ $pending_requests_count }}</span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.bookings.*') ? 'active' : '' }}" 
                       href="{{ route('customer.bookings.index') }}">
                        <i class="fas fa-calendar-check"></i>
                        My Bookings
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.reviews.*') ? 'active' : '' }}" 
                       href="{{ route('customer.reviews.index') }}">
                        <i class="fas fa-star"></i>
                        My Reviews
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.payments.*') ? 'active' : '' }}" 
                       href="{{ route('customer.payments.index') }}">
                        <i class="fas fa-credit-card"></i>
                        Payment History
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.chat.*') ? 'active' : '' }}" 
                       href="{{ route('customer.chat.index') }}">
                        <i class="fas fa-comments"></i>
                        Messages
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.favorites.*') ? 'active' : '' }}" 
                       href="{{ route('customer.favorites.index') }}">
                        <i class="fas fa-heart"></i>
                        Favorite Providers
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.profile.*') ? 'active' : '' }}" 
                       href="{{ route('customer.profile.edit') }}">
                        <i class="fas fa-user-edit"></i>
                        Profile Settings
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
        <div class="flex-grow-1">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand topbar mb-4 static-top">
                <div class="container-fluid">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search"
                          action="{{ route('customer.providers.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" 
                                   placeholder="Search for services or providers..." name="search"
                                   aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

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
                                    Notifications
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="me-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-check text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">Your service request was accepted!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Notifications</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages Dropdown -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                 aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image me-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi! I'll be there at 2 PM for the plumbing service.</div>
                                        <div class="small text-gray-500">John Smith · 15m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
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
                                <a class="dropdown-item" href="{{ route('customer.profile.edit') }}">
                                    <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('customer.payments.index') }}">
                                    <i class="fas fa-credit-card fa-sm fa-fw me-2 text-gray-400"></i>
                                    Payment History
                                </a>
                                <a class="dropdown-item" href="{{ route('customer.favorites.index') }}">
                                    <i class="fas fa-heart fa-sm fa-fw me-2 text-gray-400"></i>
                                    Favorites
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
            <main class="p-4">
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
