<?php

namespace App\Notifications;


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


class WherebyKnockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;

    }

    // Choose the channels to send the notification
    public function via($notifiable): array
    {
        $via = [];

        $setting = Setting::where('user_id', $notifiable->id)->first();

        if(empty($setting) || ($setting->do_not_disturb==0 && $setting->whereby_browser_notification == 1)){
            $via[] = 'broadcast';
            $via[] = 'database';
        }
        if (empty($setting) || ($setting->do_not_disturb == 0 && $setting->whereby_email_notification == 1)) {
            $via[] = 'mail';
        }

        return $via;
    }

    public function toArray($notifiable): array
    {
        return [
            'displayName' => $this->data['displayName'] ?? 'Unknown',
            'message' => $this->data['displayName']." knocked on your video consultation room.",

        ];
    }

    // Broadcast the notification
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'displayName' => $this->data['displayName'] ?? 'Unknown',
            'message' => $this->data['displayName']." knocked on your video consultation room.",

        ]);
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->from('noreply@microsite.com', 'Microsite-CRTX')
            ->subject('New Video Consultation Notification Received!')
            ->view(
                'emails.whereby_email',
                ['data' => $this->data]
            );
    }


    public function toTwilio($user,$data)
    {
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

        $message = "Good news! ".$data." is ready to join a video call: " . url('/crtx/video-consultations');

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
