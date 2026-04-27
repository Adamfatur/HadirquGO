<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_unique_id',
        'name',
        'business_id',
        'leader_id',
    ];

    /**
     * Relasi ke Business
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Relasi ke User sebagai Leader
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Relasi ke Members
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members', 'team_id', 'user_id');
    }

    /**
     * Relasi ke Users yang Tersedia (Tidak di Tim)
     */
    public function availableUsers()
    {
        return User::whereNotIn('id', $this->members->pluck('id'))->get();
    }

    /**
     * Relasi ke Staff yang Tersedia (Bukan Member Tim)
     */
    public function availableStaff()
    {
        // Ambil semua staff yang terkait dengan business ini dan bukan anggota tim
        return Staff::where('business_id', $this->business_id)
            ->whereNotIn('user_id', $this->members->pluck('id'))
            ->get();
    }

    /**
     * Relasi ke Attendances
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function attendanceLeaderboards()
    {
        return $this->hasMany(AttendanceLeaderboard::class);
    }

    /**
     * Relasi ke Managers (User)
     */
    public function managers()
    {
        return $this->belongsToMany(User::class, 'team_managers', 'team_id', 'user_id');
    }
}