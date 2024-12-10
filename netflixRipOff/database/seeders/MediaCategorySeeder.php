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
            MediaCategory::create([
                'media_id' => $media->id,
                'category_id' => rand(1, 3), 
            ]);
        });
    }
}
