<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Provider\DashboardController as ProviderDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/providers', [HomeController::class, 'providers'])->name('providers.index');
Route::get('/providers/{provider}', [HomeController::class, 'providerProfile'])->name('providers.profile');

// Demo routes
Route::get('/demo/messaging', [App\Http\Controllers\DemoController::class, 'messaging'])->name('demo.messaging');
Route::post('/demo/create-sample-data', [App\Http\Controllers\DemoController::class, 'createSampleData'])->name('demo.create-sample-data');

// Role-based dashboard redirection
Route::get('/dashboard', function () {
    $user = auth()->user();

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'provider':
            // Check if provider is approved
            if ($user->provider_status === 'approved') {
                return redirect()->route('provider.dashboard');
            } else {
                return redirect()->route('provider.pending');
            }
        case 'customer':
            return redirect()->route('customer.dashboard');
        default:
            auth()->logout();
            return redirect()->route('login')->with('error', 'Invalid user role.');
    }
})->middleware(['auth'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User management routes will be added here
    // Service category management routes will be added here
    // Analytics routes will be added here
});

// Provider routes
Route::middleware(['auth', 'role:provider'])->prefix('provider')->name('provider.')->group(function () {
    Route::get('/dashboard', [ProviderDashboardController::class, 'index'])->name('dashboard');

    // Provider-specific routes will be added here
});

// Customer routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Service Requests
    Route::resource('service-requests', App\Http\Controllers\Customer\ServiceRequestController::class);
    Route::post('/service-requests/{serviceRequest}/cancel', [App\Http\Controllers\Customer\ServiceRequestController::class, 'cancel'])->name('service-requests.cancel');
});

// Shared authenticated routes
Route::middleware(['auth'])->group(function () {
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Service requests
    Route::resource('service-requests', ServiceRequestController::class);
    Route::post('/service-requests/{serviceRequest}/accept', [ServiceRequestController::class, 'accept'])->name('service-requests.accept');
    Route::post('/service-requests/{serviceRequest}/reject', [ServiceRequestController::class, 'reject'])->name('service-requests.reject');

    // Bookings
    Route::resource('bookings', BookingController::class)->except(['edit', 'update']);
    Route::get('/service-requests/{serviceRequest}/book', [BookingController::class, 'create'])->name('bookings.create-from-request');
    Route::post('/service-requests/{serviceRequest}/book', [BookingController::class, 'store'])->name('bookings.store-from-request');
    Route::post('/bookings/{booking}/start', [BookingController::class, 'start'])->name('bookings.start');
    Route::post('/bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Reviews
    Route::resource('reviews', ReviewController::class)->except(['edit', 'update', 'destroy']);
    Route::get('/bookings/{booking}/review', [ReviewController::class, 'create'])->name('reviews.create-from-booking');
    Route::post('/bookings/{booking}/review', [ReviewController::class, 'store'])->name('reviews.store-from-booking');
    Route::post('/reviews/{review}/respond', [ReviewController::class, 'respond'])->name('reviews.respond');
    Route::post('/reviews/{review}/flag', [ReviewController::class, 'flag'])->name('reviews.flag');
    Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve')->middleware('role:admin');
    Route::post('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject')->middleware('role:admin');
});

require __DIR__.'/auth.php';

// Provider Registration Routes
Route::get('/provider/register', [App\Http\Controllers\Auth\ProviderRegistrationController::class, 'create'])
    ->name('provider.register.form');
Route::post('/provider/register', [App\Http\Controllers\Auth\ProviderRegistrationController::class, 'store'])
    ->name('provider.register');

// Dashboard Routes
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->name('dashboard');

// Provider Pending Route
Route::get('/provider/pending', [App\Http\Controllers\Provider\PendingController::class, 'index'])
    ->name('provider.pending');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Provider Management
    Route::get('/providers/pending', [App\Http\Controllers\Admin\ProviderController::class, 'pending'])->name('providers.pending');
    Route::post('/providers/{provider}/approve', [App\Http\Controllers\Admin\ProviderController::class, 'approve'])->name('providers.approve');
    Route::post('/providers/{provider}/reject', [App\Http\Controllers\Admin\ProviderController::class, 'reject'])->name('providers.reject');
    Route::post('/providers/{provider}/toggle-availability', [App\Http\Controllers\Admin\ProviderController::class, 'toggleAvailability'])->name('providers.toggle-availability');
    Route::resource('providers', App\Http\Controllers\Admin\ProviderController::class)->except(['create', 'store', 'edit', 'update']);

    // User Management - Specific routes first
    Route::get('/users/customers', [App\Http\Controllers\Admin\UserController::class, 'customers'])->name('users.customers');
    Route::get('/users/providers', [App\Http\Controllers\Admin\UserController::class, 'providers'])->name('users.providers');
    Route::post('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/approve-provider', [App\Http\Controllers\Admin\UserController::class, 'approveProvider'])->name('users.approve-provider');
    Route::post('/users/{user}/reject-provider', [App\Http\Controllers\Admin\UserController::class, 'rejectProvider'])->name('users.reject-provider');
    Route::post('/users/{user}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Service Categories
    Route::resource('service-categories', App\Http\Controllers\Admin\ServiceCategoryController::class);
    Route::post('/service-categories/{serviceCategory}/toggle-status', [App\Http\Controllers\Admin\ServiceCategoryController::class, 'toggleStatus'])->name('service-categories.toggle-status');

    // Service Requests
    Route::resource('service-requests', App\Http\Controllers\Admin\ServiceRequestController::class)->only(['index', 'show']);

    // Bookings
    Route::resource('bookings', App\Http\Controllers\Admin\BookingController::class)->only(['index', 'show']);

    // Reviews
    Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'show', 'destroy']);
    Route::post('/reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/flag', [App\Http\Controllers\Admin\ReviewController::class, 'flag'])->name('reviews.flag');

    // Payments
    Route::resource('payments', App\Http\Controllers\Admin\PaymentController::class)->only(['index', 'show']);

    // Analytics
    Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/reports', [App\Http\Controllers\Admin\AnalyticsController::class, 'reports'])->name('analytics.reports');

    // Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
});

