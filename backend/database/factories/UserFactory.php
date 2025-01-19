<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->domainWord() . '@lotr.com',
            'password' => bcrypt('password'),
            'payment_method' => $this->faker->randomElement(['PayPal', 'Credit Card', 'Bank Transfer']),
            'failed_login_attempts' => 0,
            'subscription_id' => $this->faker->numberBetween(1, 3),
        ];
    }

    public function admin()
    {
        return $this->state([
            'email' => 'admin@admin.com',
            'role' => 'admin'
        ]);
    }

    public function system()
    {
        return $this->state([
            'email' => 'noreply@admin.com',
            'role' => 'user'
        ]);
    }
}
