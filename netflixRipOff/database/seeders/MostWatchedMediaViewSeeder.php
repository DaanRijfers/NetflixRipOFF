<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MostWatchedMediaViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW most_watched_media AS
            SELECT
                media.title,
                COUNT(profile_histories.media_id) AS watch_count
            FROM
                profile_histories
            JOIN
                media ON profile_histories.media_id = media.id
            WHERE
                profile_histories.completed = 1
            GROUP BY
                media.title
            ORDER BY
                watch_count DESC;
        ");
    }
}
