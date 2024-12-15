<?php

namespace Database\Seeders;

use App\Models\Watchlist;
use App\Models\Profile;
use App\Models\Media;
use Illuminate\Database\Seeder;

class WatchlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::all()->each(function ($profile) {
            $mediaIds = Media::pluck('id')->toArray(); // Get all valid media IDs
            shuffle($mediaIds); // Shuffle the array for randomness
            $watchlistMedia = array_slice($mediaIds, 0, rand(1, 5)); // Pick 1–5 unique media IDs

            foreach ($watchlistMedia as $mediaId) {
                Watchlist::create([
                    'profile_id' => $profile->id,
                    'media_id' => $mediaId,
                ]);
            }
        });
    }
}
