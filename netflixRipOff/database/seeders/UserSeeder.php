<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Check if the admin user already exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.nl'], // Check if the user exists based on the email
            [
                'password' => bcrypt('adminpassword'), // Admin's password
                'is_blocked' => false,
                'subscription_id' => 1,
            ]
        );

        // Attach the Admin role if not already attached
        if (!$admin->roles()->where('role_id', 1)->exists()) {
            $admin->roles()->attach(1); // Assuming 1 is the Admin role ID
        }

        // Check if random users already exist
        $existingUsersCount = User::where('email', '!=', 'admin@admin.nl')->count();
        $usersToCreate = max(0, 50 - $existingUsersCount); // Ensure only 50 random users exist

        if ($usersToCreate > 0) {
            // Create the remaining users if needed
            $users = User::factory()->count($usersToCreate)->create();

            // Attach the User role to new users
            foreach ($users as $user) {
                $user->roles()->attach(2); // Assuming 2 is the User role ID
            }
        }
    }
}
