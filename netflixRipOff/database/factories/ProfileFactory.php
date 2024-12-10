<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => null,
            'name' => $this->faker->firstName(),
            'profile_picture_path' => $this->faker->optional()->imageUrl(),
            'date_of_birth' => $this->faker->date(),
            'language_id' => $this->faker->numberBetween(1, 5),
        ];
    }
}
