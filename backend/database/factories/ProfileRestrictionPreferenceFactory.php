<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileRestrictionPreferenceFactory extends Factory
{
    public function definition()
    {
        return [
            'profile_id' => $this->faker->numberBetween(1, 20),
            'restriction_id' => $this->faker->numberBetween(1, 3),
        ];
    }
}

