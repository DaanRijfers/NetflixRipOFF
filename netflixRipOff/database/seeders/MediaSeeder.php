<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        // Create multiple series entries with episodes
        $seriesTitles = ['Breaking Bad', 'Vampire Diaries', 'Stranger Things', 'Game of Thrones', 'The Office'];

        foreach ($seriesTitles as $seriesTitle) {
            // Create the series entry
            $series = Media::factory()->series()->create([
                'title' => $seriesTitle,
                'media_type' => 'series',
                'season_number' => null,
                'episode_number' => null,
                'series_title' => null, 
            ]);

            // Create random seasons and episodes for the series
            $totalSeasons = rand(2, 5);
            for ($season = 1; $season <= $totalSeasons; $season++) {
                $episodes = rand(5, 10);
                for ($episode = 1; $episode <= $episodes; $episode++) {
                    Media::factory()->create([
                        'title' => "Episode $episode - Season $season of $seriesTitle",
                        'media_type' => 'series',
                        'season_number' => $season,
                        'episode_number' => $episode,
                        'series_title' => $seriesTitle, 
                    ]);
                }
            }
        }

        // Create random movies
        Media::factory()->count(50)->create([
            'media_type' => 'movie',
            'season_number' => null,
            'episode_number' => null,
            'series_title' => null, 
        ]);
    }
}
