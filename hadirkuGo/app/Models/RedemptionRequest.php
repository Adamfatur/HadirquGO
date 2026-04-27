<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedemptionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'status',
        'requested_at',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_WAITING_LIST = 'waiting_list';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Disable Laravel's default timestamps.
     */
    public $timestamps = true;

    protected $dates = ['requested_at']; // Konversi 'requested_at' ke objek Carbon

    /**
     * Custom timestamp columns.
     */
    const CREATED_AT = 'requested_at';
    const UPDATED_AT = 'updated_at';

    /**
     * A redemption request belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A redemption request belongs to a product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
