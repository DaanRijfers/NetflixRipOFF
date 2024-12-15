<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QualitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('qualities')->insert([
            ['id' => 1, 'name' => 'SD'],
            ['id' => 2, 'name' => 'HD'],
            ['id' => 3, 'name' => 'UHD'],
        ]);        
    }
}
