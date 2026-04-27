<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'business_unique_id',
        'owner_id',
        'contact_person',
        'contact_email',
        'contact_phone',
    ];

    /**
     * Relationship: The owner of the business (User).
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Relationship: All staff members of the business.
     */
    public function staff()
    {
        return $this->hasMany(Staff::class, 'business_id');
    }


    /**
     * Relationship: Lecturers in the business.
     * This filters staff members who have the "Lecturer" role via the `role_user` table.
     */
    public function lecturers()
    {
        return $this->belongsToMany(User::class, 'staff', 'business_id', 'user_id')
            ->whereHas('roles', function ($query) {
                $query->where('roles.name', 'Lecturer');
            });
    }


    /**
     * Relationship: All teams in the business.
     */
    public function teams()
    {
        return $this->hasMany(Team::class, 'business_id');
    }

    /**
     * Relationship: Attendance locations linked to the business.
     */
    public function attendanceLocations()
    {
        return $this->hasMany(AttendanceLocation::class, 'business_id');
    }

    /**
     * Scope: Search businesses by name or unique ID.
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('business_unique_id', 'like', '%' . $search . '%');
        }
        return $query;
    }

    /**
     * Helper: Get all users available for adding to staff.
     * Excludes users already in the staff list.
     */
    public function availableStaff()
    {
        $staffUserIds = $this->staff()->pluck('user_id');
        return User::whereNotIn('id', $staffUserIds)->get();
    }

    // Dalam model Business

    /**
     * Menambahkan staff baru ke bisnis.
     *
     * @param int $userId
     * @param string $role
     * @return \App\Models\Staff
     */
    public function addStaff($userId, $role)
    {
        return $this->staff()->create([
            'user_id' => $userId,
            'role' => $role,
        ]);
    }

    /**
     * Remove a staff member from the business.
     *
     * @param int $userId
     * @return bool
     */
    public function removeStaff($userId)
    {
        return $this->staff()->where('user_id', $userId)->delete();
    }


    // app/Models/Business.php

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function getRouteKeyName()
    {
        return 'business_unique_id';
    }

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }

    /**
     * Get all SuperQuizzes for this business
     */
    public function superQuizzes()
    {
        return $this->hasMany(SuperQuiz::class, 'business_id'); // Ensure the foreign key is correct
    }

}
