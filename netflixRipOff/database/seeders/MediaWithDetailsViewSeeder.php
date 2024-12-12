<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaWithDetailsViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW media_with_details AS
            SELECT
                media.id AS media_id,
                media.title AS media_title,
                GROUP_CONCAT(DISTINCT genres.name) AS genres,
                GROUP_CONCAT(DISTINCT restrictions.name) AS restrictions
            FROM
                media
            LEFT JOIN
                media_genres ON media.id = media_genres.media_id
            LEFT JOIN
                genres ON media_genres.genre_id = genres.id
            LEFT JOIN
                media_restrictions ON media.id = media_restrictions.media_id
            LEFT JOIN
                restrictions ON media_restrictions.restriction_id = restrictions.id
            GROUP BY
                media.id, media.title, media_type
            ORDER BY
                media.title;
        ");
    }
}
