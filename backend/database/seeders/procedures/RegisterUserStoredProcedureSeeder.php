<?php

namespace Database\Seeders\Procedures;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegisterUserStoredProcedureSeeder extends Seeder
{
    public function run()
    {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS RegisterUser;
            CREATE PROCEDURE RegisterUser(
                IN p_email VARCHAR(255),
                IN p_password VARCHAR(255),
                OUT p_message VARCHAR(255)
            )
            BEGIN
                DECLARE user_count INT;

                -- Check if the email already exists
                SELECT COUNT(*) INTO user_count FROM users WHERE email COLLATE utf8mb4_unicode_ci = p_email COLLATE utf8mb4_unicode_ci;

                IF user_count > 0 THEN
                    SET p_message = "Email already exists";
                ELSE
                    -- Insert the new user
                    INSERT INTO users (email, password, created_at, updated_at)
                    VALUES (p_email, p_password, NOW(), NOW());
                    SET p_message = "User registered successfully";
                END IF;
            END;
        ');
    }
}