<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenger_id',
        'challenged_id',
        'type',
        'duration_days',
        'started_at',
        'ended_at',
        'status',
    ];

    protected $dates = [
        'started_at',
        'ended_at',
    ];

    public function challenger()
    {
        return $this->belongsTo(User::class, 'challenger_id');
    }

    public function challenged()
    {
        return $this->belongsTo(User::class, 'challenged_id');
    }

    public function results()
    {
        return $this->hasMany(ChallengeResult::class);
    }

    public function points()
    {
        return $this->hasMany(ChallengePoint::class);
    }

    public function durations()
    {
        return $this->hasMany(ChallengeDuration::class);
    }
}