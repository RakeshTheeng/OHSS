<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'OHSS') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #e3e6f0;
            padding: 12px 15px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .input-group-text {
            border-radius: 10px 0 0 10px;
            background-color: #f8f9fc;
            border-color: #e3e6f0;
        }
        .form-control.border-start-0 {
            border-radius: 0 10px 10px 0;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">

            <!-- Logo -->
            <div class="text-center mb-4">
                <div class="mb-3">
                    <i class="fas fa-home fa-3x" style="color: #667eea;"></i>
                </div>
                <h3 class="fw-bold text-dark mb-2">Welcome Back</h3>
                <p class="text-muted">Sign in to your account</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Success Messages -->
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Info Messages -->
            @if (session('info'))
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if (session('error'))
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="form-label text-muted">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-envelope text-muted"></i>
                        </span>
                        <input id="email" type="email"
                               class="form-control border-start-0 @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}"
                               placeholder="Enter your email"
                               required autocomplete="email" autofocus>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-4">
                    <label for="password" class="form-label text-muted">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input id="password" type="password"
                               class="form-control border-start-0 @error('password') is-invalid @enderror"
                               name="password"
                               placeholder="Enter your password"
                               required autocomplete="current-password">
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted" for="remember">
                            Remember me
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: #667eea;">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>LOG IN
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center">
                    <p class="text-muted mb-0">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: #667eea;">
                            Create Account
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
