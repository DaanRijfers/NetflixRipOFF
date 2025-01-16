<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    // Get all profiles
    public function index(Request $request)
    {
        // Fetch profiles logic here
        $profiles = []; // Replace with actual data fetching logic
        return response()->json([
            'message' => 'Profiles fetched successfully!',
            'profiles' => $profiles,
        ]);
    }

    // Create new profile
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'favorite_animal' => 'required|string', 
                'media_preference' => 'required|in:MOVIE,EPISODE',
                'language_id' => 'required|integer',
                'profile_picture' => 'nullable|string', 
            ]);

            if ($validator->fails()) {
                return $this->respondWithError(422, $request, $validator->errors());
            }

            // Convert base64 image to binary
            $profilePictureBinary = null;
            if ($request->profile_picture) {
                $profilePictureBinary = base64_decode($request->profile_picture);
            }

            // Create the profile
            $profile = Profile::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'favorite_animal' => $request->favorite_animal, 
                'media_preference' => $request->media_preference,
                'language_id' => $request->language_id,
                'profile_picture' => $profilePictureBinary, 
            ]);

            return $this->respond(['message' => 'Profile created successfully!', 'profile' => $profile], 201, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Get specific profile
    public function show(Request $request, $profile_id)
    {
        try {
            $profile = Profile::findOrFail($profile_id);
            return $this->respond(['message' => 'Profile fetched successfully!', 'profile' => $profile], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(404, $request);
        }
    }

    // Update profile information
    public function update(Request $request, $profile_id)
    {
        try {
            $profile = Profile::findOrFail($profile_id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|max:255',
                'favorite_animal' => 'sometimes|string', 
                'media_preference' => 'sometimes|in:MOVIE,EPISODE',
                'language_id' => 'sometimes|exists:languages,id',
                'profile_picture' => 'nullable|string', 
            ]);

            if ($validator->fails()) {
                return $this->respondWithError(422, $request, $validator->errors());
            }

            // Convert base64 image to binary
            $profilePictureBinary = null;
            if ($request->profile_picture) {
                $profilePictureBinary = base64_decode($request->profile_picture);
            }

            // Update the profile
            $profile->fill([
                'name' => $request->name,
                'favorite_animal' => $request->favorite_animal,
                'media_preference' => $request->media_preference,
                'language_id' => $request->language_id,
                'profile_picture' => $profilePictureBinary, 
            ])->save();

            return $this->respond(['message' => 'Profile updated successfully!', 'profile' => $profile], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Delete profile
    public function destroy(Request $request, $profile_id)
    {
        try {
            $profile = Profile::findOrFail($profile_id);
            $profile->delete();
            return $this->respond(['message' => 'Profile deleted successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Get profiles for the currently authenticated user
    public function getCurrentUserProfiles(Request $request)
    {
        try {
            // Get the authenticated user
            $user = $request->user();

            // Fetch all profiles associated with the user
            $profiles = $user->profiles;

            // If no profiles exist, create a default one
            if ($profiles->isEmpty()) {
                $profile = Profile::create([
                    'user_id' => $user->id,
                    'name' => 'Default Profile', // Default name
                    'profile_picture' => null, // No profile picture by default
                    'date_of_birth' => now()->subYears(20)->toDateString(), // Default date of birth
                    'language_id' => 1, // Default language ID
                ]);
                $profiles->push($profile);
            }

            return response()->json([
                'message' => 'Profiles fetched successfully!',
                'profiles' => $profiles,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching profiles: ' . $e->getMessage());
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Get the favorite content of the currently authenticated user
    public function getFavoriteContent(Request $request)
    {
        try {
            // Get the authenticated user
            $user = $request->user();

            // Fetch the favorite content associated with the user
            $favoriteContent = $user->favoriteContent;

            return $this->respond(['message' => 'Favorite content fetched successfully!', 'favoriteContent' => $favoriteContent], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Create a new profile for the authenticated user
    public function createProfile(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'profile_picture' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Allow image uploads
                'date_of_birth' => 'required|date',
                'language_id' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return $this->respondWithError(422, $request, $validator->errors());
            }

            // Handle profile picture upload
            $profilePictureBinary = null;
            if ($request->hasFile('profile_picture')) {
                $profilePictureBinary = file_get_contents($request->file('profile_picture')->getRealPath());
            }

            // Create the profile
            $profile = Profile::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'profile_picture' => $profilePictureBinary,
                'date_of_birth' => $request->date_of_birth,
                'language_id' => $request->language_id,
            ]);

            return $this->respond(['message' => 'Profile created successfully!', 'profile' => $profile], 201, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Get profile with genres
    public function getProfileWithGenres(Request $request, $profile_id)
    {
        try {
            // Fetch the profile with its genre preferences
            $profile = Profile::with('genres')->findOrFail($profile_id);

            return $this->respond(['message' => 'Profile with genres fetched successfully!', 'profile' => $profile], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Add genre preference to a profile
    public function addGenrePreference(Request $request, $profile_id)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'genre_id' => 'required|exists:genres,id',
            ]);

            if ($validator->fails()) {
                return $this->respondWithError(422, $request, $validator->errors());
            }

            // Find the profile
            $profile = Profile::findOrFail($profile_id);

            // Attach the genre to the profile
            $profile->genres()->attach($request->genre_id);

            return $this->respond(['message' => 'Genre preference added successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Remove genre preference from a profile
    public function removeGenrePreference(Request $request, $profile_id)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'genre_id' => 'required|exists:genres,id',
            ]);

            if ($validator->fails()) {
                return $this->respondWithError(422, $request, $validator->errors());
            }

            // Find the profile
            $profile = Profile::findOrFail($profile_id);

            // Detach the genre from the profile
            $profile->genres()->detach($request->genre_id);

            return $this->respond(['message' => 'Genre preference removed successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }
}