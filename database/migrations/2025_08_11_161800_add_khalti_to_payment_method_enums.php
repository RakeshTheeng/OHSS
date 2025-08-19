<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update bookings table payment_method enum to include 'khalti'
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_method ENUM('esewa', 'khalti', 'cash')");
        
        // Update payments table payment_method enum to include 'khalti'
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('esewa', 'khalti', 'cash')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert bookings table payment_method enum to original values
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_method ENUM('esewa', 'cash')");
        
        // Revert payments table payment_method enum to original values
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('esewa', 'cash')");
    }
};
