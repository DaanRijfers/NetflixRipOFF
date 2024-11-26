<?php

namespace Database\Factories;

use App\Models\ProfileWatchtime;
use App\Models\Profile;
use App\Models\Movie;
use App\Models\Episode;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileWatchtimeFactory extends Factory
{
    protected $model = ProfileWatchtime::class;

    public function definition()
    {
        return [
            'profile_id' => Profile::query()->inRandomOrder()->first()->id,
            'movie_id' => Movie::query()->inRandomOrder()->first()->id,
            'episode_id' => Episode::query()->inRandomOrder()->first()->id,
            'watch_time' => $this->faker->numberBetween(1, 500), // Watch time in minutes
        ];
    }
}
