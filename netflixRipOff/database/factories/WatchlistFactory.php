<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WatchlistFactory extends Factory
{
    public function definition()
    {
        return [
            'profile_id' => $this->faker->numberBetween(1, 20),
            'media_id' => $this->faker->numberBetween(1, 20),
        ];
    }
}
