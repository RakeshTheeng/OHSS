<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Chat;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = User::where('role', 'customer')->first();
        $provider = User::where('role', 'provider')->first();

        if ($customer && $provider) {
            Chat::create([
                'customer_id' => $customer->id,
                'provider_id' => $provider->id,
            ]);
        }
    }
}
