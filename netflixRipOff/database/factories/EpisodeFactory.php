<?php

namespace Database\Factories;

use App\Models\Episode;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

class EpisodeFactory extends Factory
{
    protected $model = Episode::class;

    public function definition()
    {
        return [
            'genre_id' => Genre::query()->inRandomOrder()->first()->id, // Random genre ID
            'title' => $this->faker->sentence(3), // Random episode title
            'description' => $this->faker->paragraph(), // Random episode description
            'runtime' => $this->faker->numberBetween(20, 60), // Runtime in minutes between 20 and 60
            'episode_title' => $this->faker->sentence(4), // Random episode subtitle
        ];
    }
}
