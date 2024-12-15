<?php

namespace Database\Seeders;

use App\Models\Profile; 
use App\Models\ProfileCategoryPreference; 
use Illuminate\Database\Seeder;

class ProfileCategoryPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::all()->each(function ($profile) {
            ProfileCategoryPreference::create([
                'profile_id' => $profile->id,
                'category_id' => rand(1, 3), 
            ]);
        });
    }
}
