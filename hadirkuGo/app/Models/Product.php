<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'product_code',
        'description',
        'image',
        'stock_quantity',
        'points_required',
        'status',
        'owner_id',
        'business_id',
    ];

    /**
     * A product belongs to an owner (User).
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * A product has many redemption requests.
     */
    public function redemptionRequests()
    {
        return $this->hasMany(RedemptionRequest::class);
    }

    /**
     * Check if the product is in "waiting_list" status.
     *
     * @return bool
     */
    public function isWaitingList()
    {
        return $this->status === 'waiting_list';
    }

    /**
     * Check if the product is in "ready" status.
     *
     * @return bool
     */
    public function isReady()
    {
        return $this->status === 'ready';
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
