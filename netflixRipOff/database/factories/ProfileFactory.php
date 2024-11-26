<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\ContentPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
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
            'user_id' => User::query()->inRandomOrder()->first()->id, // Ensure this fetches a random user
            'profile_picture_path' => $this->faker->imageUrl(),
            'is_child_locked' => $this->faker->boolean(30),
            'description_id' => ContentPreference::query()->inRandomOrder()->first()->id, // Ensure this fetches a random content preference
        ];
    }    
}
