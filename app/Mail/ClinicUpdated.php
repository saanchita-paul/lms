<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClinicUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $changedFields;
    public $clinicName;
    public $userName;
    public $userEmail;
    public $userIpAddress;

    public function __construct($changedFields,$clinicName,$userName,$userEmail,$userIpAddress)
    {

        $this->changedFields = $changedFields;
        $this->clinicName = $clinicName;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userIpAddress = $userIpAddress;
        
    }

    
    
    public function build()
    {
        return $this->view('emails.clinic_updated')
        ->with([
            'changedFields' => $this->changedFields,
            'UserName' => $this->userName,
            'userEmail' => $this->userEmail,
            'userIpAddress' => $this->userIpAddress
        ])
        ->subject($this->clinicName.' Clinic Updated');
    }
    
}
?>