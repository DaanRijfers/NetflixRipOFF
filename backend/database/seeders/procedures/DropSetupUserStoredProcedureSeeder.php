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
        $procedureName = 'DropSetupUser';

        // Check if the procedure already exists
        if ($this->procedureExists($procedureName)) {
            $this->command->info("Procedure {$procedureName} already exists. Skipping.");
            return;
        }

        // Create the procedure
        DB::unprepared("
            CREATE PROCEDURE {$procedureName}()
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

        $this->command->info("Procedure {$procedureName} created successfully.");
    }

    /**
     * Check if a stored procedure exists.
     *
     * @param string $procedureName
     * @return bool
     */
    private function procedureExists(string $procedureName): bool
    {
        $result = DB::selectOne("
            SELECT COUNT(*) AS count
            FROM information_schema.ROUTINES
            WHERE ROUTINE_TYPE = 'PROCEDURE' AND ROUTINE_NAME = ?
        ", [$procedureName]);

        return $result->count > 0;
    }
}