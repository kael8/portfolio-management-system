<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Reaction;
use App\Models\ReactionSummary;


class ReactionController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'post_id' => 'required|integer|exists:posts,id',
            'reaction_id' => 'required|integer',
        ]);

        $token = $request->cookie('jwt_token');
        $request->headers->set('Authorization', 'Bearer ' . $token);

        // Attempt to authenticate the user
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Use a transaction to ensure both operations succeed or fail together
        DB::beginTransaction();

        try {
            // Find the existing reaction if it exists
            $existingReaction = Reaction::where('post_id', $validatedData['post_id'])
                ->where('user_id', $user->id)
                ->first();

            // Decrement the count of the existing reaction type if it exists
            if ($existingReaction) {
                ReactionSummary::where('post_id', $validatedData['post_id'])
                    ->update([$this->getReactionColumn($existingReaction->type) => DB::raw($this->getReactionColumn($existingReaction->type) . ' - 1')]);
            }
            //return response()->json(['sdsds' => 'asfsafs']);
            // Update or create the reaction
            $reaction = Reaction::updateOrCreate(
                ['post_id' => $validatedData['post_id'], 'user_id' => $user->id],
                ['type' => $validatedData['reaction_id']]
            );

            // Increment the count of the new reaction type
            ReactionSummary::updateOrCreate(
                ['post_id' => $validatedData['post_id']],
                [$this->getReactionColumn($validatedData['reaction_id']) => DB::raw('COALESCE(' . $this->getReactionColumn($validatedData['reaction_id']) . ', 0) + 1')]
            );

            DB::commit();

            $reaction_summary = ReactionSummary::where('post_id', $reaction->post_id)->first();

            return response()->json(['reaction_summary' => $reaction_summary], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update reaction', 'error' => $e->getMessage()], 500);
        }
    }

    private function getReactionColumn($reactionId)
    {
        $reactionColumns = [
            1 => 'hearts_count',
            2 => 'cool_count',
            3 => 'bad_count',
        ];

        return $reactionColumns[$reactionId] ?? $reactionId;
    }
}
