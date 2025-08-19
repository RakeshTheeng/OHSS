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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->enum('payment_method', ['esewa', 'cash']);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('esewa_ref_id')->nullable();
            $table->json('gateway_response')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->decimal('refund_amount', 8, 2)->nullable();
            $table->datetime('refunded_at')->nullable();
            $table->text('refund_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
