<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileGenrePreferenceFactory extends Factory
{
    public function definition()
    {
        return [
            'profile_id' => $this->faker->numberBetween(1, 20),
            'genre_id' => $this->faker->numberBetween(1, 4),
        ];
    }
}

