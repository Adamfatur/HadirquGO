<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingHistory extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'ranking_histories';

    /**
     * Field yang boleh diisi secara mass-assignment (misal via create())
     */
    protected $fillable = [
        'user_id',
        'rank',
        'points',
        'period_type',
        'period_start_date',
        'period_end_date',
    ];

    /**
     * Relasi ke model User (setiap ranking history berhubungan dengan satu user)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Contoh Scope: Memudahkan kita mengambil data ranking suatu periode
     * Usage:
     *    RankingHistory::period('daily', '2023-01-01', '2023-01-01')->get();
     */
    public function scopePeriod($query, $type, $startDate, $endDate)
    {
        return $query->where('period_type', $type)
            ->where('period_start_date', $startDate)
            ->where('period_end_date', $endDate);
    }

    /**
     * Contoh Scope: Mendapatkan top N (misal 50) untuk suatu periode
     * Usage:
     *    RankingHistory::topN(50, 'daily', '2023-01-01', '2023-01-01')->get();
     */
    public function scopeTopN($query, $limit, $type, $startDate, $endDate)
    {
        return $query->where('period_type', $type)
            ->where('period_start_date', $startDate)
            ->where('period_end_date', $endDate)
            ->orderBy('rank', 'asc')
            ->limit($limit);
    }
}