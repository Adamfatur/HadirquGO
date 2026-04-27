<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\UserLeaderboard;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'comparison_user_id',
        'name',
        'name_changed',
        'email',
        'avatar',
        'member_id',
        'password',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'name_changed' => 'boolean',
    ];

    /**
     * Display name: strip numbers and ucwords for users who haven't changed name
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->name_changed) {
            return $this->name;
        }
        // Remove digits and extra spaces, then capitalize each word
        $cleaned = preg_replace('/\d+/', '', $this->name);
        $cleaned = trim(preg_replace('/\s+/', ' ', $cleaned));
        return $cleaned ? ucwords(strtolower($cleaned)) : $this->name;
    }

    /**
     * Relasi dengan Role
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Virtual attribute to get the first role name
     */
    public function getRoleAttribute()
    {
        return $this->roles->first()?->name;
    }

    /**
     * Cek apakah user memiliki role tertentu
     */
    public function hasRole($roles)
    {
        return $this->roles()->whereIn('name', (array) $roles)->exists();
    }

    /**
     * Assign role ke user tanpa duplikasi
     */
    public function assignRole($role)
    {
        $role = Role::where('name', $role)->firstOrFail();
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    /**
     * Hapus role dari user
     */
    public function removeRole($role)
    {
        $role = Role::where('name', $role)->firstOrFail();
        $this->roles()->detach($role);
    }

    /**
     * Ganti role user secara dinamis
     */
    public function switchRole($newRole)
    {
        $role = Role::where('name', $newRole)->firstOrFail();
        $this->roles()->sync([$role->id]);
    }

    /**
     * Relasi dengan model Business sebagai pemilik
     */
    public function businesses()
    {
        return $this->hasMany(Business::class, 'owner_id');
    }

    /**
     * Relasi dengan model Business melalui tabel Staff
     */
    public function businessesAsStaff()
    {
        return $this->belongsToMany(Business::class, 'staff', 'user_id', 'business_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function teamsLed()
    {
        return $this->hasMany(Team::class, 'leader_id');
    }

    public function teamsJoined()
    {
        return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function feedbackLikes()
    {
        return $this->hasMany(FeedbackLike::class);
    }
    /**
     * Menggabungkan tim yang dipimpin dan tim yang diikuti
     */
    public function teams()
    {
        return $this->teamsLed()->get()->merge($this->teamsJoined()->get());
    }


    // app/Models/User.php

    public function userPoints()
    {
        return $this->hasMany(UserPoint::class);
    }

    public function getTotalPointsAttribute()
    {
        return $this->userPoints()->sum('points');
    }

    public function biodata()
    {
        return $this->hasOne(Biodata::class);
    }

    // relasi dengan model Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function attendanceLeaderboards()
    {
        return $this->hasMany(AttendanceLeaderboard::class);
    }

    public function userLevel()
    {
        return $this->hasOne(UserLevel::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id')->through('userLevel');
    }

    public function statistics()
    {
        return $this->hasOne(UserStatistic::class);
    }

    public function redemptionRequests()
    {
        return $this->hasMany(RedemptionRequest::class);
    }

    /**
     * If the user is an owner, they can have many products.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'owner_id');
    }

    public function managedTeams()
    {
        return $this->belongsToMany(Team::class, 'team_managers', 'user_id', 'team_id');
    }

    public function challengesAsChallenger()
    {
        return $this->hasMany(Challenge::class, 'challenger_id');
    }

    public function challengesAsChallenged()
    {
        return $this->hasMany(Challenge::class, 'challenged_id');
    }

    public function challengeResults()
    {
        return $this->hasMany(ChallengeResult::class, 'winner_id');
    }

    public function challengePoints()
    {
        return $this->hasMany(ChallengePoint::class);
    }

    public function challengeDurations()
    {
        return $this->hasMany(ChallengeDuration::class);
    }

    // Di dalam model User (app/Models/User.php)
    public function pointSummary()
    {
        return $this->hasOne(UserPointSummary::class, 'user_id');
    }

    public function leaderboards()
    {
        return $this->hasMany(UserLeaderboard::class, 'user_id');
    }

    public function comparisonUser()
    {
        return $this->belongsTo(User::class, 'comparison_user_id');
    }

    public function dailyCheckins()
    {
        return $this->hasMany(DailyCheckin::class);
    }

    // Di dalam model User (app/Models/User.php)
    public function testimonies()
    {
        return $this->hasMany(Testimony::class);
    }

    /**
     * Relasi ke tabel user_rewards
     */
    public function userRewards()
    {
        return $this->hasMany(UserReward::class);
    }

    /**
     * Mendapatkan semua hadiah yang diterima oleh user
     */
    public function rewards()
    {
        return $this->belongsToMany(Reward::class, 'user_rewards', 'user_id', 'reward_id')
            ->withPivot('received_at')
            ->withTimestamps();
    }

    // Di dalam model User (app/Models/User.php)
    public function userAchievements()
    {
        return $this->hasMany(UserAchievement::class);
    }
}
