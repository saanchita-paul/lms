<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SendNotificationTrait;
use App\Notifications\AppointmentScheduled;
use App\Services\WherebyService;
use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\WherebyData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Models\Setting;
use App\Notifications\WherebyKnockNotification;

class WherebyController extends Controller
{

    use SendNotificationTrait;

    public function handleWherebyWebhook(Request $request)
    {

        $wherebyDataJson = file_get_contents('php://input');
        $whereby = json_decode($wherebyDataJson, true);

        $wherebyService = new WherebyService();
        $wherebyData = $wherebyService->processWebhook($whereby);


        if ($wherebyData) {
            $displayName = $whereby['data']['displayName'] ?? 'Unknown';
            $roomName = $whereby['data']['roomName'] ?? 'Unknown';

            $this->sendKnockNotification($roomName, $displayName);
            if ($roomName) {
                $clinic = Clinic::where('whereby_link', 'like', '%' . $roomName . '%')->first();
                if (!empty($clinic)) {
                // Retrieve users with role id 5
                    $users = $clinic->managers()->whereHas('roles', function ($q) {
                        $q->where('id', 5);
                    })->get();


                    foreach ($users as $user) {

                        $setting = Setting::where('user_id', $user->id)->first();
                        if ($setting) {
                            if ($setting->do_not_disturb == 0 && $setting->whereby_text_notification == 1) {
                                // Send notification to each user
                                $notification = new WherebyKnockNotification($roomName, $displayName);
                                $notification->toTwilio($user,$displayName);
                            }
                        } else {
                            // User does not have settings saved, send notifications by default
                            $notification = new WherebyKnockNotification($roomName, $displayName);
                            $notification->toTwilio($user,$displayName);
                        }
                    }
                }
            }
        }

        return response()->json(['status' => 'success'], 200);
    }


    public function activateClinic(Request $request)
    {
        $clinic_id = $request->clinic_id;
        $clinic_name = $request->clinic_name;
        $user_id = $request->user_id;
        $user_name = $request->user_name;
        $user_email = $request->user_email;

        $clinic = Clinic::findOrFail($clinic_id);
        $clinicName = preg_replace('/[^a-zA-Z0-9_\-.]/', '-', $clinic->clinic_name);

        $payload = [
            "isLocked" => true,
            "roomNamePrefix" => $clinicName,
            "roomNamePattern" => "uuid",
            "roomMode" => "normal",
            "endDate" => "2030-12-31",
            "fields" => ["hostRoomUrl"]
        ];

        // Call Whereby API to create a room
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('WHEREBY_API_TOKEN')
        ])->post('https://api.whereby.dev/v1/meetings', $payload);

//        dd($response->body());
        if ($response->failed()) {
            return response()->json(['error' => 'Failed to create room'], 500);
        }

        $roomData = $response->json();
        $clinic->whereby_link = $roomData['hostRoomUrl'];
        $clinic->save();

        $user = (object) [
            'id' => $user_id,
            'name' => $user_name,
            'email' => $user_email
        ];

        $clinic = Clinic::find($clinic_id);

        if ($clinic->whereby_activation_status === 'activated') {
            return response()->json(['success' => true, 'whereby_link' => $clinic->whereby_link]);
        }

        $clinic->whereby_activation_status = 'pending';
        $clinic->save();

        Mail::send('emails.activate_video_consultation', [
            'clinic_id' => $clinic_id,
            'clinic_name' => $clinic_name,
            'user_name' => $user_name,
            'user_id' => $user_id,
            'user_email' => $user_email,
        ], function($message) use ($user) {
            $message->from(env('NOREPLY_EMAIL'), 'Microsite-CRTX')
                ->to(explode(',', env('VIDEO_CONSULTATION_ACTIVATE_EMAILS')))
                ->subject('Activate Clinic Video Consultation Link');
        });

        return response()->json([
            'message' => 'Activation email sent successfully!',
            'hostRoomUrl' => $roomData['hostRoomUrl'],
            'roomUrl' => $roomData['roomUrl']
        ]);
    }

    public function getActivationState($clinic_id)
    {
        $clinic = Clinic::find($clinic_id);
        if (!$clinic) {
            return response()->json(['error' => 'Clinic not found'], 404);
        }

        return response()->json([
            'whereby_activation_status' => $clinic->whereby_activation_status,
            'whereby_link' => $clinic->whereby_link ?? null,
        ]);
    }


}
