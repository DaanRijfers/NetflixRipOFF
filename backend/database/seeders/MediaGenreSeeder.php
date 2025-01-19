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
            // Generate 2–4 random genre IDs
            $genres = range(1, 4); // Assuming you have 4 genres
            shuffle($genres); // Shuffle to randomize the order
            $selectedGenres = array_slice($genres, 0, rand(2, 4)); // Pick 2–4 genres

            foreach ($selectedGenres as $genreId) {
                MediaGenre::create([
                    'media_id' => $media->id,
                    'genre_id' => $genreId,
                ]);
            }
        });
    }
}
