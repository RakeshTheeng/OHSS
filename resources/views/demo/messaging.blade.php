<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging System Demo - OHSS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', sans-serif;
        }
        .demo-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
        }
        .dashboard-preview {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .dashboard-preview:hover {
            transform: translateY(-5px);
        }
        .sidebar-demo {
            background: linear-gradient(180deg, #28a745 0%, #20c997 100%);
            min-height: 400px;
        }
        .sidebar-demo-customer {
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            min-height: 400px;
        }
        .nav-link-demo {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.35rem;
            margin: 0.25rem 0.5rem;
            transition: all 0.3s;
        }
        .nav-link-demo:hover,
        .nav-link-demo.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .notification-badge {
            background: #dc3545;
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
            border-radius: 50px;
            margin-left: 0.5rem;
            min-width: 18px;
            text-align: center;
        }
        .chat-preview {
            max-height: 300px;
            overflow-y: auto;
        }
        .message-bubble {
            max-width: 80%;
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            margin-bottom: 0.5rem;
        }
        .own-message .message-bubble {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-left: auto;
        }
        .other-message .message-bubble {
            background: white;
            color: #333;
            border: 1px solid #e3e6f0;
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="demo-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">Seamless Messaging System</h1>
                    <p class="lead mb-4">
                        Experience smooth communication between customers and service providers with our 
                        real-time messaging system. Built for efficiency and user experience.
                    </p>
                    <div class="d-flex gap-3">
                        <button class="btn btn-light btn-lg" onclick="createSampleData()">
                            <i class="fas fa-database me-2"></i>
                            Create Sample Data
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Try Live Demo
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-comments fa-8x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Previews -->
    <div class="container my-5">
        <div class="row">
            <!-- Customer Dashboard Preview -->
            <div class="col-lg-6 mb-4">
                <div class="dashboard-preview bg-white">
                    <div class="d-flex">
                        <!-- Customer Sidebar -->
                        <div class="sidebar-demo-customer" style="width: 200px;">
                            <div class="p-3">
                                <div class="text-white fw-bold mb-3">
                                    <i class="fas fa-user me-2"></i>
                                    Customer Panel
                                </div>
                                <nav class="nav flex-column">
                                    <a class="nav-link-demo">
                                        <i class="fas fa-tachometer-alt me-2"></i>
                                        Dashboard
                                    </a>
                                    <a class="nav-link-demo">
                                        <i class="fas fa-search me-2"></i>
                                        Find Services
                                    </a>
                                    <a class="nav-link-demo">
                                        <i class="fas fa-list me-2"></i>
                                        My Requests
                                    </a>
                                    <a class="nav-link-demo active">
                                        <i class="fas fa-comments me-2"></i>
                                        Messages
                                        <span class="notification-badge">2</span>
                                    </a>
                                    <a class="nav-link-demo">
                                        <i class="fas fa-calendar me-2"></i>
                                        My Bookings
                                    </a>
                                </nav>
                            </div>
                        </div>
                        
                        <!-- Customer Chat Content -->
                        <div class="flex-grow-1 p-3">
                            <h6 class="fw-bold mb-3">Chat with Wangbu Theeng</h6>
                            <div class="chat-preview">
                                <div class="other-message">
                                    <div class="message-bubble">
                                        Hi! I can come tomorrow morning around 10 AM. Is that convenient?
                                    </div>
                                </div>
                                <div class="own-message">
                                    <div class="message-bubble">
                                        Perfect! 10 AM works great. Do you need me to prepare anything?
                                    </div>
                                </div>
                                <div class="other-message">
                                    <div class="message-bubble">
                                        Just make sure the area under the sink is clear. I'll bring all tools.
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Type your message...">
                                    <button class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <h5 class="fw-bold">Customer Dashboard</h5>
                    <p class="text-muted">Customers can easily chat with their service providers</p>
                </div>
            </div>

            <!-- Provider Dashboard Preview -->
            <div class="col-lg-6 mb-4">
                <div class="dashboard-preview bg-white">
                    <div class="d-flex">
                        <!-- Provider Sidebar -->
                        <div class="sidebar-demo" style="width: 200px;">
                            <div class="p-3">
                                <div class="text-white fw-bold mb-3">
                                    <i class="fas fa-user-cog me-2"></i>
                                    Provider Panel
                                </div>
                                <nav class="nav flex-column">
                                    <a class="nav-link-demo">
                                        <i class="fas fa-tachometer-alt me-2"></i>
                                        Dashboard
                                    </a>
                                    <a class="nav-link-demo">
                                        <i class="fas fa-clipboard-list me-2"></i>
                                        Service Requests
                                        <span class="notification-badge">1</span>
                                    </a>
                                    <a class="nav-link-demo">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        Bookings
                                    </a>
                                    <a class="nav-link-demo active">
                                        <i class="fas fa-comments me-2"></i>
                                        Messages
                                        <span class="notification-badge">3</span>
                                    </a>
                                    <a class="nav-link-demo">
                                        <i class="fas fa-star me-2"></i>
                                        Reviews
                                    </a>
                                </nav>
                            </div>
                        </div>
                        
                        <!-- Provider Chat Content -->
                        <div class="flex-grow-1 p-3">
                            <h6 class="fw-bold mb-3">Chat with Rajan Tamang</h6>
                            <div class="chat-preview">
                                <div class="other-message">
                                    <div class="message-bubble">
                                        Hello! I have a leaking kitchen sink that needs urgent repair.
                                    </div>
                                </div>
                                <div class="own-message">
                                    <div class="message-bubble">
                                        Hi! I can come tomorrow morning around 10 AM. Is that convenient?
                                    </div>
                                </div>
                                <div class="other-message">
                                    <div class="message-bubble">
                                        Perfect! 10 AM works great. Thank you!
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Type your message...">
                                    <button class="btn btn-success">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <h5 class="fw-bold">Provider Dashboard</h5>
                    <p class="text-muted">Providers can respond to customer inquiries instantly</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container my-5">
        <div class="row text-center">
            <div class="col-12 mb-5">
                <h2 class="fw-bold">Key Features</h2>
                <p class="text-muted">Everything you need for seamless communication</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h5 class="fw-bold">Real-time Messaging</h5>
                <p class="text-muted">Messages are delivered instantly with automatic polling for new messages every 3 seconds.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h5 class="fw-bold">Smart Notifications</h5>
                <p class="text-muted">Unread message badges and notifications keep users informed of new conversations.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h5 class="fw-bold">Secure & Private</h5>
                <p class="text-muted">Messages are tied to specific bookings and only accessible by involved parties.</p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function createSampleData() {
            const button = event.target;
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
            button.disabled = true;
            
            fetch('/demo/create-sample-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Sample data created successfully!\n\nYou can now login with:\nCustomer: ' + data.data.customer_email + '\nProvider: ' + data.data.provider_email + '\nPassword: password');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error creating sample data: ' + error.message);
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }
    </script>
</body>
</html>
