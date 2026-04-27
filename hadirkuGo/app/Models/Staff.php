<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'role',
    ];

    /**
     * Relasi ke model Business.
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
