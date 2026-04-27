<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttendanceLocation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_id',
        'unique_id',
        'name',
        'slug',
        'description',
        'latitude',
        'longitude',
    ];

    /**
     * The "booted" method of the model.
     * Automatically generate unique_id and slug.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($location) {
            $location->unique_id = Str::uuid(); // Automatically set a unique UUID
            $location->slug = Str::slug($location->name) . '-' . Str::random(5); // Unique slug
        });
    }

    /**
     * Get the business that owns the attendance location.
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Scope a query to search attendance locations by name or unique_id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search = null)
    {
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('unique_id', 'like', '%' . $search . '%');
        }
        return $query;
    }
}
