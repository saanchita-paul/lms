<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\CrmCustomer;

class LeadCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $crmCustomer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($crmCustomer)
    {
        $this->crmCustomer = $crmCustomer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.leadCreateMail')
                    ->with('crmCustomer', $this->crmCustomer);
    }
}
