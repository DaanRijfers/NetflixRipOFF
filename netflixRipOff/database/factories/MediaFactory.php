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
            'media_type' => $this->faker->randomElement(['MOVIE', 'EPISODE']),
            'season_number' => null,
            'episode_number' => null,
            'series_title' => null,
            'language_id' => $this->faker->numberBetween(1, 5),
            'file_path' => $this->generateFilePath('MOVIE'), 
        ];
    }

    /**
     * State for series episodes.
     */
    public function series()
    {
        return $this->state(function (array $attributes) {
            return [
                'media_type' => 'EPISODE',
                'file_path' => $this->generateFilePath('EPISODE', $attributes),
            ];
        });
    }

    /**
     * State for movies.
     */
    public function movie()
    {
        return $this->state(function (array $attributes) {
            return [
                'media_type' => 'MOVIE',
                'season_number' => null,
                'episode_number' => null,
                'series_title' => null,
                'file_path' => $this->generateFilePath('MOVIE'),
            ];
        });
    }

    /**
     * Generate file path dynamically.
     */
    private function generateFilePath(string $type, array $attributes = []): string
    {
        if ($type === 'EPISODE') {
            $seriesTitle = $attributes['series_title'] ?? 'Unknown_Series';
            $season = $attributes['season_number'] ?? 1;
            $episode = $attributes['episode_number'] ?? 1;
            return "media/$seriesTitle/season_$season/episode_$episode.mp4";
        }

        return 'media/movies/' . $this->faker->uuid() . '.mp4';
    }
}
