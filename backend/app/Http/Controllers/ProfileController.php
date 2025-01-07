<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Profile;

class ProfileController extends Controller
{
    // Get all profiles
    public function index(Request $request)
    {
        try {
            $profiles = Profile::all();
            return $this->respond($request, ['profiles' => $profiles]);
        } catch (\Exception $e) {
            return $this->respondWithError($request, 500);
        }
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $request->user()->fill($request->validated());

            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }

            $request->user()->save();

            return Redirect::route('profile.edit');
        } catch (\Exception $e) {
            return $this->respondWithError($request, 500);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'password' => ['required', 'current_password'],
            ]);

            $user = $request->user();

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/');
        } catch (\Exception $e) {
            return $this->respondWithError($request, 500);
        }
    }

    public function export(Request $request)
    {
        try {
            $profiles = Profile::all();

            if ($profiles->isEmpty()) {
                return $this->respondWithError($request, 404);
            }

            return $this->respond($request, $profiles->toArray());
        } catch (\Exception $e) {
            return $this->respondWithError($request, 500);
        }
    }
}
