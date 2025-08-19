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
        // Use raw SQL to modify enum as Laravel's change() doesn't work well with enums
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending_payment', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'confirmed'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Use raw SQL to revert enum
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'confirmed'");
    }
};
