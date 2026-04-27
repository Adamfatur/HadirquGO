<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLeaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'user_id',
        'score',
        'secondary_score',
        'third_score',
        'current_rank',
        'previous_rank',
        'title',
        'frame_color',
        'frame_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
