<?php

namespace App\Http\Controllers;

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
            // Fetch series data using stored procedure
            $series = DB::select('CALL GetAllSeries()');

            // Fetch movies data using stored procedure
            $movies = DB::select('CALL GetAllMovies()');

            // Merge series and movies into a single content array
            $content = array_merge($series, $movies);

            // Use the respond function
            return $this->respond(['message' => 'Successfully fetched all content', 'content' => $content], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }

    // Get specific content
    public function show(Request $request, $content_id)
    {
        try {
            // Fetch specific content using stored procedure
            $content = DB::select('CALL GetContentById(?)', [$content_id]);

            // Use the respond function
            return $this->respond(['message' => 'Successfully fetched content', 'content' => $content], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }

    // Get recommendations
    public function recommendations(Request $request)
    {
        try {
            // Fetch recommendations using stored procedure
            $recommendations = DB::select('CALL GetRecommendations()');

            // Use the respond function
            return $this->respond(['message' => 'Successfully fetched recommendations', 'content' => $recommendations], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }

    // Search content
    public function search(Request $request)
    {
        try {
            $query = $request->get('query');
            // Search content using stored procedure
            $content = DB::select('CALL SearchContent(?)', [$query]);

            // Use the respond function
            return $this->respond(['message' => 'Successfully completed search', 'content' => $content], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }
}
