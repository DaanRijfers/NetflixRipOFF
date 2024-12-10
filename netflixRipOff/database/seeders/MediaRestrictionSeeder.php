<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\MediaRestriction;
use Illuminate\Database\Seeder;

class MediaRestrictionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Media::all()->each(function ($media) {
            // Generate 1–3 random restriction IDs
            $restrictions = range(1, 3); // Assuming you have 3 restrictions
            shuffle($restrictions); // Shuffle to randomize the order
            $selectedRestrictions = array_slice($restrictions, 0, rand(1, 3)); // Pick 1–3 restrictions

            foreach ($selectedRestrictions as $restrictionId) {
                MediaRestriction::create([
                    'media_id' => $media->id,
                    'restriction_id' => $restrictionId,
                ]);
            }
        });
    }
}
