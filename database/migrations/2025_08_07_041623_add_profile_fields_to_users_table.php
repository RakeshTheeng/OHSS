<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only add fields that don't exist
            if (!Schema::hasColumn('users', 'kyc_document')) {
                $table->string('kyc_document')->nullable()->after('experience_years');
            }
            if (!Schema::hasColumn('users', 'citizenship_number')) {
                $table->string('citizenship_number')->nullable()->after('kyc_document');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'kyc_document',
                'citizenship_number'
            ]);
        });
    }
};
