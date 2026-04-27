<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationLeaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'attendance_location_id',
        'score',
        'secondary_score',
        'current_rank',
        'previous_rank',
    ];

    public function attendanceLocation()
    {
        return $this->belongsTo(AttendanceLocation::class);
    }
}
