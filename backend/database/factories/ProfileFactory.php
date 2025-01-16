<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => null, // This will be set dynamically in the seeder
            'name' => $this->faker->firstName(),
            'profile_picture' => null, // Skip binary data
            'date_of_birth' => $this->faker->date(),
            'language_id' => $this->faker->numberBetween(1, 5), // Assuming 1–5 are valid language IDs
            'media_preference' => $this->faker->randomElement(['MOVIE', 'EPISODE']),
        ];
    }
}