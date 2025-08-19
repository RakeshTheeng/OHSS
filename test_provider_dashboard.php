<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provider Dashboard - Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #007bff 0%, #0056b3 100%);
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
        .sidebar-brand {
            padding: 1.5rem;
            text-align: center;
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-brand">
                <i class="fas fa-user-cog me-2"></i>
                Provider Panel
            </div>
            
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-clipboard-list"></i>
                        Service Requests
                        <span class="badge bg-warning ms-2">3</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-calendar-check"></i>
                        Bookings
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-star"></i>
                        Reviews
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-dollar-sign"></i>
                        Earnings
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-comments"></i>
                        Messages
                        <span class="badge bg-info ms-2">2</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-user-edit"></i>
                        Profile Settings
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-file-alt"></i>
                        KYC Documents
                    </a>
                </li>
                
                <hr class="sidebar-divider my-3">
                
                <li class="nav-item">
                    <a class="nav-link" href="#" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        View Website
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <div class="container-fluid">
                <!-- Welcome Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-gradient-primary text-white">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h2 class="mb-0">Welcome back, Test Provider!</h2>
                                        <p class="mb-0">Here's what's happening with your services today.</p>
                                    </div>
                                    <div class="col-auto">
                                        <div class="text-center">
                                            <h3 class="mb-0">4.5/5</h3>
                                            <small>Average Rating</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Requests
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">15</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending Requests
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Completed Jobs
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-double fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Reviews
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-star fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KYC Documents Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-file-alt me-2"></i>Recent Activity
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="text-center text-muted">
                                    <strong>Changes Made:</strong><br>
                                    ✅ Sidebar background changed from green to blue<br>
                                    ✅ Availability option removed from sidebar<br>
                                    ✅ Profile Settings links to edit profile<br>
                                    ✅ KYC Documents section created with upload functionality<br>
                                    ✅ KYC views created for displaying uploaded documents
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
