<?php

namespace Database\Seeders\Views;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeriesEpisodesWithFilesViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW series_episodes_with_files AS
            SELECT
                series_title,
                season_number,
                episode_number,
                title AS episode_title,
                file_path AS episode_file_path
            FROM
                media
            WHERE
                media_type = 'EPISODE' AND series_title IS NOT NULL
            ORDER BY
                series_title, season_number, episode_number;
        ");
    }
}