<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    use HasFactory;

    // Menyesuaikan dengan nama tabel 'biodatas'
    protected $table = 'biodatas';

    protected $fillable = [
        'user_id',
        'phone_number',
        'id_number',
        'other_id_number',
        'nickname',
        'about',
        'verified',
        'degree_id',
        'birth_date',
    ];

    /**
     * Relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Menyediakan akses untuk mendapatkan status verifikasi sebagai boolean.
     */
    public function getVerifiedAttribute($value)
    {
        return (bool) $value;
    }

    /**
     * Mengatur format tanggal lahir menjadi format yang lebih ramah.
     */
    public function getBirthDateAttribute($value)
    {
        // Kembalikan null jika nilai birth_date adalah null
        if (is_null($value)) {
            return null;
        }

        // Format nilai birth_date hanya jika tidak null
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }


    /**
     * Menyediakan akses untuk mendapatkan usia berdasarkan tanggal lahir.
     */
    public function getAgeAttribute()
    {
        return \Carbon\Carbon::parse($this->birth_date)->age;
    }
}
