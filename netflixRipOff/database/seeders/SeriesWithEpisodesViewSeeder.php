<?php

namespace Database\Seeders;

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
                parent.title AS series_title,
                child.season_number,
                child.episode_number,
                child.title AS episode_title
            FROM
                media AS parent
            JOIN
                media AS child ON parent.title = child.series_title
            JOIN
                media_types AS mt_parent ON parent.media_type_id = mt_parent.id
            JOIN
                media_types AS mt_child ON child.media_type_id = mt_child.id
            WHERE
                mt_parent.name = 'series' AND mt_child.name = 'series'
            ORDER BY
                series_title, season_number, episode_number;
        ");
    }
}
