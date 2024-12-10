<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestrictionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('restrictions')->insert([
            ['id' => 1, 'name' => 'Violence'],
            ['id' => 2, 'name' => 'Strong Language'],
            ['id' => 3, 'name' => 'Drug Use'],
            ['id' => 4, 'name' => '16+'],
            ['id' => 5, 'name' => '18+'],
        ]);
    }
}
