<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'type',
        'is_active',
        'expires_at',
    ];

    protected $dates = [
        'expires_at',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cek apakah token sudah expired
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    // Cek apakah token masih aktif
    public function isActive()
    {
        return $this->is_active;
    }

    // Menonaktifkan token setelah digunakan
    public function deactivate()
    {
        $this->is_active = false;
        $this->save();
    }
}
