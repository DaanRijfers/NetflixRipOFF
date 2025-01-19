<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MediaCategoryFactory extends Factory
{
    public function definition()
    {
        return [
            'media_id' => $this->faker->numberBetween(1, 20),
            'category_id' => $this->faker->numberBetween(1, 3),
        ];
    }
}

