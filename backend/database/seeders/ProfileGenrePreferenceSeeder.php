<?php

namespace Database\Seeders;

use App\Models\ProfileGenrePreference;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileGenrePreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::all()->each(function ($profile) {
            ProfileGenrePreference::create([
                'profile_id' => $profile->id,
                'genre_id' => rand(1, 4),
            ]);
        });
    }
}
