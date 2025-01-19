<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $seriesTitles = ['Breaking Bad', 'Vampire Diaries', 'Stranger Things', 'Doctor Who', 'The Originals'];

        foreach ($seriesTitles as $seriesTitle) {
            $totalSeasons = rand(2, 5);

            for ($season = 1; $season <= $totalSeasons; $season++) {
                $episodes = rand(5, 10);
                for ($episode = 1; $episode <= $episodes; $episode++) {
                    Media::factory()->create([
                        'title' => "Episode $episode - Season $season of $seriesTitle",
                        'description' => "Description for Episode $episode - Season $season of $seriesTitle.",
                        'release_date' => now()->subDays(rand(1, 1000)),
                        'duration' => rand(20, 60),
                        'media_type' => 'EPISODE',
                        'season_number' => $season,
                        'episode_number' => $episode,
                        'series_title' => $seriesTitle,
                        'file_path' => "media/$seriesTitle/season_$season/episode_$episode.mp4",
                    ]);
                }
            }
        }

        Media::factory()->count(50)->create([
            'media_type' => 'MOVIE',
            'season_number' => null,
            'episode_number' => null,
            'series_title' => null,
            'file_path' => function () {
                return 'media/movies/' . fake()->uuid() . '.mp4';
            },
        ]);
    }
}
