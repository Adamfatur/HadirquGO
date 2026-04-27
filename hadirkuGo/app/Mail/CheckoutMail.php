<?php

namespace App\Mail;

use App\Models\User;
use App\Models\AttendanceLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CheckoutMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $location;
    public $sent_at;
    public $session_duration;
    public $attendance_id;
    public $points_earned; // Menambahkan properti untuk poin yang diperoleh
    public $formatted_duration; // Menambahkan properti untuk durasi yang diformat

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param AttendanceLocation $location
     * @param Carbon $sent_at
     * @param int $session_duration
     * @param int $attendance_id
     * @param int $points_earned
     */
    public function __construct(User $user, AttendanceLocation $location, Carbon $sent_at, int $session_duration, int $attendance_id, int $points_earned)
    {
        $this->user = $user;
        $this->location = $location;
        $this->sent_at = $sent_at;
        $this->session_duration = $session_duration;
        $this->attendance_id = $attendance_id;
        $this->points_earned = $points_earned;

        // Memformat session_duration menjadi jam dan menit
        $hours = intdiv($session_duration, 60);
        $minutes = $session_duration % 60;

        if ($hours > 0 && $minutes > 0) {
            $this->formatted_duration = sprintf('%d hours %d minutes', $hours, $minutes);
        } elseif ($hours > 0) {
            $this->formatted_duration = sprintf('%d hours', $hours);
        } else {
            $this->formatted_duration = sprintf('%d minutes', $minutes);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('#' . $this->attendance_id . ' Check-out Successful from ' . $this->location->name)
            ->markdown('emails.checkout')
            ->with([
                'user' => $this->user,
                'location' => $this->location,
                'sent_at' => $this->sent_at,
                'session_duration' => $this->session_duration,
                'formatted_duration' => $this->formatted_duration,
                'attendance_id' => $this->attendance_id,
                'points_earned' => $this->points_earned, // Menambahkan poin yang diperoleh
            ]);
    }
}