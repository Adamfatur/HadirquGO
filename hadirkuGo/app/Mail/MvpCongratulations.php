<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class MvpCongratulations extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $metric;
    public $period;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $metric
     * @param string $period
     */
    public function __construct(User $user, $metric, $period)
    {
        $this->user = $user;
        $this->metric = $metric;
        $this->period = $period;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('🎉 Congratulations! You Are Today\'s MVP! 🎉')
            ->view('emails.mvp_congratulations')
            ->with([
                'user' => $this->user,
                'metric' => $this->metric,
                'period' => $this->period,
            ]);
    }
}