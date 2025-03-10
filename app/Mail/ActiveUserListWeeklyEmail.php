<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActiveUserListWeeklyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $attachmentPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $attachmentPath)
    {
        $this->subject = $subject;
        $this->attachmentPath = $attachmentPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->attach($this->attachmentPath, [
                        'as' => 'active_users_list.csv',
                        'mime' => 'text/csv',
                    ])
                    ->view('emails.active_users_list');
    }
}
