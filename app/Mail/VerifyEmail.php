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
    protected $email_token;
    public function __construct($email_token)
    {
        $this->email_token = $email_token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Xác nhận email đăng kí tài khoản ADCRM')->view('mail.verify_email')->with(['email_token' => $this->email_token]);
    }
}
