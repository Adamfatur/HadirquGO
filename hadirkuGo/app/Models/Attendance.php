<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_location_id',
        // 'team_id',  <-- Dihapus
        'checkin_time',
        'checkout_time',
        'duration_at_location',
        'total_daily_duration',
        'type',
        'points',
        'locations', // Masih ada
    ];

    protected $dates = [
        'checkin_time',
        'checkout_time',
    ];

    protected $casts = [
        'locations'      => 'array',
        'checkin_time'   => 'datetime',
        'checkout_time'  => 'datetime',
    ];

    /**
     * Relasi ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke AttendanceLocation (Lokasi saat ini).
     */
    public function attendanceLocation()
    {
        return $this->belongsTo(AttendanceLocation::class, 'attendance_location_id');
    }

    /**
     * HAPUS relasi ke Team (tidak digunakan lagi).
     */
    // public function team()
    // {
    //     return $this->belongsTo(Team::class);
    // }

    /**
     * Mengembalikan durasi di lokasi dalam format "X hours Y minutes".
     */
    public function getFormattedDurationAtLocationAttribute()
    {
        if ($this->duration_at_location) {
            $hours   = intdiv($this->duration_at_location, 60);
            $minutes = $this->duration_at_location % 60;
            return sprintf('%d hours %d minutes', $hours, $minutes);
        }
        return null;
    }

    /**
     * Mengembalikan total durasi harian dalam format "X hours Y minutes".
     */
    public function getFormattedTotalDailyDurationAttribute()
    {
        if ($this->total_daily_duration) {
            $hours   = intdiv($this->total_daily_duration, 60);
            $minutes = $this->total_daily_duration % 60;
            return sprintf('%d hours %d minutes', $hours, $minutes);
        }
        return null;
    }
}