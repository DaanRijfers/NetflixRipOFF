<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionRevenueByQualityViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW subscription_revenue_by_quality AS
            SELECT
                qualities.name AS quality,
                COUNT(users.id) AS subscribers,
                (subscriptions.price * COUNT(users.id)) AS total_revenue
            FROM
                users
            JOIN
                subscriptions ON users.subscription_id = subscriptions.id
            JOIN
                qualities ON subscriptions.quality_id = qualities.id
            GROUP BY
                qualities.name, subscriptions.price
            ORDER BY
                total_revenue DESC;
        ");
    }
}
