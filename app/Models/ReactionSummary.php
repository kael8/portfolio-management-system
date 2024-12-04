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
}
