<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\Media;
use App\Models\ProfileHistory;
use Illuminate\Database\Seeder;

class ProfileHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usedCombinations = []; 

        Profile::all()->each(function ($profile) use (&$usedCombinations) {
            $mediaIds = Media::pluck('id')->toArray(); 
            shuffle($mediaIds); 

            for ($i = 0; $i < rand(1, 5); $i++) { 
                foreach ($mediaIds as $mediaId) {
                    // Check if this combination has already been used
                    if (isset($usedCombinations["{$profile->id}-{$mediaId}"])) {
                        continue;
                    }

                    // Mark this combination as used
                    $usedCombinations["{$profile->id}-{$mediaId}"] = true;

                    ProfileHistory::create([
                        'profile_id' => $profile->id,
                        'media_id' => $mediaId,
                        'watch_time' => now()->subMinutes(rand(1, 500)),
                        'completed' => rand(0, 1),
                    ]);

                    break; 
                }
            }
        });
    }
}
