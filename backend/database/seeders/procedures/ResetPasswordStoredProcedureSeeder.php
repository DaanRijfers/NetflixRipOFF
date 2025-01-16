<?php

namespace Database\Seeders\Procedures;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetPasswordStoredProcedureSeeder extends Seeder
{
    public function run()
    {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS ResetPassword;
            CREATE PROCEDURE ResetPassword(
                IN p_email VARCHAR(255),
                OUT p_message VARCHAR(255)
            )
            BEGIN
                DECLARE user_count INT;
                DECLARE new_password VARCHAR(255);

                -- Check if the email exists
                SELECT COUNT(*) INTO user_count FROM users WHERE email COLLATE utf8mb4_unicode_ci = p_email COLLATE utf8mb4_unicode_ci;

                IF user_count = 0 THEN
                    SET p_message = "Email does not exist";
                ELSE
                    -- Generate a new random password
                    SET new_password = SUBSTRING(MD5(RAND()), 1, 8);
                    UPDATE users SET password = new_password, updated_at = NOW() WHERE email = p_email;
                    SET p_message = CONCAT("Password reset successfully. New password: ", new_password);
                END IF;
            END;
        ');
    }
}
