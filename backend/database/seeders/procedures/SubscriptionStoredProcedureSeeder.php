<?php

namespace Database\Seeders\procedures;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionStoredProcedureSeeder extends Seeder
{
    public function run()
    {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS GetAllSubscriptions;
            CREATE PROCEDURE GetAllSubscriptions()
            BEGIN
                SELECT * FROM subscriptions;
            END;

            DROP PROCEDURE IF EXISTS GetSubscriptionById;
            CREATE PROCEDURE GetSubscriptionById(IN subscriptionId INT)
            BEGIN
                SELECT * FROM subscriptions WHERE id = subscriptionId;
            END;

            DROP PROCEDURE IF EXISTS CreateSubscription;
            CREATE PROCEDURE CreateSubscription(IN userId INT, IN plan VARCHAR(255), IN price DECIMAL(10, 2), IN quality VARCHAR(255))
            BEGIN
                INSERT INTO subscriptions (user_id, plan, price, quality) VALUES (userId, plan, price, quality);
            END;

            DROP PROCEDURE IF EXISTS UpdateSubscription;
            CREATE PROCEDURE UpdateSubscription(IN subscriptionId INT, IN subscriptionData JSON)
            BEGIN
                UPDATE subscriptions 
                SET plan = JSON_UNQUOTE(JSON_EXTRACT(subscriptionData, "$.plan")),
                    price = JSON_UNQUOTE(JSON_EXTRACT(subscriptionData, "$.price")),
                    quality = JSON_UNQUOTE(JSON_EXTRACT(subscriptionData, "$.quality"))
                WHERE id = subscriptionId;
            END;

            DROP PROCEDURE IF EXISTS DeleteSubscription;
            CREATE PROCEDURE DeleteSubscription(IN subscriptionId INT)
            BEGIN
                DELETE FROM subscriptions WHERE id = subscriptionId;
            END;
        ');
    }
}