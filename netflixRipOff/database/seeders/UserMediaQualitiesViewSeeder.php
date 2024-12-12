<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserMediaQualitiesViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW user_with_subscription AS
            SELECT
                users.email AS user_email,
                subscriptions.quality AS subscription_quality
            FROM
                users
            JOIN
                subscriptions ON users.subscription_id = subscriptions.id
            ORDER BY
                users.email;
        ");
    }
}