// Provider Routes
Route::middleware(['auth', 'role:provider'])->prefix('provider')->name('provider.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Provider\DashboardController::class, 'index'])->name('dashboard');

    // Service Requests
    Route::resource('service-requests', App\Http\Controllers\Provider\ServiceRequestController::class)->only(['index', 'show']);
    Route::post('/service-requests/{serviceRequest}/accept', [App\Http\Controllers\Provider\ServiceRequestController::class, 'accept'])->name('service-requests.accept');
    Route::post('/service-requests/{serviceRequest}/reject', [App\Http\Controllers\Provider\ServiceRequestController::class, 'reject'])->name('service-requests.reject');
    Route::post('/service-requests/{serviceRequest}/complete', [App\Http\Controllers\Provider\ServiceRequestController::class, 'complete'])->name('service-requests.complete');

    // Bookings
    Route::resource('bookings', App\Http\Controllers\Provider\BookingController::class)->only(['index', 'show']);
    Route::post('/bookings/{booking}/start', [App\Http\Controllers\Provider\BookingController::class, 'start'])->name('bookings.start');
    Route::post('/bookings/{booking}/complete', [App\Http\Controllers\Provider\BookingController::class, 'complete'])->name('bookings.complete');

    // Reviews
    Route::resource('reviews', App\Http\Controllers\Provider\ReviewController::class)->only(['index', 'show']);
    Route::post('/reviews/{review}/respond', [App\Http\Controllers\Provider\ReviewController::class, 'respond'])->name('reviews.respond');

    // Earnings
    Route::get('/earnings', [App\Http\Controllers\Provider\EarningsController::class, 'index'])->name('earnings.index');

    // Chat
    Route::resource('chat', App\Http\Controllers\Provider\ChatController::class)->only(['index', 'show']);
    Route::post('/chat/{chat}', [App\Http\Controllers\Provider\ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{chat}/messages', [App\Http\Controllers\Provider\ChatController::class, 'getMessages'])->name('chat.messages');

    // Availability
    Route::resource('availability', App\Http\Controllers\Provider\AvailabilityController::class);
    Route::post('/toggle-availability', [App\Http\Controllers\Provider\DashboardController::class, 'toggleAvailability'])->name('toggle-availability');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Provider\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Provider\ProfileController::class, 'update'])->name('profile.update');

    // KYC Documents
    Route::resource('kyc', App\Http\Controllers\Provider\KycController::class);
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');

    // Provider Search and Browsing
    Route::get('/providers', [App\Http\Controllers\Customer\ProviderController::class, 'index'])->name('providers.index');
    Route::get('/providers/{provider}', [App\Http\Controllers\Customer\ProviderController::class, 'show'])->name('providers.show');

    // Service Requests
    Route::resource('service-requests', App\Http\Controllers\Customer\ServiceRequestController::class);
    Route::post('/service-requests/{serviceRequest}/cancel', [App\Http\Controllers\Customer\ServiceRequestController::class, 'cancel'])->name('service-requests.cancel');

    // Bookings
    Route::resource('bookings', App\Http\Controllers\Customer\BookingController::class)->only(['index', 'show', 'create', 'store']);
    Route::post('/bookings/{booking}/cancel', [App\Http\Controllers\Customer\BookingController::class, 'cancel'])->name('bookings.cancel');

    // Reviews
    Route::resource('reviews', App\Http\Controllers\Customer\ReviewController::class);
    Route::get('/bookings/{booking}/review', [App\Http\Controllers\Customer\ReviewController::class, 'createFromBooking'])->name('reviews.create-from-booking');
    Route::post('/bookings/{booking}/review', [App\Http\Controllers\Customer\ReviewController::class, 'storeFromBooking'])->name('reviews.store-from-booking');

    // Payments
    Route::get('/payments', [App\Http\Controllers\Customer\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [App\Http\Controllers\Customer\PaymentController::class, 'show'])->name('payments.show');

    // eSewa Payment Routes
    Route::get('/payments/{payment}/esewa', [App\Http\Controllers\Customer\PaymentController::class, 'esewa'])->name('payments.esewa');
    Route::get('/payments/esewa/success', [App\Http\Controllers\Customer\PaymentController::class, 'esewaSuccess'])->name('payments.esewa.success');
    Route::get('/payments/esewa/failure', [App\Http\Controllers\Customer\PaymentController::class, 'esewaFailure'])->name('payments.esewa.failure');

    // Khalti Payment Routes
    Route::get('/payments/{payment}/khalti', [App\Http\Controllers\Customer\PaymentController::class, 'khalti'])->name('payments.khalti');
    Route::post('/payments/khalti/verify', [App\Http\Controllers\Customer\PaymentController::class, 'verifyKhaltiPayment'])->name('payments.khalti.verify');
    Route::get('/payments/khalti/success', [App\Http\Controllers\Customer\PaymentController::class, 'khaltiSuccess'])->name('payments.khalti.success');
    Route::get('/payments/khalti/failure', [App\Http\Controllers\Customer\PaymentController::class, 'khaltiFailure'])->name('payments.khalti.failure');

    // Chat
    Route::resource('chat', App\Http\Controllers\Customer\ChatController::class)->only(['index', 'show']);
    Route::post('/chat/{chat}', [App\Http\Controllers\Customer\ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{chat}/messages', [App\Http\Controllers\Customer\ChatController::class, 'getMessages'])->name('chat.messages');

    // Favorites
    Route::get('/favorites', [App\Http\Controllers\Customer\FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/providers/{provider}/favorite', [App\Http\Controllers\Customer\FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');
});
