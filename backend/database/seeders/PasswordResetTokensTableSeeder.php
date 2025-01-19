<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PasswordResetTokensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if the `users` table exists
        if (Schema::hasTable('users')) {
            // Fetch all user emails from the `users` table
            $users = DB::table('users')->select('email')->get();

            // Insert a reset token for each user
            foreach ($users as $user) {
                DB::table('password_reset_tokens')->insert([
                    'email' => $user->email,
                    'token' => Hash::make(Str::random(60)), // Securely hash the token
                    'created_at' => now(),
                ]);
            }
        } else {
            $this->command->info('Users table does not exist. Seeder skipped.');
        }
    }
}
