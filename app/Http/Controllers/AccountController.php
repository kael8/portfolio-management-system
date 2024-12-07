<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function updateAccount(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'nullable|string',
                'password' => 'nullable|min:6',
                'isPrivate' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Get the authenticated user
            $user = Auth::guard('api')->user();

            if (!$user) {
                Log::error('User not authenticated.');
                return response()->json(['message' => 'User not authenticated'], 401);
            }

            // Hash password if it was provided
            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }

            // Update the user's account
            $user->update($validated);

            // Handle profile image upload if provided
            if ($request->hasFile('image')) {
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
            }

            return response()->json(['message' => 'Account updated successfully', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update account', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAccount(Request $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::guard('api')->user();

            $account = User::with('profile')
                ->where('id', $user->id)->first();

            $account = $account->makeHidden(['id', 'user_id', 'created_at', 'updated_at']);

            return response()->json(['message' => 'Account retrieved successfully', 'account' => $account], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve account', 'error' => $e->getMessage()], 500);
        }
    }
}
