<?php

namespace Database\Seeders\Procedures;

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
            DROP PROCEDURE IF EXISTS RemoveWatchlist;
            CREATE PROCEDURE RemoveWatchlist(
                IN profileId bigint(20),
                IN mediaId bigint(20)
            )
            BEGIN
                DELETE FROM watchlist
                WHERE profile_id = profileId AND media_id = mediaId;
            END;
        ");

        // Remove user by ID
        DB::unprepared("
            DROP PROCEDURE IF EXISTS DeleteUser;
            CREATE PROCEDURE DeleteUser(
                IN userId bigint(20)
            )
            BEGIN
                DELETE FROM users 
                WHERE id = userId;
            END;
        ");

        // Increment failed_login_attempts by user ID
        DB::unprepared("
            DROP PROCEDURE IF EXISTS IncrementFailedLoginUser;
            CREATE PROCEDURE IncrementFailedLoginUser(
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
            DROP PROCEDURE IF EXISTS UpdateUserProperty;
            CREATE PROCEDURE UpdateUserProperty(
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

        // Get all users
        DB::unprepared("
            DROP PROCEDURE IF EXISTS GetAllUsers;
            CREATE PROCEDURE GetAllUsers()
            BEGIN
                SELECT * FROM users;
            END;
        ");

        // Get user by ID
        DB::unprepared("
            DROP PROCEDURE IF EXISTS GetUserById;
            CREATE PROCEDURE GetUserById(
                IN userId INT
            )
            BEGIN
                SELECT * FROM users 
                WHERE id = userId;
            END;
        ");

        // Update user
        DB::unprepared("
            DROP PROCEDURE IF EXISTS UpdateUser;
            CREATE PROCEDURE UpdateUser(
            IN userId INT, 
            IN userData JSON
            )
            BEGIN
            UPDATE users 
            SET email = JSON_UNQUOTE(JSON_EXTRACT(userData, '$.email')) 
            WHERE id = userId;
            END;
        ");
    }
}
