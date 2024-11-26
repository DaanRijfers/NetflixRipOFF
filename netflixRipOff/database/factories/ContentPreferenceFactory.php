<?php

namespace Database\Factories;

use App\Models\ContentPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentPreferenceFactory extends Factory
{
    protected $model = ContentPreference::class;

    public function definition()
    {
        return [
            'description' => $this->faker->sentence(), // Generates a random description
        ];
    }
}
