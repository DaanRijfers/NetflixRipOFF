<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MediaType;

class MediaFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'release_date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(90, 180),
            'media_type_id' => MediaType::inRandomOrder()->first()->id, // Assign a random valid media_type_id
            'season_number' => null,
            'episode_number' => null,
            'series_title' => null,
            'language_id' => $this->faker->numberBetween(1, 5),
        ];
    }

    public function series()
    {
        $seriesType = MediaType::where('name', 'series')->first();
        return $this->state([
            'media_type_id' => $seriesType ? $seriesType->id : null, // Ensure a valid media_type_id for 'series'
        ]);
    }

    public function movie()
    {
        $movieType = MediaType::where('name', 'movie')->first();
        return $this->state([
            'media_type_id' => $movieType ? $movieType->id : null, // Ensure a valid media_type_id for 'movie'
            'season_number' => null,
            'episode_number' => null,
            'series_title' => null,
        ]);
    }
}
