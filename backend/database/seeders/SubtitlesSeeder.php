<?php

namespace Database\Seeders;

use App\Models\Media; 
use App\Models\Language; 
use App\Models\Subtitle; 
use Illuminate\Database\Seeder;

class SubtitlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Media::all()->each(function ($media) {
            $languages = Language::inRandomOrder()->take(rand(1, 3))->get();

            foreach ($languages as $language) {
                Subtitle::create([
                    'media_id' => $media->id,
                    'language_id' => $language->id,
                ]);
            }
        });
    }
}
