<?php

namespace Database\Seeders\Procedures;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentStoredProcedureSeeder extends Seeder
{
    public function run()
    {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS GetAllSeries;
            CREATE PROCEDURE GetAllSeries()
            BEGIN
                SELECT * FROM media WHERE media_type = "EPISODE";
            END;

            DROP PROCEDURE IF EXISTS GetAllMovies;
            CREATE PROCEDURE GetAllMovies()
            BEGIN
                SELECT * FROM media WHERE media_type = "MOVIE";
            END;

            DROP PROCEDURE IF EXISTS GetContentById;
            CREATE PROCEDURE GetContentById(IN contentId INT)
            BEGIN
                SELECT * FROM media WHERE id = contentId;
            END;

            DROP PROCEDURE IF EXISTS GetRecommendations;
            CREATE PROCEDURE GetRecommendations()
            BEGIN
                SELECT * FROM media ORDER BY RAND() LIMIT 5;
            END;

            DROP PROCEDURE IF EXISTS SearchContent;
            CREATE PROCEDURE SearchContent(IN query VARCHAR(255))
            BEGIN
                SELECT * FROM media WHERE title LIKE CONCAT("%", query, "%");
            END;
        ');
    }
}