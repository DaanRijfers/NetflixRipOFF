<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;

class LanguageController extends Controller
{
    // Get all languages
    public function index(Request $request)
    {
        try {
            $languages = Language::all();
            return $this->respond(['message' => 'Languages fetched successfully!', 'languages' => $languages], 200, $request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch languages!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}