<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    // The table associated with the model
    protected $table = 'levels';

    // The attributes that are mass assignable
    protected $fillable = [
        'name',
        'minimum_points',
        'maximum_points',
        'description',
        'image_url',
    ];

    // Define any relationships if necessary
    // For example, if users belong to levels based on points
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if the given points fall within the range of this level.
     *
     * @param int $points
     * @return bool
     */
    public function containsPoints(int $points): bool
    {
        return $points >= $this->minimum_points && $points <= $this->maximum_points;
    }

    /**
     * Get the formatted name with points range (for display purposes).
     *
     * @return string
     */
    public function getFormattedNameAttribute(): string
    {
        return "{$this->name} ({$this->minimum_points} - {$this->maximum_points} pts)";
    }

    /**
     * Get the level image URL or a default image if not set.
     *
     * @return string
     */
    public function getImageOrDefaultAttribute(): string
    {
        return $this->image_url ?? asset('images/default-level.png');
    }
}
