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
            'qualities' => QualitySeeder::class,
            'subscriptions' => SubscriptionSeeder::class,
            'roles' => RoleSeeder::class,
            'media_types' => MediaTypeSeeder::class,
            'genres' => GenreSeeder::class,
            'restrictions' => RestrictionSeeder::class,
            'languages' => LanguageSeeder::class,
            'users' => UserSeeder::class,
            'user_roles' => UserRoleSeeder::class,
            'profiles' => ProfileSeeder::class,
            'media' => MediaSeeder::class,
            'watchlists' => WatchlistSeeder::class,
            'profile_histories' => ProfileHistorySeeder::class,
            'media_genres' => MediaGenreSeeder::class,
            'media_restrictions' => MediaRestrictionSeeder::class,
            'profile_genre_preferences' => ProfileGenrePreferenceSeeder::class,
            'profile_restriction_preferences' => ProfileRestrictionPreferenceSeeder::class,
            'user_invitations' => UserInvitationSeeder::class,
            'trial_periods' => TrialPeriodSeeder::class,
            'subtitles' => SubtitlesSeeder::class,
            'media_qualities' => MediaQualitySeeder::class,
        ];

        foreach ($seeders as $table => $seeder) {
            if (DB::table($table)->count() === 0) {
                $this->call($seeder);
                $this->command->info("Seeded: {$seeder}");
            } else {
                $this->command->info("Skipped: {$seeder} (table '{$table}' is not empty)");
            }
        }

        $this->call([
            MediaWithDetailsViewSeeder::class,
            UserMediaQualitiesViewSeeder::class,
            MediaAvailabilityByQualityViewSeeder::class,
            SeriesWithEpisodesViewSeeder::class,
        ]);

        $this->command->info('Views created successfully.');
    }
}
