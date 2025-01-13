<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class GrantRevokePermissionsSeeder extends Seeder
{
    public function run()
    {
        // Get the database name from the .env file
        $databaseName = config('database.connections.mysql.database');

        // Grant access to all tables except financial_data and privacy_data
        DB::connection('setup_user')->statement("GRANT SELECT, INSERT, UPDATE, DELETE ON $databaseName.* TO 'medior'@'%'");
        DB::connection('setup_user')->statement("GRANT SELECT, INSERT, UPDATE, DELETE ON $databaseName.* TO 'junior'@'%'");
        DB::connection('setup_user')->statement("GRANT SELECT, INSERT, UPDATE, DELETE ON $databaseName.subscriptions TO 'medior'@'%'");
        DB::connection('setup_user')->statement("GRANT SELECT, INSERT, UPDATE, DELETE ON $databaseName.subscriptions TO 'junior'@'%'");
        DB::connection('setup_user')->statement("GRANT SELECT, INSERT, UPDATE, DELETE ON $databaseName.users TO 'junior'@'%'");
        // Flush privileges to apply changes
        DB::connection('setup_user')->statement("FLUSH PRIVILEGES");
        
        // Revoke access to specific tables
        try {
            DB::connection('setup_user')->statement("REVOKE SELECT, INSERT, UPDATE, DELETE ON $databaseName.subscriptions FROM 'medior'@'%'");
        } catch (QueryException $e) {
            // Handle the exception if the user doesn't have the privileges
            if ($e->getCode() === '42000') {
                // Log or handle the error as needed
                echo "No grants found for 'medior'@'%' on $databaseName.subscriptions\n";
            } else {
                throw $e; // Re-throw the exception if it's not related to missing grants
            }
        }

        DB::connection('setup_user')->statement("REVOKE SELECT, INSERT, UPDATE, DELETE ON $databaseName.subscriptions FROM 'junior'@'%'");
        DB::connection('setup_user')->statement("REVOKE SELECT, INSERT, UPDATE, DELETE ON $databaseName.users FROM 'junior'@'%'");

        // Flush privileges to apply changes
        DB::connection('setup_user')->statement("FLUSH PRIVILEGES");

        try {
            DB::connection('setup_user')->statement("CALL DropSetupUser()");
            echo "User 'setup_user'@'%' has been dropped successfully.\n";
        } catch (QueryException $e) {
            echo "Failed to drop 'setup_user'@'%': " . $e->getMessage() . "\n";
        }
        
    }
}