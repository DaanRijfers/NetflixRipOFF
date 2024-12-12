<?php

namespace Database\Seeders;

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
            CREATE OR REPLACE PROCEDURE AddWatchlist(
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
            CREATE OR REPLACE PROCEDURE removeWatchlist(
                IN profileId bigint(20), 
                IN mediaId bigint(20)
            )
            BEGIN
                DELETE FROM watchlist
                WHERE profile_id = proflieId AND media_id = mediaId;
            END;
        ");
    }
}
