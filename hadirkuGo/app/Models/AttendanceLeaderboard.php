<?php

// app/Models/AttendanceLeaderboard.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLeaderboard extends Model
{
    use HasFactory;

    // Pastikan penamaan tabel sesuai dengan yang ada di database
    protected $table = 'attendance_leaderboard';


    // Menentukan kolom-kolom yang dapat diisi (fillable)
    protected $fillable = [
        'user_id',            // ID pengguna yang paling pagi atau terlambat
        'team_id',            // ID tim
        'date',               // Tanggal
        'morning_person',     // Flag untuk Morning Person
        'last_person',        // Flag untuk Last Person
        'longest_duration',   // Durasi kegiatan terlama dalam jam
    ];

    /**
     * Relasi dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan Team
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Mutator untuk memastikan `longest_duration` disimpan dalam format yang tepat.
     */
    public function setLongestDurationAttribute($value)
    {
        $this->attributes['longest_duration'] = number_format($value, 2, '.', '');
    }

    /**
     * Aksesors untuk mendapatkan durasi dalam format yang lebih mudah dibaca
     */
    public function getLongestDurationAttribute($value)
    {
        return number_format($value, 2, '.', '');
    }
}

