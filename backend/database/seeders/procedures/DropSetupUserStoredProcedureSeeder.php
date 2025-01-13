<?php

namespace Database\Seeders\Procedures;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DropSetupUserStoredProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Drops the setup_user if it exists
        DB::connection('setup_user')->unprepared("
            CREATE OR REPLACE PROCEDURE DropSetupUser()
            BEGIN
                DECLARE userCount INT;

                -- Check if the setup_user exists
                SELECT COUNT(*) INTO userCount
                FROM mysql.user
                WHERE User = 'setup_user' AND Host = '%';

                -- Drop the user if it exists
                IF userCount > 0 THEN
                    DROP USER 'setup_user'@'%';
                    FLUSH PRIVILEGES;
                    SELECT 'User setup_user@% has been dropped.' AS Message;
                ELSE
                    SELECT 'User setup_user@% does not exist.' AS Message;
                END IF;
            END;
        ");
    }
}