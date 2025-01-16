<?php

namespace Database\Seeders\procedures;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionStoredProcedureSeeder extends Seeder
{
    public function run()
    {
        DB::unprepared('
            CREATE PROCEDURE GetAllSubscriptions()
            BEGIN
                SELECT * FROM subscriptions;
            END;

            CREATE PROCEDURE GetSubscriptionById(IN subscriptionId INT)
            BEGIN
                SELECT * FROM subscriptions WHERE id = subscriptionId;
            END;

            CREATE PROCEDURE CreateSubscription(IN userId INT, IN plan VARCHAR(255))
            BEGIN
                INSERT INTO subscriptions (user_id, plan) VALUES (userId, plan);
            END;

            CREATE PROCEDURE UpdateSubscription(IN subscriptionId INT, IN subscriptionData JSON)
            BEGIN
                UPDATE subscriptions SET plan = JSON_UNQUOTE(JSON_EXTRACT(subscriptionData, "$.plan")) WHERE id = subscriptionId;
            END;

            CREATE PROCEDURE DeleteSubscription(IN subscriptionId INT)
            BEGIN
                DELETE FROM subscriptions WHERE id = subscriptionId;
            END;
        ');
    }
}