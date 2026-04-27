<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'banner_url',
        'is_active',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}