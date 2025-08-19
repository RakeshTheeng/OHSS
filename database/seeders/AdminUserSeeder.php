<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@householdservices.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Create sample customer
        User::create([
            'name' => 'John Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
            'email_verified_at' => now(),
            'status' => 'active',
            'phone' => '+977-9841234567',
            'address' => 'Kathmandu, Nepal',
        ]);

        // Create sample provider
        User::create([
            'name' => 'Mike Provider',
            'email' => 'provider@example.com',
            'password' => Hash::make('provider123'),
            'role' => 'provider',
            'email_verified_at' => now(),
            'status' => 'active',
            'phone' => '+977-9851234567',
            'address' => 'Lalitpur, Nepal',
            'hourly_rate' => 500.00,
            'provider_status' => 'approved',
            'is_available' => true,
            'services' => ['plumbing', 'electrical'],
        ]);
    }
}
