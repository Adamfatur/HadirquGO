<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTeamPointSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'team_id',
        'total_team_points',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Team
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Menambah points untuk user di tim tertentu
     */
    public function addTeamPoints(int $points)
    {
        $this->increment('total_team_points', $points);
    }
}
