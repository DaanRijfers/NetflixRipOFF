<?php

namespace Database\Factories;

use App\Models\ViewingGuide;
use Illuminate\Database\Eloquent\Factories\Factory;

class ViewingGuideFactory extends Factory
{
    protected $model = ViewingGuide::class;

    public function definition()
    {
        return [
            'description' => $this->faker->sentence(), // Generates a random description
        ];
    }
}
