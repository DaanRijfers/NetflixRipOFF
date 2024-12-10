<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesWithMostWatchlistItemsViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW profiles_with_most_watchlist_items AS
            SELECT
                profiles.name AS profile_name,
                users.email AS user_email,
                COUNT(watchlists.media_id) AS watchlist_count
            FROM
                watchlists
            JOIN
                profiles ON watchlists.profile_id = profiles.id
            JOIN
                users ON profiles.user_id = users.id
            GROUP BY
                profiles.name, users.email
            ORDER BY
                watchlist_count DESC;
        ");
    }
}
