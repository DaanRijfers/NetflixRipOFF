<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    public function definition()
    {
        return [
            'price' => $this->faker->randomElement([7.99, 10.99, 13.99]),
            'quality_id' => $this->faker->numberBetween(1, 3),
        ];
    }
}

