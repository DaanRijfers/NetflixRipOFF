<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the admin user
        User::factory()->admin()->create();

        User::factory()
            ->count(10)
            ->state(function () {
                return ['email' => fake()->unique()->domainWord() . '@lotr.com'];
            })
            ->create();
    }
}
