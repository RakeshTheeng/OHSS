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
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_category_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->datetime('preferred_date')->nullable();
            $table->integer('estimated_duration')->nullable(); // in minutes
            $table->decimal('estimated_price', 8, 2)->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'booked', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('provider_response')->nullable();
            $table->datetime('responded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
