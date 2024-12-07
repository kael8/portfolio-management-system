<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Reaction;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;
use App\Models\PostImage;
use App\Rules\AtLeastOneField;

class PostController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'at_least_one' => [new AtLeastOneField(['title', 'content', 'images'])],
        ]);

        $user = Auth::guard('api')->user();

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the post
            $post = Post::create([
                'user_id' => $user->id,
                'title' => $validatedData['title'] ?? '',
                'content' => $validatedData['content'] ?? '',
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


        // Attempt to authenticate the user
        $user = Auth::guard('api')->user();

        $userId = $user ? $user->id : null;
        $isPrivate = $user ? $user->isPrivate : null;
        $posts = Post::with([
            'images',
            'user.profile', // Ensure this is correct
            'comments.user.profile',
            'reactions_summary'
        ])->orderBy('created_at', 'desc')->get();

        // Attach user_reaction to each post
        if ($userId) {
            $posts->each(function ($post) use ($userId) {
                $post->user_reaction = Reaction::where('post_id', $post->id)
                    ->where('user_id', $userId)
                    ->first();
            });
        }

        $profileImage = User::where('isOwner', true)
            ->with('profile')
            ->first()
            ->profile
            ->image_url;

        // Filter out posts.user isOwner, google_id, email_verified_at, created_at, and updated_at fields
        $posts->transform(function ($post) {
            $post->user = $post->user->makeHidden(['isOwner', 'google_id', 'email', 'email_verified_at', 'created_at', 'updated_at']);
            return $post;
        });

        // Filter out posts.comments.user isOwner, google_id, email_verified_at, created_at, and updated_at fields
        $posts->transform(function ($post) {
            $post->comments->transform(function ($comment) {
                $comment->user = $comment->user->makeHidden(['isOwner', 'google_id', 'email', 'email_verified_at', 'created_at', 'updated_at']);
                return $comment;
            });
            return $post;
        });

        // Change posts.comments.user name to anonymous if the user is not is inPrivate
        $posts->transform(function ($post) {
            $post->comments->transform(function ($comment) {
                if ($comment->user->isPrivate) {
                    $comment->user->name = 'Anonymous';
                    $comment->user->profile->image_url = null;
                }
                return $comment;
            });
            return $post;
        });


        return response()->json(['posts' => $posts, 'profileImage' => $profileImage, 'userId' => $userId, 'isPrivate' => $isPrivate], 200);
    }
}
