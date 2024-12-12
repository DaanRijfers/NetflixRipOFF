<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaAvailabilityByQualityViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW media_availability_by_quality AS
            SELECT
                media.title AS media_title,
                media_qualities.quality AS quality
            FROM
                media_qualities
            JOIN
                media ON media_qualities.media_id = media.id
            ORDER BY
                media_title, quality;
        ");
    }
}
