<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    protected $model = Movie::class;

    public function definition()
    {
        return [
            'genre_id' => Genre::query()->inRandomOrder()->first()->id, // Random genre ID
            'title' => $this->faker->sentence(3), // Generate a random title
            'description' => $this->faker->paragraph(), // Generate a random description
            'runtime' => $this->faker->numberBetween(80, 180), // Runtime between 80 and 180 minutes
        ];
    }
}
