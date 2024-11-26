<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Subscription;
use App\Models\User;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'is_blocked' => $this->faker->boolean(10),
            'payment_method' => $this->faker->randomElement(['paypal', 'credit_card']),
            'subscription_id' => Subscription::query()->inRandomOrder()->first()->id,
        ];
    }
}
