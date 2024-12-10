<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileCategoryPreferenceFactory extends Factory
{
    public function definition()
    {
        return [
            'profile_id' => $this->faker->numberBetween(1, 20),
            'category_id' => $this->faker->numberBetween(1, 3),
        ];
    }
}

