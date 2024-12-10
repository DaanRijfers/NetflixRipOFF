<?php

namespace Database\Seeders;

use App\Models\Media; 
use App\Models\MediaGenre;
use Illuminate\Database\Seeder;

class MediaGenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Media::all()->each(function ($media) {
            MediaGenre::create([
                'media_id' => $media->id,
                'genre_id' => rand(1, 4),
            ]);
        });
    }
}
