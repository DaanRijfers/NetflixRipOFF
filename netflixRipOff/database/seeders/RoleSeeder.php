<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the roles already exist
        if (Role::where('name', 'Admin')->doesntExist()) {
            Role::create(['name' => 'Admin']);
        }

        if (Role::where('name', 'User')->doesntExist()) {
            Role::create(['name' => 'User']);
        }
    }
}
