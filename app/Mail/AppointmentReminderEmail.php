<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AppointmentReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data = null;

    public $content, $subject;

    public function __construct($data, $subject = null)
    {
        if (is_string($data)) {
            // Case when $data is email content and $subject is provided
            $this->content = $data;
            $this->subject = $subject;
        } else {
            // Case when $data is appointment data
            $this->data = $data;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (is_string($this->content)) {
            // Case when template content and subject are provided
            $from_name = $this->data["clinic"]->clinic_name ?? '';
            $from_email = $this->data['clinic']->email_id ?? '';
            return $this->subject($this->subject)->from($from_email, $from_name)->html($this->content);
        } else {
            return $this->from($this->data['clinic']->email_id, $this->data['clinic']->clinic_name)
                //->replyTo('noreply@microsite.com', 'No Reply')
                ->subject('Reminder: Your Upcoming Appointment with Dr. '.$this->data['clinic']->dr_name)
                ->view('emails.appointment_reminder')
                ->with([
                    'appointment' => $this->data['appointment'],
                    'lead' => $this->data['lead'],
                    'clinic' => $this->data['clinic'],
                ]);
        }
    }
}
