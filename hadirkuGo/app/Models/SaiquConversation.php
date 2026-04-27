<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaiquConversation extends Model
{
    protected $table = 'saiqu_conversations';

    protected $fillable = [
        'user_id',
        'role',
        'message',
    ];

    /**
     * Relationship to User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get recent conversation history for a user.
     */
    public static function getHistory(int $userId, int $limit = null): array
    {
        $limit = $limit ?? config('saiqu.max_history', 5);

        // Get last N pairs (user + model messages)
        $messages = self::where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->limit($limit * 2)
            ->get()
            ->reverse()
            ->values();

        return $messages->map(fn ($m) => [
            'role' => $m->role,
            'text' => $m->message,
        ])->toArray();
    }

    /**
     * Store a message in conversation history.
     */
    public static function storeMessage(int $userId, string $role, string $message): self
    {
        return self::create([
            'user_id' => $userId,
            'role' => $role,
            'message' => $message,
        ]);
    }

    /**
     * Clear conversation history for a user.
     */
    public static function clearHistory(int $userId): void
    {
        self::where('user_id', $userId)->delete();
    }
}
