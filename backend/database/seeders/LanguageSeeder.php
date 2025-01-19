<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('languages')->insert([
            ['id' => 1, 'name' => 'English'],
            ['id' => 2, 'name' => 'Dutch'],
            ['id' => 3, 'name' => 'Spanish'],
            ['id' => 4, 'name' => 'French'],
            ['id' => 5, 'name' => 'German'],
        ]);        
    }
}
