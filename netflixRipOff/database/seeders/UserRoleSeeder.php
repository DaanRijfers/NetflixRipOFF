<?php

namespace Database\Seeders;

use App\Models\User; 
use App\Models\UserRole; 
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function ($user) {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $user->email === 'admin@admin.com' ? 1 : 2,
            ]);
        });
    }
}
