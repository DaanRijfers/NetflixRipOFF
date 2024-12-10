<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LanguageFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['English', 'Dutch', 'Spanish', 'French', 'German']),
        ];
    }
}
