<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContentController extends Controller
{
    // Get all content
    public function index()
    {
        // Fetch series data with aggregated seasons and episodes, paginated
        $series = DB::table('series_with_episodes')
            ->select('series_title', 'total_seasons', 'total_episodes')
            ->paginate(6); 

        // Fetch movie data with their qualities, paginated
        $movies = DB::table('movies_with_quality')
            ->select('movie_id', 'movie_title', 'qualities')
            ->paginate(6, ['*'], 'movies_page'); 

        // Return data to an Inertia page
        return Inertia::render('Content/Index', [
            'series' => $series,
            'movies' => $movies,
        ]);
    }

    // Get specific content
    public function show($content_id)
    {
        $content = Media::findOrFail($content_id);
        return response()->json($content);
    }

    // Get recommendations
    public function recommendations()
    {
        // Logic for content recommendations
        return response()->json(Media::all()->random(5)); // Example of random recommendations
    }

    // Search content
    public function search(Request $request)
    {
        $query = $request->get('query');
        $content = Media::where('title', 'like', "%$query%")->get();
        return response()->json($content);
    }
}

