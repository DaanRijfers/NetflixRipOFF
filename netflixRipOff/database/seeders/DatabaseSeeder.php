<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $seeders = [
            'subscriptions' => SubscriptionSeeder::class,
            'genres' => GenreSeeder::class,
            'restrictions' => RestrictionSeeder::class,
            'languages' => LanguageSeeder::class,
            'users' => UserSeeder::class,
            'profiles' => ProfileSeeder::class,
            'media' => MediaSeeder::class,
            'watchlists' => WatchlistSeeder::class,
            'profile_histories' => ProfileHistorySeeder::class,
            'media_genres' => MediaGenreSeeder::class,
            'media_restrictions' => MediaRestrictionSeeder::class,
            'media_qualities' => MediaQualitySeeder::class,
            'profile_genre_preferences' => ProfileGenrePreferenceSeeder::class,
            'profile_restriction_preferences' => ProfileRestrictionPreferenceSeeder::class,
            'user_invitations' => UserInvitationSeeder::class,
            'trial_periods' => TrialPeriodSeeder::class,
            'subtitles' => SubtitlesSeeder::class,
        ];

        foreach ($seeders as $table => $seeder) {
            if (DB::table($table)->count() === 0) {
                $this->call($seeder);
                $this->command->info("Seeded: {$seeder}");
            } else {
                $this->command->info("Skipped: {$seeder} (table '{$table}' is not empty)");
            }
        }

        // Seed views from the 'views' folder
        $this->call([
            \Database\Seeders\Views\MediaWithDetailsViewSeeder::class,
            \Database\Seeders\Views\UserMediaQualitiesViewSeeder::class,
            \Database\Seeders\Views\MediaAvailabilityByQualityViewSeeder::class,
            \Database\Seeders\Views\SeriesWithEpisodesViewSeeder::class,
            \Database\Seeders\Views\SeriesEpisodesWithFilesViewSeeder::class,
            \Database\Seeders\Views\MoviesWithQualityViewSeeder::class,
        ]);

        $this->command->info('Views created successfully.');

         // Call stored procedure seeders
         $this->call([
            \Database\Seeders\Procedures\UserStoredProcedureSeeder::class,
            \Database\Seeders\Procedures\UserInvitationsStoredProcedureSeeder::class,
            \Database\Seeders\Procedures\WatchlistStoredProcedureSeeder::class,
        ]);

        $this->command->info('Stored procedures created successfully.');
    }
}
