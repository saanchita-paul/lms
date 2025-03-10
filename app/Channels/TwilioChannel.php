<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Exceptions\TwilioException;
use Log;

class TwilioChannel
{
    protected $twilio;

    public function __construct(Client $twilio)
    {

       
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $this->twilio = new Client($sid, $token);
    }

    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('twilio', $notification)) {
            return;
        }

        // Set up the HTTP client with SSL verification disabled
        $to = '+13865070583';

        $message = $notification->toTwilio($notifiable);

        if (!empty($to)) {
            try {
                $response = $this->twilio->messages->create($to, [
                    'from' => '+18305496144',
                    'body' => $message->content,
                ]);
                Log::info("Twilio message sent successfully to: $to");
                return $response;
            } catch (TwilioException $e) {
                \Log::error("Twilio Error: " . $e->getMessage());
            }
        } else {
            \Log::warning('No phone number provided for notification.');
        }
    }
}
