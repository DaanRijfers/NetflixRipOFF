<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QualityFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['SD', 'HD', 'UHD']),
        ];
    }
}
