<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MediaRestrictionFactory extends Factory
{
    public function definition()
    {
        return [
            'media_id' => $this->faker->numberBetween(1, 20),
            'restriction_id' => $this->faker->numberBetween(1, 3),
        ];
    }
}

