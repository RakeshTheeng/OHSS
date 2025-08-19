<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    protected $model = Chat::class;

    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'customer_id' => User::factory(),
            'provider_id' => User::factory(),
            'is_active' => true,
        ];
    }
}
