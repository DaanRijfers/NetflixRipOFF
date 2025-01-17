<?php

namespace Database\Seeders\Procedures;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WatchlistStoredProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add item to watchlist
        DB::unprepared("
            DROP PROCEDURE IF EXISTS AddWatchlist;
            CREATE PROCEDURE AddWatchlist(
                IN profileId bigint(20), 
                IN mediaId bigint(20)
            )
            BEGIN
                INSERT INTO watchlist(profile_id, media_id)
                VALUES (profileId, mediaId);
            END;
        ");

        // Remove item from watchlist
        DB::unprepared("
            DROP PROCEDURE IF EXISTS removeWatchlist;
            CREATE PROCEDURE removeWatchlist(
                IN profileId bigint(20), 
                IN mediaId bigint(20)
            )
            BEGIN
                DELETE FROM watchlist
                WHERE profile_id = profileId AND media_id = mediaId;
            END;
        ");
    }
}
