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
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
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
        .bg-gradient-primary {
            background: linear-gradient(87deg, #007bff 0, #0056b3 100%) !important;
        }
        .border-left-primary {
            border-left: 0.25rem solid #007bff !important;
        }
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        .text-primary {
            color: #007bff !important;
        }
        .text-warning {
            color: #f6c23e !important;
        }
        .text-success {
            color: #1cc88a !important;
        }
        .text-info {
            color: #36b9cc !important;
        }
        .text-gray-800 {
            color: #5a5c69 !important;
        }
        .text-gray-300 {
            color: #dddfeb !important;
        }
        .badge {
            font-size: 0.75rem;
        }
        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
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

                <!-- Changes Summary -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-check-circle me-2"></i>Changes Successfully Implemented
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-success"><i class="fas fa-check me-2"></i>Sidebar Updates</h6>
                                        <ul class="list-unstyled ms-3">
                                            <li>✅ Background changed from green to blue gradient</li>
                                            <li>✅ Availability option removed from sidebar</li>
                                            <li>✅ Profile Settings properly linked</li>
                                            <li>✅ KYC Documents section added</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-success"><i class="fas fa-check me-2"></i>KYC Documents Feature</h6>
                                        <ul class="list-unstyled ms-3">
                                            <li>✅ KYC index view created</li>
                                            <li>✅ KYC upload form created</li>
                                            <li>✅ KYC document details view created</li>
                                            <li>✅ Controller updated for proper functionality</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="alert alert-info mt-3">
                                    <strong>Note:</strong> The KYC Documents section now allows providers to:
                                    <ul class="mb-0 mt-2">
                                        <li>Upload citizenship certificates, passports, driving licenses, and voter ID cards</li>
                                        <li>View status of uploaded documents (Pending, Approved, Rejected)</li>
                                        <li>Download and preview uploaded documents</li>
                                        <li>Delete non-approved documents and re-upload if needed</li>
                                    </ul>
                                </div>
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
