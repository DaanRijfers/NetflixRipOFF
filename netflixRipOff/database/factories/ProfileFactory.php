<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MediaType;

class ProfileFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => null, // This will be set dynamically in the seeder
            'name' => $this->faker->firstName(),
            'profile_picture_path' => $this->faker->optional()->imageUrl(),
            'date_of_birth' => $this->faker->date(),
            'language_id' => $this->faker->numberBetween(1, 5), // Assuming 1–5 are valid language IDs
            'media_preference' => MediaType::inRandomOrder()->first()->id, // Random media type preference
        ];
    }
}
