<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'service_request_id' => ServiceRequest::factory(),
            'customer_id' => User::factory(),
            'provider_id' => User::factory(),
            'scheduled_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'duration_hours' => $this->faker->numberBetween(1, 8),
            'total_amount' => $this->faker->numberBetween(1000, 10000),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'in_progress', 'completed', 'cancelled']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
