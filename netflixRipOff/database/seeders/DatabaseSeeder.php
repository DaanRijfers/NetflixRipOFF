<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\AppFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Order matters to satisfy foreign key constraints
        AppFactory::createRoles();
        AppFactory::createSubscriptions();
        $this->call([
            UserSeeder::class,
        ]);
        AppFactory::createGenres();
        AppFactory::createContentPreferences();
        AppFactory::createUsers();
        AppFactory::createProfiles();
        AppFactory::createMovies();
        AppFactory::createEpisodes();
        AppFactory::createViewingGuides();
        AppFactory::createViewingGuideMovies();
        AppFactory::createViewingGuideEpisodes();
        AppFactory::createProfileWatchtimes();
        AppFactory::createProfilePreferences();
    }
}
