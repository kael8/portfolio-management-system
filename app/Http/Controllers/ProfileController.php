<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'title' => 'nullable|string',
                'description' => 'nullable|string',
                'phone' => 'nullable|string',
                'address' => 'nullable|string',
                'facebook' => 'nullable|string',
                'twitter' => 'nullable|string',
                'instagram' => 'nullable|string',
                'linkedin' => 'nullable|string',
                'github' => 'nullable|string',
            ]);

            // Get the authenticated user
            $user = Auth::guard('api')->user();

            // Create or update the user's profile
            $profile = Profile::updateOrCreate(
                ['user_id' => $user->id],
                $validated
            );

            return response()->json(['message' => 'Profile created or updated successfully', 'profile' => $profile], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create or update profile', 'error' => $e->getMessage()], 500);
        }
    }

    public function getProfile(Request $request)
    {
        try {
            // Get the authenticated user using Sanctum
            $userId = User::where('isOwner', true)->first()->id;


            // Retrieve the user's profile
            $profile = User::with('profile')->find($userId);

            if (!$profile) {
                return response()->json(['message' => 'Profile not found'], 404);
            }

            // Filter out isOwner, google_id, and email_verified_at, created_at, and updated_at fields
            $profile = $profile->makeHidden(['id', 'isOwner', 'google_id', 'email_verified_at', 'created_at', 'updated_at']);

            // Filter out profile.profile id, user_id, created_at, and updated_at fields
            $profile->profile = $profile->profile->makeHidden(['id', 'user_id', 'created_at', 'updated_at']);

            return response()->json(['profile' => $profile], 200);
        } catch (\Exception $e) {
            Log::error('Profile retrieval error', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to retrieve profile', 'error' => $e->getMessage()], 500);
        }
    }

    public function profileImage(Request $request)
    {
        // Validate the request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Get the authenticated user
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            Log::error('User not authenticated.');
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Get or create the user's profile
        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id],
            ['user_id' => $user->id] // Default values if creating a new profile
        );

        // Upload the image to Cloudinary
        $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
            'public_id' => $user->id . '/profile-image',
            'folder' => 'portfolio/profile',
            'overwrite' => true,
        ])->getSecurePath();

        // Update the profile image
        $profile->image_url = $uploadedFileUrl;
        $profile->save();

        return response()->json(['message' => 'Profile image uploaded successfully', 'image' => $uploadedFileUrl], 200);
    }

    public function getProfileImage(Request $request)
    {
        // Get the authenticated user
        $userId = User::where('isOwner', true)->first()->id;

        // Get the user's profile
        $profile = Profile::where('user_id', $userId)->first();

        return response()->json(['image' => $profile->image_url], 200);
    }

    // Update the user's account
    public function updateAccount(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'nullable|string',
                'isPrivate' => 'nullable|boolean',

            ]);

            // Get the authenticated user
            $user = Auth::guard('sanctum')->user();

            // Update the user's account
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->save();

            return response()->json(['message' => 'Account updated successfully', 'user' => $user], 200);
        } catch (\Exception $e) {
            Log::error('Account update error', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to update account', 'error' => $e->getMessage()], 500);
        }
    }
}
