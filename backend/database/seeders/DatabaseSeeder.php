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
            \Database\Seeders\views\MediaWithDetailsViewSeeder::class,
            \Database\Seeders\views\UserMediaQualitiesViewSeeder::class,
            \Database\Seeders\views\MediaAvailabilityByQualityViewSeeder::class,
            \Database\Seeders\views\SeriesWithEpisodesViewSeeder::class,
            \Database\Seeders\views\SeriesEpisodesWithFilesViewSeeder::class,
            \Database\Seeders\views\MoviesWithQualityViewSeeder::class,
        ]);

        $this->command->info('Views created successfully.');

        // Call stored procedure seeders
        $this->call([
            \Database\Seeders\procedures\UserStoredProcedureSeeder::class,
            \Database\Seeders\procedures\SubscriptionStoredProcedureSeeder::class,
            \Database\Seeders\procedures\ProfileStoredProcedureSeeder::class,
            \Database\Seeders\procedures\ContentStoredProcedureSeeder::class,
            \Database\Seeders\procedures\ResetPasswordStoredProcedureSeeder::class, // Added ResetPasswordStoredProcedureSeeder
            \Database\Seeders\procedures\RegisterUserStoredProcedureSeeder::class, // Added RegisterUserStoredProcedureSeeder
            \Database\Seeders\procedures\LoginUserStoredProcedureSeeder::class, // Added LoginUserStoredProcedureSeeder
        ]);

        $this->command->info('Stored procedures created successfully.');
    }

    /**
     * Check if a stored procedure exists.
     *
     * @param string $procedureName
     * @return bool
     */
    private function procedureExists(string $procedureName): bool
    {
        $result = DB::selectOne("
            SELECT COUNT(*) AS count
            FROM information_schema.ROUTINES
            WHERE ROUTINE_TYPE = 'PROCEDURE' AND ROUTINE_NAME = ?
        ", [$procedureName]);

        return $result->count > 0;
    }
}