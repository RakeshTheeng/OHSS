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
        Schema::table('service_requests', function (Blueprint $table) {
            // Add budget fields (keeping for backward compatibility)
            $table->decimal('budget_min', 8, 2)->nullable()->after('address');
            $table->decimal('budget_max', 8, 2)->nullable()->after('budget_min');

            // Add new required hours field
            $table->decimal('required_hours', 5, 2)->nullable()->after('budget_max');

            // Add hourly rate (captured at time of request)
            $table->decimal('hourly_rate', 8, 2)->nullable()->after('required_hours');

            // Add total budget (calculated field)
            $table->decimal('total_budget', 8, 2)->nullable()->after('hourly_rate');

            // Add title field if it doesn't exist
            if (!Schema::hasColumn('service_requests', 'title')) {
                $table->string('title')->nullable()->after('service_category_id');
            }

            // Add additional notes field if it doesn't exist
            if (!Schema::hasColumn('service_requests', 'additional_notes')) {
                $table->text('additional_notes')->nullable()->after('provider_response');
            }

            // Add urgency field if it doesn't exist
            if (!Schema::hasColumn('service_requests', 'urgency')) {
                $table->enum('urgency', ['low', 'medium', 'high'])->default('medium')->after('additional_notes');
            }

            // Add preferred time field if it doesn't exist
            if (!Schema::hasColumn('service_requests', 'preferred_time')) {
                $table->string('preferred_time')->nullable()->after('preferred_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn([
                'budget_min',
                'budget_max',
                'required_hours',
                'hourly_rate',
                'total_budget',
                'title',
                'additional_notes',
                'urgency',
                'preferred_time'
            ]);
        });
    }
};
