<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ContentController extends Controller
{
    // Get all content
    public function index(Request $request)
    {
        // Fetch series data directly as-is (no need for aggregation)
        $series = DB::table('series_with_episodes')
            ->select('series_title', 'total_seasons', 'total_episodes')
            ->paginate(10); // Optional: Add pagination if needed

        // Fetch movies data with their qualities
        $movies = DB::table('movies_with_quality')
            ->select('movie_id', 'movie_title', 'qualities')
            ->paginate(10);

        // Check the Accept header
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader == 'text/csv') {
            // Convert data to CSV format
            $csvData = $this->convertToCsv([
                'series' => $series->items(),
                'movies' => $movies->items(),
            ]);

            return Response::make($csvData, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="content.csv"',
            ]);
        }

        // Return data as JSON
        return response()->json([
            'series' => $series,
            'movies' => $movies,
        ]);
    }

    // Get specific content
    public function show(Request $request, $content_id)
    {
        $content = Media::findOrFail($content_id);

        // Check the Accept header
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader == 'text/csv') {
            // Convert data to CSV format
            $csvData = $this->convertToCsv([$content]);

            return Response::make($csvData, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="content.csv"',
            ]);
        }

        return response()->json($content);
    }

    // Get recommendations
    public function recommendations(Request $request)
    {
        $recommendations = Media::all()->random(5); // Example of random recommendations

        // Check the Accept header
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader == 'text/csv') {
            // Convert data to CSV format
            $csvData = $this->convertToCsv($recommendations);

            return Response::make($csvData, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="recommendations.csv"',
            ]);
        }

        return response()->json($recommendations);
    }

    // Search content
    public function search(Request $request)
    {
        $query = $request->get('query');
        $content = Media::where('title', 'like', "%$query%")->get();

        // Check the Accept header
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader == 'text/csv') {
            // Convert data to CSV format
            $csvData = $this->convertToCsv($content);

            return Response::make($csvData, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="search_results.csv"',
            ]);
        }

        return response()->json($content);
    }

    // Helper function to convert data to CSV format
    private function convertToCsv($data)
    {
        $csv = '';
        $header = false;

        foreach ($data as $row) {
            if (!$header) {
                // Add the header row
                $csv .= implode(',', array_keys((array)$row)) . "\n";
                $header = true;
            }
            // Add the data rows
            $csv .= implode(',', array_values((array)$row)) . "\n";
        }

        return $csv;
    }
}
