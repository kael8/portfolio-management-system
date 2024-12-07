<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'post_id' => 'required|integer|exists:posts,id',
            'content' => 'required|string|max:255',
        ]);

        $user = Auth::guard('api')->user();

        DB::beginTransaction();

        try {
            $comment = new Comment();
            $comment->user_id = $user->id;
            $comment->post_id = $request->post_id;
            $comment->content = $request->content;
            $comment->save();

            $comments = Comment::where('post_id', $request->post_id)
                ->with('user')

                ->get();

            DB::commit();

            // Change comments.user name to anonymous if user is isPrivate
            foreach ($comments as $comment) {
                $comment->user->name = $comment->user->isPrivate ? 'Anonymous' : $comment->user->name;
            }

            return response()->json([
                'message' => 'Comment created successfully',
                'comments' => $comments
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
