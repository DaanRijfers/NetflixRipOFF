<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    // Get all profiles
    public function index(Request $request)
    {
        // Fetch profiles logic here
        $profiles = []; // Replace with actual data fetching logic
        return $this->respond(['message' => 'Profiles fetched successfully!', 'profiles' => $profiles], 200, $request);
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
                'profile_picture' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->respondWithError('Incorrect input provided', 422, $request);
            }

            // Handle profile picture (can be a URL, base64 or null)
            $profilePictureBinary = $this->decodeProfilePicture($request);

            // Create the profile
            $profile = Profile::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'media_preference' => $request->media_preference,
                'language_id' => $request->language_id,
                'profile_picture' => $profilePictureBinary,
            ]);

            $profile['profile_picture'] = url("/api/profile/" . $profile->id . "/picture");
            return $this->respond(['message' => 'Profile created successfully!', 'profile' => $profile], 201, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occured. Please try again later', 500, $request);
        }
    }

    // Get specific profile
    public function show(Request $request, $profile_id)
    {
        try {
            $profile = Profile::findOrFail($profile_id);
            $profile['profile_picture'] = url("/api/profile/" . $profile->id . "/picture");
            return $this->respond(['message' => 'Profile fetched successfully!', 'profile' => $profile], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occured. Please try again later', 500, $request);
        }
    }

    // Update profile information
    public function update(Request $request, $profile_id)
    {
        try {
            $profile = Profile::findOrFail($profile_id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|max:255',
                'media_preference' => 'sometimes|in:MOVIE,EPISODE',
                'language_id' => 'sometimes|exists:languages,id',
                'profile_picture' => 'nullable|string', // Allow Unsplash image URL or base64
            ]);

            if ($validator->fails()) {
                return $this->respondWithError('Incorrect input provided', 422, $request);
            }

            // Handle profile picture (can be a URL or base64)
            $profilePictureBinary = $this->decodeProfilePicture($request);

            // Update the profile
            $profile->fill([
                'name' => $request->name,
                'media_preference' => $request->media_preference,
                'language_id' => $request->language_id,
                'profile_picture' => $profilePictureBinary,
            ])->save();

            $profile['profile_picture'] = url("/api/profile/" . $profile->id . "/picture");
            return $this->respond(['message' => 'Profile updated successfully!', 'profile' => $profile], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occured. Please try again later', 500, $request);
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
                    'profile_picture' => $this->decodeProfilePicture($request), // No profile picture by default
                    'date_of_birth' => now()->subYears(20)->toDateString(), // Default date of birth
                    'language_id' => 1, // Default language ID
                ]);
                $profiles->push($profile);
            }

            return $this->respond(['message' => 'Profiles fetched successfully!', 'profiles' => $profiles], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occured. Please try again later', 500, $request);
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
            return $this->respondWithError('An error has occured. Please try again later', 500, $request);
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
            return $this->respondWithError('An error has occured. Please try again later', 500, $request);
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
                return $this->respondWithError('Incorrect input provided', 422, $request);
            }

            // Find the profile
            $profile = Profile::findOrFail($profile_id);

            // Attach the genre to the profile
            $profile->genres()->attach($request->genre_id);

            return $this->respond(['message' => 'Genre preference added successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError('An error has occured. Please try again later', 500, $request);
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
                return $this->respondWithError('Incorrect input provided', 422, $request);
            }

            // Find the profile
            $profile = Profile::findOrFail($profile_id);

            // Detach the genre from the profile
            $profile->genres()->detach($request->genre_id);

            return $this->respond(['message' => 'Genre preference removed successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->response('An error has occured. Please try again later', 500, $requestu);
        }
    }
    
    public function getProfilePicture(Request $request, $profile_id)
    {
        try {
            $profile = Profile::findOrFail($profile_id);
    
            $profilePictureBinary = $profile->profile_picture;
    
            if (!$profilePictureBinary) {
                return $this->response('Unable to locate profile picture', 404, $requestu);
            }
    
            // Initialize FileInfo and get MIME type
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($profilePictureBinary);
    
            return response($profilePictureBinary, 200)
                ->header('Content-Type', $mimeType);
        } catch (\Throwable $th) {
            return $this->response('An error has occured. Please try again later', 500, $requestu);
        }
    }
    

    // Fetch the PFP, decode it or get a random fox one
    private function decodeProfilePicture($request)
    {
        $profilePictureBinary = null;
        try {
            if (!$request->profile_picture) {
                $imageResponse = Http::get('https://randomfox.ca/floof/');
                if($imageResponse->successful()) {
                    $profilePictureBinary = file_get_contents($imageResponse->json()['image']);
                } else {
                    $profilePictureBinary = base64_decode("UklGRg4DAABXRUJQVlA4WAoAAAAQAAAAJwAAJwAAQUxQSA0BAAABgGptexopyyyZGe2evYBVvJKZFN0DO163mpmZNTMzyZlZLv2J6WT+Rq2MiAkgtnsFR/kHZHZdMKv1BNW+eOb5689iYbxjKHn7DNHkh5DMcBsQ2pHeQ2wbRGK9xMPJhiWsv/PH80W+eSxdvh1Rwn9FSWVGWrhaDfnlWjWAKgrXNpqmKBrwpDwgaZKkAXB4DzJU+P740yhYCXIk2Y8MU/o0/2oUdGNyygc/Zssps53+mL9kSsHqD0PWZEkCCrwMV5UVDSjwY4CiqICocwlWQJVlRQNUHWDsZHp2V5ZkFdB1GFt0IaSagZF6P1GEkBoR+oggWpIobUSQTQdBbvyIGNMhRAipx56IkUg4DSL/FgBWUDgg2gEAAPAIAJ0BKigAKAA+kUSZSqWjoiGnOApIsBIJYgDBsXVgvkcgJ17GhPQOvgidky8fiw+9VINh196O6cTVVzAMb+cWzyAWIrAAL+QcFGfDFQAAAP7/Gt3jwoEvSITLJ0xDHYvMNGzQ7nWrE7dQcTwMdJkoEw1dKk/YaiQ/f5qPDw3ymTApdmVRLHbdhqzfs+Xq3WCDHcIaKNJGG9QO4YVG/zcvZE0Hhsa4HTR9CwN8N7yP1uilT2c0Kn6PBVna3Jvm/SRzVOreOmNftymuBBqMo43xd7/A+D3Zcujc2czkC8gDd3mpm6ht9bn3KLVLM9V8lTNNJoASlcS201l1Cl6vw1d3muRjRfQdHAev8rry1jNzhFtBQkRO+8orsTPDCLE+esqhrkIwqAHf/a3oy1SHqH8bi8pA9TVmYVX0JqCMd51uZrJAgRU0dh55FGpy7XX37/mjxZlwBQzxnEdyjr7zHEI+pe7sFnFGaJvn/jQwN3QH5f+LC//Q0BeJEADeTkP3aLj3UKO5jK/6hwwdCK7qhuRXe7I37QPAKsH+eLH3s/qHBeHkuDH3muiDjqHKwcLLvwf9EitdGA7niLdLaxp47II/nXJmEE7ADX5ArTz+5XR1CR2oLvgXOQAAAA==");
                }
            } elseif (filter_var($request->profile_picture, FILTER_VALIDATE_URL)) {
                $profilePictureBinary = file_get_contents($request->profile_picture);
            } else {
                $profilePictureBinary = base64_decode($request->profile_picture);
            }
        } catch (\Exception $e) {
            return base64_decode("UklGRg4DAABXRUJQVlA4WAoAAAAQAAAAJwAAJwAAQUxQSA0BAAABgGptexopyyyZGe2evYBVvJKZFN0DO163mpmZNTMzyZlZLv2J6WT+Rq2MiAkgtnsFR/kHZHZdMKv1BNW+eOb5689iYbxjKHn7DNHkh5DMcBsQ2pHeQ2wbRGK9xMPJhiWsv/PH80W+eSxdvh1Rwn9FSWVGWrhaDfnlWjWAKgrXNpqmKBrwpDwgaZKkAXB4DzJU+P740yhYCXIk2Y8MU/o0/2oUdGNyygc/Zssps53+mL9kSsHqD0PWZEkCCrwMV5UVDSjwY4CiqICocwlWQJVlRQNUHWDsZHp2V5ZkFdB1GFt0IaSagZF6P1GEkBoR+oggWpIobUSQTQdBbvyIGNMhRAipx56IkUg4DSL/FgBWUDgg2gEAAPAIAJ0BKigAKAA+kUSZSqWjoiGnOApIsBIJYgDBsXVgvkcgJ17GhPQOvgidky8fiw+9VINh196O6cTVVzAMb+cWzyAWIrAAL+QcFGfDFQAAAP7/Gt3jwoEvSITLJ0xDHYvMNGzQ7nWrE7dQcTwMdJkoEw1dKk/YaiQ/f5qPDw3ymTApdmVRLHbdhqzfs+Xq3WCDHcIaKNJGG9QO4YVG/zcvZE0Hhsa4HTR9CwN8N7yP1uilT2c0Kn6PBVna3Jvm/SRzVOreOmNftymuBBqMo43xd7/A+D3Zcujc2czkC8gDd3mpm6ht9bn3KLVLM9V8lTNNJoASlcS201l1Cl6vw1d3muRjRfQdHAev8rry1jNzhFtBQkRO+8orsTPDCLE+esqhrkIwqAHf/a3oy1SHqH8bi8pA9TVmYVX0JqCMd51uZrJAgRU0dh55FGpy7XX37/mjxZlwBQzxnEdyjr7zHEI+pe7sFnFGaJvn/jQwN3QH5f+LC//Q0BeJEADeTkP3aLj3UKO5jK/6hwwdCK7qhuRXe7I37QPAKsH+eLH3s/qHBeHkuDH3muiDjqHKwcLLvwf9EitdGA7niLdLaxp47II/nXJmEE7ADX5ArTz+5XR1CR2oLvgXOQAAAA==");
        }

        return $profilePictureBinary;
    }
}