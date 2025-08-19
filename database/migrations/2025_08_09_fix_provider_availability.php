<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing providers to be available by default
        User::where('role', 'provider')
            ->where('provider_status', 'approved')
            ->where('status', 'active')
            ->whereIn('is_available', [false, null])
            ->update(['is_available' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible as we don't know the original state
    }
};
