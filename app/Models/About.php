<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $table = 'about';

    protected $fillable = [
        'user_id',
        'school',
        'degree',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
