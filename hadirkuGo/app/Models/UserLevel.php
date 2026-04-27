<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'user_levels';

    // Mass assignable attributes
    protected $fillable = [
        'user_id',
        'level_id',
        'current_points',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
