<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // Get all profiles
    public function index(Request $request)
    {
        try {
            $profiles = Profile::all();
            return $this->respond(['message' => 'Profiles fetched succesfully!', 'profiles' => $profiles], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Create new profile
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:255|exists:name',
                'profile_picture_path' => 'nullable|string',
                'date_of_birth' => 'required|date',
                'language_id' => 'nullable|integer',
            ]);

            $profile = Profile::create([
                'name' => $request->name,
                'profile_picture_path' => $request->profile_picture_path ?? '/path/to/image/default.png', // TODO: Create default PFP
                'date_of_birth' => $request->date_of_birth,
                'language_id' => $request->language_id,
            ]);
            
            return $this->respond(['message' => 'Subscription created successfully!', 'profile' => $profile], 201, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Get specific profile
    public function show(Request $request, $profile_id)
    {
        try {
            $profile = Profile::findOrFail($profile_id);
            return $this->respond(['message' => 'Profile fetched succesfuly!', 'profile' => $profile], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(404, $request);
        }
    }

    // Update profile information
    public function update(Request $request, $profile_id)
    {
        try {
            $profile = Profile::findOrFail($profile_id);
            $profile->update($request->all());
            return $this->respond(['message' => 'User updated successfully!', 'profile' => $profile], 200, $request);
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
}
