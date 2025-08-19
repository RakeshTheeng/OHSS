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
        // Update booking payment_status enum to include 'awaiting_payment'
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('awaiting_payment', 'pending', 'paid', 'failed', 'refunded') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert booking payment_status enum to original values
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending'");
    }
};
