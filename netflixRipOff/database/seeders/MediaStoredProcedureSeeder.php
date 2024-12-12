<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaStoredProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Adds a new series episode
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE AddEpisode(
                IN title varchar(255),
                IN description text,
                IN duration int(11),
                IN mediaTypeId bigint(20),
                IN seriesTitle varchar(255),
                IN seasonNumber int(11),
                IN episodeNumber int(11),
                IN languageId bigint(20)
            )
            BEGIN
                INSERT INTO user_invitations(
                    title, 
                    description, 
                    release_date, 
                    duration, 
                    media_type_id, 
                    series_title, 
                    season_number, 
                    episode_number, 
                    language_id
                )
                VALUES (
                    title,
                    description, 
                    " . date("Y-m-d") . ",
                    mediaTypeId,
                    seriesTitle,
                    seasonNumber,
                    episodeNumber,
                    languageId
                );
            END;
        ");

        // Adds a new film
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE AddFilm(
                IN title varchar(255),
                IN description text,
                IN duration int(11),
                IN mediaTypeId bigint(20),
                IN languageId bigint(20)
            )
            BEGIN
                INSERT INTO user_invitations(
                    title, 
                    description, 
                    release_date, 
                    duration, 
                    media_type_id,  
                    language_id
                )
                VALUES (
                    title,
                    description, 
                    " . date("Y-m-d") . ",
                    mediaTypeId,
                    languageId
                );
            END;
        ");

        // Remove a media item
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE RemoveMediaItem(
                IN mediaId bigint(20)
            )
            BEGIN
                DELETE FROM media
                WHERE id = mediaId;
            END;
        ");
    }
}
