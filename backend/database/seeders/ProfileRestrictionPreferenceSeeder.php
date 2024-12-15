<?php

namespace Database\Seeders;

use App\Models\ProfileRestrictionPreference;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileRestrictionPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::all()->each(function ($profile) {
            ProfileRestrictionPreference::create([
                'profile_id' => $profile->id,
                'restriction_id' => rand(1, 3),
            ]);
        });
    }
}
