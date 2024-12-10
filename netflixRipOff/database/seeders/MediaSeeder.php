<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;
use App\Models\MediaType;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch media type IDs
        $seriesTypeId = MediaType::where('name', 'series')->first()->id;
        $movieTypeId = MediaType::where('name', 'movie')->first()->id;

        // Create multiple series entries with episodes
        $seriesTitles = ['Breaking Bad', 'Vampire Diaries', 'Stranger Things', 'Game of Thrones', 'The Office'];

        foreach ($seriesTitles as $seriesTitle) {
            // Create the series entry
            $series = Media::factory()->create([
                'title' => $seriesTitle,
                'media_type_id' => $seriesTypeId,
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
                        'media_type_id' => $seriesTypeId,
                        'season_number' => $season,
                        'episode_number' => $episode,
                        'series_title' => $seriesTitle,
                    ]);
                }
            }
        }

        // Create random movies
        Media::factory()->count(50)->create([
            'media_type_id' => $movieTypeId,
            'season_number' => null,
            'episode_number' => null,
            'series_title' => null,
        ]);
    }
}
