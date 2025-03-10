<?php

namespace App\Notifications;

use App\Models\CrmCustomer;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;
use Twilio\Http\CurlClient;
use App\Channels\TwilioChannel;
use Log;


class AppointmentScheduled extends Notification implements ShouldQueue
{
    use Queueable;

    public $lead;
    public $appointmentdata;
    //public $clinicID;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CrmCustomer $lead,$appointmentdata)
    {
        $this->lead = $lead;
        $this->appointmentdata = $appointmentdata;
       // $clinicID = $clinicID;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {

        $via = [];

        $setting = Setting::where('user_id', $notifiable->id)->first();

        if(empty($setting) || ($setting->do_not_disturb==0 && $setting->appointment_browser_notification == 1)){
            $via[] = 'broadcast';
            $via[] = 'database';
        }
        if (empty($setting) || ($setting->do_not_disturb == 0 && $setting->appointment_email_notification == 1)) {
            $via[] = 'mail';
        }

        return $via;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'lead' => $this->lead,
            'message' => 'Another New Patient Appointment Set! :- ' . $this->lead->first_name. ' ' . $this->lead->last_name,
        ];
    }

    /**
     * Get the BroadcastMessage representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'lead' => $this->lead,
            'message' => 'Another New Patient Appointment Set! :- ' . $this->lead->first_name. ' ' . $this->lead->last_name,
        ]);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->from('noreply@microsite.com', 'Microsite-CRTX')
        ->subject('Exciting News: New Patient Appointment Scheduled!')
        ->view(
            'emails.appointment_scheduled', 
            ['data' => $this->appointmentdata,'crmCustomer' => $this->lead]
        );
    }

    /**
     * Get the Twilio SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return void
     */
    public function toTwilio($user,$lead)
    {
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));  
        
        $message = 'Good news! A new patient appointment is set. Check your email for details. Click Link To View Lead: ' . url('/crtx/patient-profile/' . $lead->id);

        $phoneNumber = $user->phone;        
        if (!empty($phoneNumber)) {
            $twilio->messages->create(
                $phoneNumber, 
                [
                    'from' => env('TWILIO_FROM'),                
                    'body' => $message,
                ]
            );
        } else {
            // Optionally, you can handle the case where $phoneNumber is empty
            // For example, log an error or take some other action
            Log::warning('No phone number provided for notification.');
        }
    }
}
