<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;
use App\Models\PostImage;

class PostController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::guard('api')->user();

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the post
            $post = Post::create([
                'user_id' => $user->id,
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
            ]);

            // Handle images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    // Upload each image to Cloudinary
                    $uploadedFileUrl = Cloudinary::upload(
                        $image->getRealPath(),
                        ['folder' => 'portfolio/posts']
                    )->getSecurePath();

                    // Save image URL in the post_images table
                    PostImage::create([
                        'post_id' => $post->id,
                        'image_url' => $uploadedFileUrl,
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            return response()->json([
                'message' => 'Post created successfully!',
                'post' => $post,
                'images' => $post->images,
            ], 201);
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPosts(Request $request)
    {
        $posts = Post::with('images')
            ->with('user')
            ->with('comments')
            ->with('reactions_summary')
            ->get();

        $profileImage = User::where('isOwner', true)
            ->with('profile')
            ->first()
            ->profile
            ->image_url;

        $token = $request->cookie('jwt_token');

        $request->headers->set('Authorization', 'Bearer ' . $token);

        // Attempt to authenticate the user
        $user = Auth::guard('api')->user();

        $userId = $user ? $user->id : null;

        return response()->json(['posts' => $posts, 'profileImage' => $profileImage, 'userId' => $userId], 200);
    }
}
