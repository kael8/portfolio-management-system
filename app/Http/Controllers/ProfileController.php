<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
            $user = $request->user();

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
        // Get the authenticated user
        $user = $request->user();

        // Get the user's profile
        $profile = Profile::with('user')
            ->where('user_id', $user->id)->first();

        return response()->json(['profile' => $profile], 200);
    }

    public function profileImage(Request $request)
    {
        // Validate the request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Get the authenticated user
        $user = $request->user();

        // Get the user's profile
        $profile = Profile::where('user_id', $user->id)->first();

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
        $user = $request->user();

        // Get the user's profile
        $profile = Profile::where('user_id', $user->id)->first();

        return response()->json(['image' => $profile->image_url], 200);
    }
}
