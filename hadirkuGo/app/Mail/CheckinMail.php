<?php

namespace App\Mail;

use App\Models\User;
use App\Models\AttendanceLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CheckinMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $location;
    public $sent_at;
    public $attendance_id;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param AttendanceLocation $location
     * @param Carbon $sent_at
     * @param int $attendance_id
     */
    public function __construct(User $user, AttendanceLocation $location, Carbon $sent_at, int $attendance_id)
    {
        $this->user = $user;
        $this->location = $location;
        $this->sent_at = $sent_at;
        $this->attendance_id = $attendance_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('#' . $this->attendance_id . ' Check-in Successful at ' . $this->location->name)
            ->markdown('emails.checkin');
    }
}
