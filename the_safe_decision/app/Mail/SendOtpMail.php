<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    /**
     * Create a new message instance.
     *
     * @param string $otp
     * @return void
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('verify@thesafedecision.com', 'TSD') // Specify sender email and name
                    ->view('emails.sendOtp')
                    ->subject('Verify TSD Account')
                    ->with([
                        'otp' => $this->otp,
                    ]);
    }
}
