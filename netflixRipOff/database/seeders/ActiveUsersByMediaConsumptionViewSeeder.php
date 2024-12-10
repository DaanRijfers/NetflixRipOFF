<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActiveUsersByMediaConsumptionViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW active_users_by_media_consumption AS
            SELECT
                users.email AS user_email,
                COUNT(profile_histories.media_id) AS completed_media_count
            FROM
                profile_histories
            JOIN
                profiles ON profile_histories.profile_id = profiles.id
            JOIN
                users ON profiles.user_id = users.id
            WHERE
                profile_histories.completed = 1
            GROUP BY
                users.email
            ORDER BY
                completed_media_count DESC;
        ");
    }
}
