<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'release_date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(90, 180),
            'media_type' => $this->faker->randomElement(['movie', 'series']),
            'season_number' => null,
            'episode_number' => null,
            'series_title' => null,
            'language_id' => $this->faker->numberBetween(1, 5),
        ];
    }

    public function series()
    {
        return $this->state([
            'media_type' => 'series',
        ]);
    }

    public function movie()
    {
        return $this->state([
            'media_type' => 'movie',
            'season_number' => null,
            'episode_number' => null,
            'series_title' => null,
        ]);
    }
}
