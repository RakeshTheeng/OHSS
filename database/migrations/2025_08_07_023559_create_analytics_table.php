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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name'); // e.g., 'daily_bookings', 'revenue', 'user_registrations'
            $table->string('metric_type'); // 'count', 'sum', 'average'
            $table->decimal('value', 15, 2);
            $table->date('date');
            $table->string('category')->nullable(); // e.g., 'service_category', 'location'
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();

            $table->index(['metric_name', 'date']);
            $table->index(['date', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
