<?php

namespace Database\Factories;

use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceRequestFactory extends Factory
{
    protected $model = ServiceRequest::class;

    public function definition(): array
    {
        return [
            'customer_id' => User::factory(),
            'provider_id' => User::factory(),
            'service_category_id' => ServiceCategory::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->address(),
            'preferred_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'budget_min' => $this->faker->numberBetween(500, 2000),
            'budget_max' => $this->faker->numberBetween(2000, 5000),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected', 'completed']),
            'urgency' => $this->faker->randomElement(['low', 'medium', 'high']),
            'special_instructions' => $this->faker->optional()->sentence(),
        ];
    }
}
