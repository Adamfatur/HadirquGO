<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_location_id',
        'type',
        'message',
        'time',
    ];

    protected $dates = [
        'time',
    ];

    /**
     * Relasi ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke AttendanceLocation.
     */
    public function attendanceLocation()
    {
        return $this->belongsTo(AttendanceLocation::class);
    }
}