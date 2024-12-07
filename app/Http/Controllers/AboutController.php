<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;
use Illuminate\Support\Facades\Auth;

class AboutController extends Controller
{
    public function about(Request $request)
    {

        try {
            // Validate the request
            $validated = $request->validate([
                'school' => 'nullable|string',
                'degree' => 'nullable|string',
                'description' => 'nullable|string',
            ]);

            // Get the authenticated user
            $user = Auth::guard('api')->user();

            // Create or update the user's about
            $about = About::updateOrCreate(
                ['user_id' => $user->id],
                $validated
            );

            // Hide id, user_id, created_at, and updated_at fields
            $about = $about->makeHidden(['id', 'user_id', 'created_at', 'updated_at']);

            return response()->json(['message' => 'About created or updated successfully', 'about' => $about], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create or update about', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAbout(Request $request)
    {
        try {

            // Get the user's about
            $about = About::first();

            // Hide id, user_id, created_at, and updated_at fields
            $about = $about->makeHidden(['id', 'user_id', 'created_at', 'updated_at']);

            return response()->json(['message' => 'About retrieved successfully', 'about' => $about], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve about', 'error' => $e->getMessage()], 500);
        }
    }
}
