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

        // Prepare data
        $data = [
            'series' => $series,
            'movies' => $movies,
        ];

        // Use the respond function
        return $this->respond($data, 200, $request);
    }

    // Get specific content
    public function show(Request $request, $content_id)
    {
        $content = Media::findOrFail($content_id);

        // Use the respond function
        return $this->respond($content->toArray(), 200, $request);
    }

    // Get recommendations
    public function recommendations(Request $request)
    {
        $recommendations = Media::all()->random(5); // Example of random recommendations

        // Use the respond function
        return $this->respond($recommendations->toArray(), 200, $request);
    }

    // Search content
    public function search(Request $request)
    {
        $query = $request->get('query');
        $content = Media::where('title', 'like', "%$query%")->get();

        // Use the respond function
        return $this->respond($content->toArray(), 200, $request);
    }

    // Helper function to respond in JSON or CSV format
    private function respond(array $data, int $status, Request $request)
    {
        $acceptHeader = $request->header('Accept');

        switch ($acceptHeader) {
            case 'text/csv':
                $csvData = $this->convertToCsv($data);
                return response($csvData, $status)->header('Content-Type', 'text/csv');
            case 'text/xml':
                $xmlData = $this->arrayToXml($data);
                return response($xmlData, $status)->header('Content-Type', 'text/xml');
            default:
                return response()->json($data, $status);
        }
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

    // Helper function to convert array to XML
    private function arrayToXml(array $data, \SimpleXMLElement $xmlData = null): string
    {
        if ($xmlData === null) {
            $xmlData = new \SimpleXMLElement('<users/>');
        }

        foreach ($data as $key => $value) {
            $key = is_numeric($key) ? "item$key" : $key;
            if (is_array($value)) {
                $subnode = $xmlData->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xmlData->addChild($key, htmlspecialchars("$value"));
            }
        }

        return $xmlData->asXML();
    }
}
