<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Quality;
use App\Models\MediaQuality;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class MediaQualitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        Media::all()->each(function ($media) use ($faker) {
            // Generate 1–3 random qualities
            $qualities = $faker->randomElements(['SD', 'HD', 'UHD'], rand(1, 3));

            foreach ($qualities as $quality) {
                MediaQuality::create([
                    'media_id' => $media->id,
                    'quality' => $quality,
                ]);
            }
        });
    }
}
