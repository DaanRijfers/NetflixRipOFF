<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@admin.nl',
            'password' => bcrypt('qwerty'),
        ]);

        // Attach role with ID 1 (e.g., Admin role)
        $user->roles()->attach(1);
    }
}
