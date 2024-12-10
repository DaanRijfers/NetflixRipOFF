<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Quality;
use App\Models\MediaQuality;
use Illuminate\Database\Seeder;

class MediaQualitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Media::all()->each(function ($media) {
            $qualities = Quality::inRandomOrder()->take(rand(1, 3))->get();

            foreach ($qualities as $quality) {
                MediaQuality::create([
                    'media_id' => $media->id,
                    'quality_id' => $quality->id,
                ]);
            }
        });
    }
}
