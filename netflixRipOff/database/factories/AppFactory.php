<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{User, Role, Subscription, Profile, ContentPreference, Movie, Episode, Genre, ViewingGuide, ProfileWatchtime, ProfilePreference};

class AppFactory extends Factory
{
    public function definition()
    {
        // No direct usage; individual factories go here
    }

    // Role Factory
    public static function createRoles()
    {
        if (Role::count() === 0) {
            return Role::insert([
                ['name' => 'Admin'],
                ['name' => 'User'],
            ]);
        }
    }

    // Subscription Factory
    public static function createSubscriptions()
    {
        if (Subscription::count() < 3) {
            return Subscription::factory()->count(3 - Subscription::count())->create();
        }
    }

    // User Factory
    public static function createUsers()
    {
        $existingUsersCount = User::count();
        $usersToCreate = max(0, 50 - $existingUsersCount); // Ensure only 50 users exist

        if ($usersToCreate > 0) {
            return User::factory()->count($usersToCreate)->create()->each(function ($user) {
                $roleId = $user->email === 'admin@admin.nl' ? 1 : 2; // Assign Admin or User role
                if (!$user->roles()->where('role_id', $roleId)->exists()) {
                    $user->roles()->attach($roleId);
                }
            });
        }
    }

    // Profile Factory
    public static function createProfiles()
    {
        $existingProfilesCount = Profile::count();
        $profilesToCreate = max(0, 100 - $existingProfilesCount);

        if ($profilesToCreate > 0) {
            return Profile::factory()->count($profilesToCreate)->create();
        }
    }

    // Movie Factory
    public static function createMovies()
    {
        $existingMoviesCount = Movie::count();
        $moviesToCreate = max(0, 20 - $existingMoviesCount);

        if ($moviesToCreate > 0) {
            return Movie::factory()->count($moviesToCreate)->create();
        }
    }

    // Episode Factory
    public static function createEpisodes()
    {
        $existingEpisodesCount = Episode::count();
        $episodesToCreate = max(0, 50 - $existingEpisodesCount);

        if ($episodesToCreate > 0) {
            return Episode::factory()->count($episodesToCreate)->create();
        }
    }

    // Genre Factory
    public static function createGenres()
    {
        $existingGenresCount = Genre::count();
        $genresToCreate = max(0, 5 - $existingGenresCount);

        if ($genresToCreate > 0) {
            return Genre::factory()->count($genresToCreate)->create();
        }
    }

    // Viewing Guide Factory
    public static function createViewingGuides()
    {
        $existingViewingGuidesCount = ViewingGuide::count();
        $viewingGuidesToCreate = max(0, 10 - $existingViewingGuidesCount);

        if ($viewingGuidesToCreate > 0) {
            return ViewingGuide::factory()->count($viewingGuidesToCreate)->create();
        }
    }

    // Content Preference Factory
    public static function createContentPreferences()
    {
        $existingPreferencesCount = ContentPreference::count();
        $preferencesToCreate = max(0, 50 - $existingPreferencesCount);

        if ($preferencesToCreate > 0) {
            return ContentPreference::factory()->count($preferencesToCreate)->create();
        }
    }

    // Viewing Guide and Movie Pivot
    public static function createViewingGuideMovies()
    {
        $viewingGuides = ViewingGuide::all();
        $movies = Movie::all();

        $viewingGuides->each(function ($guide) use ($movies) {
            $existingMovies = $guide->movies()->pluck('movies.id')->toArray();
            $newMovies = $movies->whereNotIn('id', $existingMovies)->random(rand(1, 5))->pluck('id')->toArray();

            if (!empty($newMovies)) {
                $guide->movies()->attach($newMovies);
            }
        });
    }
    
    public static function createViewingGuideEpisodes()
    {
        $viewingGuides = ViewingGuide::all();
        $episodes = Episode::all();
    
        $viewingGuides->each(function ($guide) use ($episodes) {
            // Specify the table name for the `id` column
            $existingEpisodes = $guide->episodes()->pluck('episodes.id')->toArray();
            $newEpisodes = $episodes->whereNotIn('id', $existingEpisodes)->random(rand(1, 10))->pluck('id')->toArray();
    
            if (!empty($newEpisodes)) {
                $guide->episodes()->attach($newEpisodes);
            }
        });
    }
    

    // Profile Watchtime Factory
    public static function createProfileWatchtimes()
    {
        $existingWatchtimesCount = ProfileWatchtime::count();
        $watchtimesToCreate = max(0, 50 - $existingWatchtimesCount);

        if ($watchtimesToCreate > 0) {
            return ProfileWatchtime::factory()->count($watchtimesToCreate)->create();
        }
    }

    // Profile Preferences Factory
    public static function createProfilePreferences()
    {
        $existingPreferencesCount = ProfilePreference::count();
        $preferencesToCreate = max(0, 50 - $existingPreferencesCount);

        if ($preferencesToCreate > 0) {
            return ProfilePreference::factory()->count($preferencesToCreate)->create();
        }
    }
}
