<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class RedemptionRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Publik properties yang akan diakses di Blade:
     */
    public $user;
    public $product;
    public $status;
    public $notificationMessage;
    public $redeemedAt;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\User   $user
     * @param  \App\Models\Product $product
     * @param  string             $status
     * @param  string             $notificationMessage
     * @param  \Carbon\Carbon     $redeemedAt
     */
    public function __construct(User $user, Product $product, $status, $notificationMessage, Carbon $redeemedAt)
    {
        $this->user = $user;
        $this->product = $product;
        $this->status = $status;
        $this->notificationMessage = $notificationMessage;
        $this->redeemedAt = $redeemedAt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Redemption Request Update for Product: {$this->product->product_code}";

        return $this->subject($subject)
            ->view('emails.redemption_notification')
            ->with([
                'user'               => $this->user,
                'product'            => $this->product,
                'status'             => $this->status,
                'notificationMessage'=> $this->notificationMessage,
                'redeemedAt'         => $this->redeemedAt,
            ]);
    }
}