<?php

namespace Database\Factories;

use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceCategoryFactory extends Factory
{
    protected $model = ServiceCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Plumbing',
                'Electrical',
                'Cleaning',
                'Gardening',
                'Painting',
                'Carpentry',
                'AC Repair',
                'Appliance Repair'
            ]),
            'description' => $this->faker->sentence(),
            'icon' => $this->faker->randomElement([
                'fas fa-wrench',
                'fas fa-bolt',
                'fas fa-broom',
                'fas fa-seedling',
                'fas fa-paint-brush',
                'fas fa-hammer',
                'fas fa-snowflake',
                'fas fa-tools'
            ]),
            'is_active' => true,
        ];
    }
}
