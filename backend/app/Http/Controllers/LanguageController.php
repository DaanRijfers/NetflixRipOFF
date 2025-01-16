<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;

class LanguageController extends Controller
{
    // Get all languages
    public function index()
    {
        try {
            $languages = Language::all();
            return response()->json([
                'message' => 'Languages fetched successfully!',
                'languages' => $languages,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch languages!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}