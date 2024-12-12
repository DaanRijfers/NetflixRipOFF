<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserStoredProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add new user
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE RemoveWatchlist(
                IN profileId bigint(20),
                IN mediaId bigint(20)
            )
            BEGIN
                DELETE FROM watchlist
                WHERE profile_id = proflieId AND media_id = mediaId;
            END;
        ");

        // Remove user by ID
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE DeleteUser(
                IN userId bigint(20)
            )
            BEGIN
                DELETE FROM users 
                WHERE id = userId;
            END;
        ");

        // Increment failed_login_attempts by user ID
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE IncrementFailedLoginUser(
                IN userId bigint(20)
            )
            BEGIN
                UPDATE users
                SET failed_login_attempts = users.failed_login_attempts + 1 
                WHERE id = userId;
            END;
        ");

        // Updates property of user by column name
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE UpdateUserProperty(
                IN userId bigint(20), 
                IN columnName varchar(64), 
                IN newValue TEXT
            )
            BEGIN
                SET @sql = CONCAT('UPDATE users SET ', columnName, ' = ? WHERE id = ?');
                PREPARE stmt FROM @sql;
                EXECUTE stmt USING newValue, userId;
                DEALLOCATE PREPARE stmt;
            END;
        ");
    }
}
