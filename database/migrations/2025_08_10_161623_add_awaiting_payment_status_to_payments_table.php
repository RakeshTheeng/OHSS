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
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('awaiting_payment', 'pending', 'processing', 'completed', 'failed', 'refunded') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Use raw SQL to revert enum
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'failed', 'refunded') DEFAULT 'pending'");
    }
};
