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
            return; // Exit the seeder early
        }

        // Check if the 'setup_user' database user exists and has the required permissions
        try {
            // Test the connection by running a simple query
            DB::connection('setup_user')->select('SELECT 1');
        } catch (QueryException $e) {
            // If the connection fails, log the error and skip the seeder
            $this->command->error("Skipping GrantRevokePermissionsSeeder: 'setup_user' connection failed. Error: " . $e->getMessage());
            return; // Exit the seeder early
        }

        // If we reach here, the 'setup_user' connection is valid
        $this->command->info("'setup_user' connection is valid. Proceeding with permissions setup...");

        // Get the database name from the .env file
        $databaseName = config('database.connections.mysql.database');

        // Revoke all permissions first
        try {
            DB::connection('setup_user')->statement("REVOKE ALL PRIVILEGES ON $databaseName.* FROM 'medior'@'%'");
            DB::connection('setup_user')->statement("REVOKE ALL PRIVILEGES ON $databaseName.* FROM 'junior'@'%'");
            // Flush privileges to apply changes
            DB::connection('setup_user')->statement("FLUSH PRIVILEGES");
            $this->command->info("All permissions revoked successfully.");
        } catch (QueryException $e) {
            $this->command->error("Error revoking all permissions: " . $e->getMessage());
        }

        // Grant permissions one by one, excluding restricted tables
        try {
            // List of tables to grant access
            $tables = [
                'users',
                'subscriptions',
                'genres',
                'languages',
                'media',
                'media_availability_by_quality',
                'media_genres',
                'media_qualities',
                'media_restrictions',
                'media_with_details',
                'movies_with_quality',
                'password_reset_tokens',
                'profile_genre_preferences',
                'profile_histories',
                'profile_restriction_preferences',
                'profiles',
                'restrictions',
                'series_episodes_with_files',
                'series_with_episodes',
                'subtitles',
                'trial_periods',
                'user_invitations',
                'user_with_subscription',
                'watchlists',
            ];

            // Grant permissions to 'medior' user (excluding subscriptions and users)
            foreach ($tables as $table) {
                if ($table !== 'subscriptions') { // Exclude 'subscriptions' and 'users' for medior
                    DB::connection('setup_user')->statement("GRANT SELECT, INSERT, UPDATE, DELETE ON $databaseName.$table TO 'medior'@'%'");
                }
            }

            // Grant permissions to 'junior' user (excluding subscriptions and users)
            foreach ($tables as $table) {
                if ($table !== 'subscriptions' && $table !== 'users') { // Exclude 'subscriptions' and 'users' for junior
                    DB::connection('setup_user')->statement("GRANT SELECT, INSERT, UPDATE, DELETE ON $databaseName.$table TO 'junior'@'%'");
                }
            }

            // Flush privileges to apply changes
            DB::connection('setup_user')->statement("FLUSH PRIVILEGES");
            $this->command->info("Permissions granted successfully.");
        } catch (QueryException $e) {
            $this->command->error("Error granting permissions: " . $e->getMessage());
        }

        // Drop the 'setup_user' if it exists
        try {
            DB::connection('setup_user')->statement("DROP USER IF EXISTS 'setup_user'@'%'");
            $this->command->info("User 'setup_user'@'%' has been dropped successfully.");
        } catch (QueryException $e) {
            $this->command->error("Failed to drop 'setup_user'@'%': " . $e->getMessage());
        }
    }
}