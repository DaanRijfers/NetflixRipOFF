<?php

namespace Database\Factories;

use App\Models\ProfilePreference;
use App\Models\Profile;
use App\Models\ViewingGuide;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfilePreferenceFactory extends Factory
{
    protected $model = ProfilePreference::class;

    public function definition()
    {
        return [
            'profile_id' => Profile::query()->inRandomOrder()->first()->id,
            'viewing_guide_id' => ViewingGuide::query()->inRandomOrder()->first()->id,
            'genre_id' => Genre::query()->inRandomOrder()->first()->id,
        ];
    }
}
