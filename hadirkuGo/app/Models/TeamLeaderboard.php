<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLeaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'team_id',
        'score',
        'current_rank',
        'previous_rank',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
