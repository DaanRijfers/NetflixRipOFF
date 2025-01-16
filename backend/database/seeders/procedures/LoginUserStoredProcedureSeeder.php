<?php
namespace Database\Seeders\Procedures;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoginUserStoredProcedureSeeder extends Seeder {
    public function run() {
        DB::unprepared('
        DROP PROCEDURE IF EXISTS LoginUser;

        CREATE PROCEDURE LoginUser(
            IN p_email VARCHAR(255),
            OUT p_user_id INT,
            OUT p_hashed_password VARCHAR(255),
            OUT p_message VARCHAR(255)
        )
        BEGIN
            -- Check if the email exists and get the stored password
            SELECT id, password INTO p_user_id, p_hashed_password FROM users 
            WHERE email COLLATE utf8mb4_unicode_ci = p_email COLLATE utf8mb4_unicode_ci;

            IF p_user_id IS NULL THEN
                SET p_message = "Invalid credentials";
            ELSE
                SET p_message = "User found";
            END IF;
        END;
        ');
    }
}