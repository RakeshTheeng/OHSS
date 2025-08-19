<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Hash;

// Initialize database connection
$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => __DIR__ . '/database/database.sqlite',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Create provider user
try {
    $provider = Capsule::table('users')->insert([
        'name' => 'Test Provider',
        'email' => 'provider@test.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'role' => 'provider',
        'phone' => '+977-9841234567',
        'address' => 'Kathmandu, Nepal',
        'hourly_rate' => 500.00,
        'provider_status' => 'approved',
        'is_available' => true,
        'bio' => 'Professional household service provider',
        'experience_years' => 5,
        'rating' => 4.5,
        'total_reviews' => 0,
        'status' => 'active',
        'email_verified_at' => date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
    
    echo "Provider created successfully!\n";
    echo "Email: provider@test.com\n";
    echo "Password: password123\n";
    
} catch (Exception $e) {
    echo "Error creating provider: " . $e->getMessage() . "\n";
}
