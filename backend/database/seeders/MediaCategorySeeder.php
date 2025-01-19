<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\MediaCategory;
use Illuminate\Database\Seeder;

class MediaCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Media::all()->each(function ($media) {
            // Generate 1–3 random category IDs
            $categories = range(1, 3); // Assuming you have 3 categories
            shuffle($categories); // Shuffle to randomize the order
            $selectedCategories = array_slice($categories, 0, rand(1, 3)); // Pick 1–3 categories

            foreach ($selectedCategories as $categoryId) {
                MediaCategory::create([
                    'media_id' => $media->id,
                    'category_id' => $categoryId,
                ]);
            }
        });
    }
}
