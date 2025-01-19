<?php

namespace Database\Seeders\Views;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeriesWithEpisodesViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW series_with_episodes AS
            SELECT
                series_title,
                COUNT(DISTINCT season_number) AS total_seasons,
                COUNT(*) AS total_episodes
            FROM
                media
            WHERE
                media_type = 'EPISODE' AND series_title IS NOT NULL
            GROUP BY
                series_title
            ORDER BY
                series_title;
        ");
    }
}
