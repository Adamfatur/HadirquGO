<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'winner_id',
        'loser_id',
        'points_awarded',
        'points_deducted',
    ];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function loser()
    {
        return $this->belongsTo(User::class, 'loser_id');
    }
}