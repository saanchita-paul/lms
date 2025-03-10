<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $content, $subject;
    public $appointment, $lead, $clinic;

    /**
     * Create a new message instance.
     *
     * @param array|string $data
     * @param string|null $subject
     */
    public function __construct($data, $subject = null, $clinic = null)
    {
        if (is_string($data)) {
            // Case when $data is email content and $subject is provided
            $this->content = $data;
            $this->subject = $subject;
            $this->clinic = $clinic ?? null;
        } else {
            // Case when $data is appointment data
            $this->appointment = $data['appointment'] ?? null;
            $this->lead = $data['lead'] ?? null;
            $this->clinic = $data['clinic'] ?? null;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->content && $this->subject) {
            // Case when template content and subject are provided
            $from_name = $this->clinic->clinic_name ?? '';
            $from_email = $this->clinic->email_id ?? '';
            return $this->subject($this->subject)->from($from_email, $from_name)->html($this->content);
        } else {
            // Case when appointment, lead, and clinic data are used
            $from_name = $this->clinic->clinic_name ?? '';
            $from_email = $this->clinic->email_id ?? '';

            return $this->from($from_email, $from_name)
                        ->replyTo($from_email, $from_name)
                        ->subject('Your appointment has been successfully scheduled.')
                        ->view('emails.appointment')
                        ->with([
                            'appointment' => $this->appointment,
                            'lead' => $this->lead,
                            'clinic' => $this->clinic,
                        ]);
        }
    }
}
