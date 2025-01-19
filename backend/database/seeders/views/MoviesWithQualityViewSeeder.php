<?php

namespace Database\Seeders\Views;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoviesWithQualityViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW movies_with_quality AS
            SELECT
                media.id AS movie_id,
                media.title AS movie_title,
                GROUP_CONCAT(DISTINCT media_qualities.quality ORDER BY media_qualities.quality ASC) AS qualities
            FROM
                media
            LEFT JOIN
                media_qualities ON media.id = media_qualities.media_id
            WHERE
                media.media_type = 'MOVIE'
            GROUP BY
                media.id, media.title
            ORDER BY
                media.title;
        ");
    }
}
