<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genres')->insert([
            ['id' => 1, 'name' => 'Action'],
            ['id' => 2, 'name' => 'Drama'],
            ['id' => 3, 'name' => 'Comedy'],
            ['id' => 4, 'name' => 'Fantasy'],
        ]);        
    }
}
