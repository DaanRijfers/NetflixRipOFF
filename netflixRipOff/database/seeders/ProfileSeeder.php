<?php

namespace Database\Seeders;

use App\Models\User; 
use App\Models\Profile; 
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function ($user) {
            Profile::factory()->count(rand(1, 4))->create(['user_id' => $user->id]);
        });
    }
}
