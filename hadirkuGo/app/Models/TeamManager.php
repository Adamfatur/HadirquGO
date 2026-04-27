<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamManager extends Model
{
    use HasFactory;

    protected $table = 'team_managers';
    // Jika butuh mass assignment
    protected $fillable = ['team_id', 'user_id'];

    /**
     * Relasi ke model Team (one to many/belongsTo).
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Relasi ke model User (one to many/belongsTo).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}