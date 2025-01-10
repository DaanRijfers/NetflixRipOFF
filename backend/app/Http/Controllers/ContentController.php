<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

class ContentController extends Controller
{
    // Get all content
    public function index(Request $request)
    {
        try {
            // Fetch series data directly as-is (no need for aggregation)
            $series = DB::table('series_with_episodes')
                ->select('series_title', 'total_seasons', 'total_episodes')
                ->paginate(10); // Optional: Add pagination if needed

            // Fetch movies data with their qualities
            $movies = DB::table('movies_with_quality')
                ->select('movie_id', 'movie_title', 'qualities')
                ->paginate(10);

            // Prepare data
            $data = [
                'series' => $series,
                'movies' => $movies,
            ];

            // Use the respond function
            return $this->respond(['message' => 'Succesfully fetched all content', 'content' => $data], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Get specific content
    public function show(Request $request, $content_id)
    {
        try {
            $content = Media::findOrFail($content_id);

            // Use the respond function
            return $this->respond(['message' => 'Succesfully fetched content', 'content' => $content->toArray()], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Get recommendations
    public function recommendations(Request $request)
    {
        try {
            $recommendations = Media::all()->random(5); // Example of random recommendations

            // Use the respond function
            return $this->respond(['message' => 'Succesfully fetched reccomendations', 'content' => $recommendations->toArray()], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Search content
    public function search(Request $request)
    {
        try {
            $query = $request->get('query');
            $content = Media::where('title', 'like', "%$query%")->get();

            // Use the respond function
            return $this->respond(['message' => 'Succesfully completed search', 'content' => $content->toArray()], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }
}
