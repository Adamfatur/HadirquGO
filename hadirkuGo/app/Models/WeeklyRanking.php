<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyRanking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_points',
        'total_sessions',
        'total_hours',
        'week_start_date',
        'week_end_date',
    ];

    protected $dates = [
        'week_start_date',
        'week_end_date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor to get total hours in hours format
    public function getTotalHoursInHoursAttribute()
    {
        return round($this->total_hours / 60, 2); // Convert minutes to hours
    }
}