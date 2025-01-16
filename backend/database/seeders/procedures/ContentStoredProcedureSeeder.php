<?php

namespace Database\Seeders\Procedures;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentStoredProcedureSeeder extends Seeder
{
    public function run()
    {
        DB::unprepared('
            CREATE PROCEDURE GetAllSeries()
            BEGIN
                SELECT * FROM series_with_episodes;
            END;

            CREATE PROCEDURE GetAllMovies()
            BEGIN
                SELECT * FROM movies_with_quality;
            END;

            CREATE PROCEDURE GetContentById(IN contentId INT)
            BEGIN
                SELECT * FROM media WHERE id = contentId;
            END;

            CREATE PROCEDURE GetRecommendations()
            BEGIN
                SELECT * FROM media ORDER BY RAND() LIMIT 5;
            END;

            CREATE PROCEDURE SearchContent(IN query VARCHAR(255))
            BEGIN
                SELECT * FROM media WHERE title LIKE CONCAT("%", query, "%");
            END;
        ');
    }
}