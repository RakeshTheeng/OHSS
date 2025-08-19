<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'chat_id' => Chat::factory(),
            'sender_id' => User::factory(),
            'message' => $this->faker->sentence(),
            'read_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
