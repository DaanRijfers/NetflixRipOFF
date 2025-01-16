<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\QueryException;

class GrantRevokePermissionsSeeder extends Seeder
{
    public function run()
    {
        // Check if the 'setup_user' connection exists in the database configuration
        if (!Config::has('database.connections.setup_user')) {
            $this->command->info("Skipping GrantRevokePermissionsSeeder: 'setup_user' connection does not exist.");
            return;
        }

        // Check if the 'setup_user' database user exists and has the required permissions
        try {
            // Test the connection by running a simple query
            DB::connection('setup_user')->select('SELECT 1');
        } catch (QueryException $e) {
            // If the connection fails, log the error and skip the seeder
            $this->command->error("Skipping GrantRevokePermissionsSeeder: 'setup_user' connection failed. Error: " . $e->getMessage());
            return;
        }

        // Get the database name from the .env file
        $databaseName = config('database.connections.mysql.database');

        // Grant access to all tables except financial_data and privacy_data
        try {
            DB::connection('setup_user')->statement("GRANT SELECT, INSERT, UPDATE, DELETE ON $databaseName.* TO 'medior'@'%'");
            DB::connection('setup_user')->statement("GRANT SELECT, INSERT, UPDATE, DELETE ON $databaseName.* TO 'junior'@'%'");
            DB::connection('setup_user')->statement("GRANT SELECT, INSERT, UPDATE, DELETE ON $databaseName.subscriptions TO 'medior'@'%'");
            DB::connection('setup_user')->statement("GRANT SELECT, INSERT, UPDATE, DELETE ON $databaseName.subscriptions TO 'junior'@'%'");
            DB::connection('setup_user')->statement("GRANT SELECT, INSERT, UPDATE, DELETE ON $databaseName.users TO 'junior'@'%'");
            // Flush privileges to apply changes
            DB::connection('setup_user')->statement("FLUSH PRIVILEGES");
        } catch (QueryException $e) {
            $this->command->error("Error granting permissions: " . $e->getMessage());
        }

        // Revoke access to specific tables
        try {
            DB::connection('setup_user')->statement("REVOKE SELECT, INSERT, UPDATE, DELETE ON $databaseName.subscriptions FROM 'medior'@'%'");
        } catch (QueryException $e) {
            // Handle the exception if the user doesn't have the privileges
            if ($e->getCode() === '42000') {
                $this->command->info("No grants found for 'medior'@'%' on $databaseName.subscriptions");
            } else {
                throw $e; // Re-throw the exception if it's not related to missing grants
            }
        }

        try {
            DB::connection('setup_user')->statement("REVOKE SELECT, INSERT, UPDATE, DELETE ON $databaseName.subscriptions FROM 'junior'@'%'");
            DB::connection('setup_user')->statement("REVOKE SELECT, INSERT, UPDATE, DELETE ON $databaseName.users FROM 'junior'@'%'");
            // Flush privileges to apply changes
            DB::connection('setup_user')->statement("FLUSH PRIVILEGES");
        } catch (QueryException $e) {
            $this->command->error("Error revoking permissions: " . $e->getMessage());
        }

        // Drop the 'setup_user' if it exists
        try {
            DB::connection('setup_user')->statement("CALL DropSetupUser()");
            $this->command->info("User 'setup_user'@'%' has been dropped successfully.");
        } catch (QueryException $e) {
            $this->command->error("Failed to drop 'setup_user'@'%': " . $e->getMessage());
        }
    }
}