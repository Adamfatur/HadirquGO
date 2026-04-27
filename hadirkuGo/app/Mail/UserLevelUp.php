<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Level;

class UserLevelUp extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $level;
    public $totalPoints; // Tambahkan variabel untuk total poin

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Level $level
     */
    public function __construct(User $user, Level $level)
    {
        $this->user = $user;
        $this->level = $level;
        $this->totalPoints = $user->userPoints->sum('points'); // Hitung total poin
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Congratulations! You Have Leveled Up')
            ->view('emails.user_level_up')
            ->with([
                'user' => $this->user,
                'level' => $this->level,
                'totalPoints' => $this->totalPoints, // Teruskan total poin ke blade
            ]);
    }
}