<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reward_id',
        'received_at',
        'attendance_id', // Tambahkan kolom attendance_id
    ];

    /**
     * Relasi ke tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel rewards
     */
    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

    /**
     * Relasi ke tabel attendances
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}