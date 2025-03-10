<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\SikkaLog;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SikkaWebhookController extends Controller
{
    public function createSikkaWebhook()
    {
        $clinics = Clinic::select(
            'id',
            'sikka_soft_app_id', 'sikka_soft_app_key','sikka_soft_office_id','sikka_soft_secret_key')
            ->where('sikka_soft_app_id',"!=",NULL)
            ->where('sikka_soft_app_id',"!=","")->get();

        if($clinics->isNotEmpty())
        {
            $clinicData = [];
            foreach($clinics as $value){
                $clinicData['id'] = $value->id;
//                $clinicData['sikka_soft_app_id'] = $value->sikka_soft_app_id;
//                $clinicData['sikka_soft_app_key'] = $value->sikka_soft_app_key;
//                $clinicData['sikka_soft_office_id'] = $value->sikka_soft_office_id;
//                $clinicData['sikka_soft_secret_key'] = $value->sikka_soft_secret_key;
                $clinicData['sikka_soft_app_id'] = env('sikka_soft_app_id');
                $clinicData['sikka_soft_app_key'] = env('sikka_soft_app_key');
                $clinicData['sikka_soft_office_id'] = env('sikka_soft_office_id');
                $clinicData['sikka_soft_secret_key'] = env('sikka_soft_secret_key');

                $url = 'https://api.sikkasoft.com/webhooks/register'; // SikkaSoft webhook registration endpoint

// Set your API key
                $apiKey = env('sikka_soft_app_key');

// Set the URL of your webhook endpoint
                $yourWebhookUrl = 'https://crtx.microsite.com/api/v1/token/4534sdfgd'; // Change this to your actual webhook endpoint URL

// Set the events you want to subscribe to
                $events = ['patient'];

// Create an array with the payload data
                $payload = [
                    'url' => $yourWebhookUrl,
                    'events' => $events
                ];

// Initialize cURL
                $ch = curl_init($url);

// Set cURL options
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $apiKey
                ]);

// Execute the cURL request
                $response = curl_exec($ch);

// Check for errors
                if (curl_errno($ch)) {
                    echo 'Error: ' . curl_error($ch);
                } else {
                    echo 'Webhook registered successfully!';
                }

// Close cURL
                curl_close($ch);

dd($response);

                $webhookUrl = "https://api.sikkasoft.com/v2/webhooks";
                $headers = [
                    "Content-Type: application/json",
                ];



                $authcurl = curl_init();
                curl_setopt_array($authcurl, array(
                    CURLOPT_URL => "https://api.sikkasoft.com/v2/start?app_id=".$clinicData['sikka_soft_app_id']."&app_key=".$clinicData['sikka_soft_app_key']."&office_id=".$clinicData['sikka_soft_office_id']."&secret_key=".$clinicData['sikka_soft_secret_key'],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_POSTFIELDS => array('spam' => 'true'),
                    CURLOPT_HTTPHEADER => $headers,
                ));
                $response = curl_exec($authcurl);
                $response = json_decode($response);
                $err = curl_error($authcurl);
                $requestKey = $response[0]->request_key;
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    $payload = json_encode([
                        'url' => 'https://crtx.microsite.com/api/v1/activate/453454534534dfgegdgsgf',
                        'events' => ['patient_created', 'patient_updated', 'appointment_scheduled'],
                        'request_key' => $requestKey
                    ]);

                    // cURL request successful, now create webhook
                    $webhookcurl = curl_init();
                    curl_setopt_array($webhookcurl, array(
                        CURLOPT_URL => $webhookUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_SSL_VERIFYHOST => 0,
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_POSTFIELDS => $payload,
                        CURLOPT_HTTPHEADER => $headers,
                    ));

                    $webhookResponse = curl_exec($webhookcurl);
                    $webhookErr = curl_error($webhookcurl);

                    curl_close($webhookcurl);

                    if ($webhookErr) {
                        return response()->json([
                            'success' => true,
                            'message' => "Error creating webhook: " . $webhookErr,
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => true,
                            'message' => "Webhook created successfully!",
                        ], 200);
                    }
                }
            }
        }
    }

    public function receievePayload(Request $request){
        $input = $request->all();
        SikkaLog::create([
            'logs'=>json_encode($input)
        ]);
        return response()->json([
            'success' => true,
            'message' => "Response stored!",
        ], 200);
    }
}
