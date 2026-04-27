<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'team_id', 'achievement_id', 'achieved_at'];

    protected $casts = [
        'achieved_at' => 'datetime', // Pastikan kolom ini otomatis dikonversi ke datetime
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }
}

