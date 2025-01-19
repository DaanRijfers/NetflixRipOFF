<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscriptions')->insert([
            ['id' => 1, 'price' => 7.99, 'quality' => 'SD'],
            ['id' => 2, 'price' => 10.99, 'quality' => 'HD'],
            ['id' => 3, 'price' => 13.99, 'quality' => 'UHD'],
        ]);        
    }
}
