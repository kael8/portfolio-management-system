<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReactionSummary extends Model
{
    use HasFactory;

    protected $table = 'reaction_summaries';

    protected $fillable = [
        'post_id',
        'hearts_count',
        'cool_count',
        'bad_count',
    ];

    // Define the relationship to get the user's reaction
    public function user_reaction($userId)
    {
        return $this->hasOne(Reaction::class, 'post_id', 'post_id')->where('user_id', $userId);
    }
}
