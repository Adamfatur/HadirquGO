<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardProbability extends Model
{
    use HasFactory;

    protected $fillable = [
        'reward_id',
        'probability',
    ];

    /**
     * Relasi ke tabel rewards
     */
    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}