<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $name;
    protected $code;
    protected $user;
    public function __construct($name, $code)
    {
        $this->name = $name;
        $this->code = $code;
        $this->user = user()->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Lời mời tham gia ADCRM')->from('noreply@crm.adung.software', $this->user)->view('mail.invitation')->with(['name' => $this->name, 'code' => $this->code]);
    }
}
