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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
            $table->datetime('scheduled_date');
            $table->integer('duration'); // in minutes
            $table->decimal('total_amount', 8, 2);
            $table->enum('payment_method', ['esewa', 'cash']);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('status', ['confirmed', 'in_progress', 'completed', 'cancelled'])->default('confirmed');
            $table->text('special_instructions')->nullable();
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->text('completion_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
