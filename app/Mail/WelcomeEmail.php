<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;


    public $firstName;
    public $lastName;
    public $practiceName;
    public $activationLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstName,$lastName,$practiceName,$activationLink)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->practiceName = $practiceName;
        $this->activationLink = $activationLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->lastName. ' Invites You to Your New Patient Management Software')
                    ->view('emails.welcome')
                    ->with([
                        'firstName' => $this->firstName,
                        'lastName' => $this->lastName,
                        'practiceName' => $this->practiceName,
                        'activationLink' => $this->activationLink,
                    ]);
    }
}
