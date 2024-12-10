<?php

namespace Database\Seeders;

use App\Models\TrialPeriod;
use App\Models\User;
use Illuminate\Database\Seeder;

class TrialPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function ($user) {
            TrialPeriod::create([
                'user_id' => $user->id,
                'start_date' => now()->subDays(rand(1, 7)),
                'end_date' => now(),
            ]);
        });
    }
}
