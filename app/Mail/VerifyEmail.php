<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $emailToken;
    protected $isConfirming;
    public function __construct($emailToken, $isConfirming = false)
    {
        $this->emailToken = $emailToken;
        $this->isConfirming = $isConfirming;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->isConfirming) {
            return $this->subject('Xác nhận email của bạn')->view('mail.confirm_email')->with(['emailToken' => $this->emailToken]);
        }
        return $this->subject('Xác nhận email đăng kí tài khoản ADCRM')->view('mail.verify_email')->with(['emailToken' => $this->emailToken]);
    }
}
