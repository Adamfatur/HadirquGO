<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'average_checkin_time',
        'most_frequent_location_id',
        'all_visited_locations',
        'average_checkout_time',
        'total_checkins',
        'total_checkouts',
        'longest_consecutive_attendance_streak',
        'max_checkins_in_one_day',
        'total_attendance_sessions',
        'least_frequent_location_id',
        'morning_person_count',
        'late_person_count',
    ];

    protected $casts = [
        'all_visited_locations' => 'array',
        // HAPUS: 'average_checkin_time' => 'datetime:H:i:s',
        // HAPUS: 'average_checkout_time' => 'datetime:H:i:s',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mostFrequentLocation()
    {
        return $this->belongsTo(AttendanceLocation::class, 'most_frequent_location_id');
    }

    public function leastFrequentLocation()
    {
        return $this->belongsTo(AttendanceLocation::class, 'least_frequent_location_id');
    }
}
