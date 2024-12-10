<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MediaGenreFactory extends Factory
{
    public function definition()
    {
        return [
            'media_id' => $this->faker->numberBetween(1, 20),
            'genre_id' => $this->faker->numberBetween(1, 4),
        ];
    }
}

