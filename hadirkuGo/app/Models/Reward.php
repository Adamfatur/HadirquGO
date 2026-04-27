<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'quantity',
        'probability',
        'image',       // Tambahkan kolom baru
        'deskripsi',   // Tambahkan kolom baru
        'points',       // Tambahkan kolom baru
    ];

    // Relasi ke tabel user_rewards
    public function userRewards()
    {
        return $this->hasMany(UserReward::class);
    }

    // Relasi ke tabel reward_probabilities
    public function rewardProbabilities()
    {
        return $this->hasMany(RewardProbability::class);
    }
}