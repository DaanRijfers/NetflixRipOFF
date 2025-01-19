<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    // Get all profiles
    public function index(Request $request)
    {
        try {
            $profiles = DB::select('CALL GetAllProfiles()');
            return $this->respond(['message' => 'Profiles fetched successfully!', 'profiles' => $profiles], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }

    // Create new profile
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'media_preference' => 'required|in:MOVIE,EPISODE',
                'language_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->respondWithError('Incorrect input provided', 422, $request);
            }

            // Create the profile using stored procedure
            DB::statement('CALL CreateProfile(?, ?, ?, ?)', [
                $request->user()->id,
                $request->name,
                $request->media_preference,
                $request->language_id,
            ]);

            return $this->respond(['message' => 'Profile created successfully!'], 201, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }

    // Get specific profile
    public function show(Request $request, $profile_id)
    {
        try {
            $profile = DB::select('CALL GetProfileById(?)', [$profile_id]);
            if (empty($profile)) {
                return $this->respondWithError('Profile not found', 404, $request);
            }
            return $this->respond(['message' => 'Profile fetched successfully!', 'profile' => $profile[0]], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }

    // Update profile information
    public function update(Request $request, $profile_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|max:255',
                'media_preference' => 'sometimes|in:MOVIE,EPISODE',
                'language_id' => 'sometimes|exists:languages,id',
            ]);

            if ($validator->fails()) {
                return $this->respondWithError('Incorrect input provided', 422, $request);
            }

            // Update the profile using stored procedure
            DB::statement('CALL UpdateProfile(?, ?)', [
                $profile_id,
                json_encode($request->only(['name', 'media_preference', 'language_id'])),
            ]);

            return $this->respond(['message' => 'Profile updated successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }

    // Delete profile
    public function destroy(Request $request, $profile_id)
    {
        try {
            DB::statement('CALL DeleteProfile(?)', [$profile_id]);
            return $this->respond(['message' => 'Profile deleted successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }

    // Get profiles for the currently authenticated user
    public function getCurrentUserProfiles(Request $request)
    {
        try {
            $user = $request->user();
            $profiles = DB::select('CALL GetProfilesByUserId(?)', [$user->id]);

            if (empty($profiles)) {
                DB::statement('CALL CreateProfile(?, ?, ?, ?)', [
                    $user->id,
                    'Default Profile',
                    'MOVIE',
                    1,
                ]);
                $profiles = DB::select('CALL GetProfilesByUserId(?)', [$user->id]);
            }

            return $this->respond(['message' => 'Profiles fetched successfully!', 'profiles' => $profiles], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }

    // Get the favorite content of the currently authenticated user
    public function getFavoriteContent(Request $request)
    {
        try {
            $user = $request->user();
            $favoriteContent = DB::select('CALL GetFavoriteContentByUserId(?)', [$user->id]);

            return $this->respond(['message' => 'Favorite content fetched successfully!', 'favoriteContent' => $favoriteContent], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }

    // Get profile with genres
    public function getProfileWithGenres(Request $request, $profile_id)
    {
        try {
            $profile = Profile::with('genres')->findOrFail($profile_id);
            return $this->respond(['message' => 'Profile with genres fetched successfully!', 'profile' => $profile], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }

    // Add genre preference to a profile
    public function addGenrePreference(Request $request, $profile_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'genre_id' => 'required|exists:genres,id',
            ]);

            if ($validator->fails()) {
                return $this->respondWithError('Incorrect input provided', 422, $request);
            }

            $profile = Profile::findOrFail($profile_id);
            $profile->genres()->attach($request->genre_id);

            return $this->respond(['message' => 'Genre preference added successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }

    // Remove genre preference from a profile
    public function removeGenrePreference(Request $request, $profile_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'genre_id' => 'required|exists:genres,id',
            ]);

            if ($validator->fails()) {
                return $this->respondWithError('Incorrect input provided', 422, $request);
            }

            $profile = Profile::findOrFail($profile_id);
            $profile->genres()->detach($request->genre_id);

            return $this->respond(['message' => 'Genre preference removed successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occurred. Please try again later', 500, $request);
        }
    }
}
