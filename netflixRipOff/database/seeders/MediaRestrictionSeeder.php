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
            MediaRestriction::create([
                'media_id' => $media->id,
                'restriction_id' => rand(1, 3), 
            ]);
        });
    }
}
