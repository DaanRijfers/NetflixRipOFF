<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    // Get all content
    public function index()
    {
        return response()->json(Content::all());
    }

    // Get specific content
    public function show($content_id)
    {
        $content = Content::findOrFail($content_id);
        return response()->json($content);
    }

    // Get recommendations
    public function recommendations()
    {
        // Logic for content recommendations
        return response()->json(Content::all()->random(5)); // Example of random recommendations
    }

    // Search content
    public function search(Request $request)
    {
        $query = $request->get('query');
        $content = Content::where('title', 'like', "%$query%")->get();
        return response()->json($content);
    }
}

