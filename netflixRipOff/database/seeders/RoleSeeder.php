<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Admin'], // temp
            ['id' => 2, 'name' => 'User'],
            ['id' => 3, 'name' => 'Junior'],
            ['id' => 4, 'name' => 'Medior'],
            ['id' => 5, 'name' => 'Senior'],
        ]);        
    }
}
