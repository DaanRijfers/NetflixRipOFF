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
            return $this->respondWithError('An error has occured. Please try again later', 500, $request);
        }
    }
}