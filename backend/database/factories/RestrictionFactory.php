<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RestrictionFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Violence', 'Strong Language', 'Drug Use']),
        ];
    }
}

