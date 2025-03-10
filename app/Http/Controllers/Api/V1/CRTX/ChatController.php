<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Http\Resources\CRTX\ChatResource;
use App\Http\Services\MailSlurpService;
use App\Models\CrmCustomer;
use App\Models\Clinic;
use App\Models\CrmChat;
use App\Models\CrmNote;
use App\Models\ErrorLog;
use App\Models\ReceivedEmails;
use App\Traits\ExceptionLog;
use Illuminate\Http\Request;
use DB;
use Twilio\Rest\Client;
use Twilio\Http\CurlClient;
use Gate;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Log;
use App\Services\OpenAIAutoService;


class ChatController extends Controller
{
    use ExceptionLog;
    private MailSlurpService $mailSlurpService;
    private $openAIAutoService;

    public function __construct(MailSlurpService $mailSlurpService, OpenAIAutoService $openAIAutoService)
    {
        $this->mailSlurpService = $mailSlurpService;
        $this->openAIAutoService = $openAIAutoService;
    }
    public function storeSms(Request $request)
    {

        // DB::enableQueryLog();
        $lead      = CrmCustomer::select('first_name','last_name','phone','clinic_id')->where('id','=',$request->input('lead_id'))->first();
        // dd($lead);
        $clinic      = Clinic::select('id','clinic_name','dr_name','microsite_website','link1','link2','link3','twilio_number','twilio_subid','twilio_token')->where('id','=',$lead->clinic_id)->first();


        $clinic_name = $clinic->clinic_name;
        $clinic_drname = $clinic->dr_name;
        $link1 = $clinic->link1;
        $link2 = $clinic->link2;
        $link3 = $clinic->link3;
        $website = $clinic->microsite_website;

        $clinic_id = $clinic->id;;

        $message =  strip_tags($request->input('chat'));



        $message = str_replace("{{REPLACE_FIRST_NAME}}", $lead->first_name, $message);
        $message = str_replace("{{REPLACE_PRACTICE}}", $clinic_name, $message);

        $message = str_replace("{{REPLACE_DOCTOR}}", $clinic_drname, $message);


        $message = str_replace("{{REPLACE_LINK_1}}", $link1, $message);
        $message = str_replace("{{REPLACE_LINK_2}}", $link2, $message);
        $message = str_replace("{{REPLACE_LINK_3}}", $link3, $message);
        $message = str_replace("{{REPLACE_WEBSITE}}", $website, $message);

        $receiverNumber = $lead->phone;

        $account_sid = $clinic->twilio_subid;
        $auth_token = $clinic->twilio_token;
        $twilio_number = $clinic->twilio_number;

        $crmChat = new CrmChat();
        $crmChat->lead_id  = $request->input('lead_id');
        $crmChat->user_id = $request->input('user_id');
        // $crmChat->clinic_id = $request->input('clinic_id');
        $crmChat->chat = $message;
       // $crmChat->from = '+9988775544'; //temp pass $twilio_number
        $crmChat->from = $twilio_number;
        $crmChat->to = $receiverNumber;
        $crmChat->is_sms = '1';
        $crmChat->save();



        try {
            //$client = new Client($account_sid, $auth_token);
            $httpClient = new CurlClient([
                CURLOPT_SSL_VERIFYPEER => false,
            ]);



            $smsWebhook = Config::get('app.sms_webhook');


            // Create the Twilio client with the custom HTTP client
            $client = new Client($account_sid, $auth_token, null, null, $httpClient);

            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'statusCallback' => route('sms.crtxinbound', $smsWebhook),
                'body' => $crmChat->chat]);

        } catch (Exception $e) {
            return response()->json(["Error: " => $e->getMessage()]);
        }

        $chatResults = CrmChat::where('lead_id', $request->input('lead_id'))
        ->orderBy('crm_chats.id', 'ASC')
        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Successfully send message!',
            'data' => ['chat' => $chatResults]
        ], 200);

    }

    public function index(Request $request)
    {
        $leadId = $request->leadId;
        $clinic_id = $request->clinic_id;
        $searchTerm = $request->searchTerm;
        $unread = $request->unread;
        $perPage = 50;
        $page = $request->page;
        $timezone = "America/New_York"; // Default timezone

        // Retrieve the clinic's timezone based on the clinic_id
        $clinic = Clinic::find($clinic_id);
        if ($clinic && !empty($clinic->timezone)) {
            $timezone = $clinic->timezone;
        }

        $baseQuery = CrmCustomer::leftJoin('crm_chats', function ($join) {
            $join->on('crm_chats.lead_id', '=', 'crm_customers.id')
                ->whereNull('crm_chats.deleted_at');
        });

        $crmcustomer = clone $baseQuery;
        $crmcustomerAll = clone $baseQuery;

        if ($leadId && $clinic_id) {
            // When both leadId and clinic_id are passed
            $crmcustomer = $crmcustomer
                ->where('crm_customers.id', $leadId)
                ->where('crm_customers.clinic_id', $clinic_id)
                ->orderBy('crm_chats.created_at', 'DESC');
            $crmcustomerAll = $crmcustomerAll
                ->where('crm_customers.id', $leadId)
                ->where('crm_customers.clinic_id', $clinic_id)
                ->orderBy('crm_chats.created_at', 'DESC');
            CrmCustomer::where('id', $leadId)->update(['has_sms' => 0]);
        } elseif ($clinic_id) {
            $crmcustomer = $this->filterByClinicId($clinic_id);
            $crmcustomerAll = $this->filterByClinicId($clinic_id);
        }

        // Add unread messages only
        if($unread == true){
            $crmcustomer = $crmcustomer->where('crm_customers.has_sms', '1');
            $crmcustomerAll = $crmcustomerAll->where('crm_customers.has_sms', '1');
        }

        // Add search conditions for first name and last name
        if ($searchTerm) {
            $crmcustomer = $this->addSearchConditions($crmcustomer, $searchTerm);
            $crmcustomerAll = $this->addSearchConditions($crmcustomerAll, $searchTerm);
        }

        $crmcustomerUnreadCount = count($crmcustomerAll->where('crm_customers.has_sms', 1)->get());

        // Paginate the results
        $crmcustomer = $this->paginateResults($crmcustomer, $perPage, $page);

        // Format the result items
        $this->formatResultItems($crmcustomer, $timezone);

        // Get the total number of chats
        $totalChats = $crmcustomer->count();

        //$totalUnread = $crmcustomer->where('has_sms', 1)->count();

         // Extract first_name and last_name separately
        $firstNames = $crmcustomer->pluck('first_name')->unique()->values();
        $lastNames = $crmcustomer->pluck('last_name')->unique()->values();

        // Group the results by created_at timestamp if leadId is present
        $groupedResults = $leadId ? $this->groupResultsByDate($crmcustomer) : $crmcustomer;



        // Get the lead full name if leadId is present
        $leadFullName = $this->getLeadFullName($leadId);

        // If lead_id is passed, show firstName and lastName only once
        if ($leadId) {
            $data = [
                'crmcustomer' => $groupedResults,
                'firstNames' => $firstNames->unique(),
                'lastNames' => $lastNames->unique(),
            ];
        } else {
            $data = [
                'crmcustomer' => $groupedResults,
                'firstNames' => $firstNames,
                'lastNames' => $lastNames,
            ];
        }

        if(isset($crmcustomer[0])){
            if ($crmcustomer[0]['chat'] === null) {
                $crmcustomer = [];
                $totalChats = 0;
            }
        }

        $data = [
            'crmcustomer' => $groupedResults,
            'firstNames' => $firstNames,
            'lastNames' => $lastNames,
        ];

        try {
            return ChatResource::collection($data)->additional(['success' => true,'totalChats'=>$totalChats,'Unread'=>$crmcustomerUnreadCount,'LeadName'=>$leadFullName]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'GetPatientList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }

    private function filterByClinicId($clinic_id)
    {
        return CrmCustomer::leftJoinSub(function ($query) {
            $query->select('s1.*')
                ->from('crm_chats as s1')
                ->leftJoin('crm_chats as s2', function ($join) {
                    $join->on('s1.lead_id', '=', 's2.lead_id')
                        ->whereRaw('s1.created_at < s2.created_at');
                })
                ->whereNull('s2.lead_id');
        }, 'crm_chats', function ($join) {
            $join->on('crm_customers.id', '=', 'crm_chats.lead_id');
        })
            ->whereNotNull('crm_chats.chat')
            ->where('crm_customers.clinic_id', $clinic_id)
            ->groupBy('crm_customers.id')
            ->orderByDesc('crm_chats.created_at');
    }

    private function addSearchConditions($query, $searchTerm)
    {
        return $query->where(function ($query) use ($searchTerm) {
            $query->where('crm_customers.first_name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('crm_customers.last_name', 'LIKE', '%' . $searchTerm . '%');
        });
    }

    private function paginateResults($query, $perPage, $page)
    {
        return $query->select(
            'crm_customers.id',
            DB::raw('COALESCE(crm_customers.first_name, "") as first_name'),
            DB::raw('COALESCE(crm_customers.last_name, "") as last_name'),
            'crm_customers.has_sms',
            DB::raw('REPLACE(REPLACE(REPLACE(crm_chats.chat, "<p>", ""), "</p>", ""), "&nbsp;", " ") as chat'), // Remove <p>, </p>, and &nbsp;
            'crm_chats.from',
            'crm_chats.to',
            'crm_chats.inbound',
            'crm_chats.user_id',
            'crm_chats.created_at',
            'crm_chats.delivered',
            'crm_chats.read',
            'crm_chats.updated_at'
        )
            ->paginate($perPage, ['*'], 'page', $page);
    }

    private function formatResultItems($crmcustomer, $timezone)
    {
        foreach ($crmcustomer as $item) {
            if ($item->created_at !== null) {
                    $item->created_at = $item->created_at->setTimezone($timezone)->format('m/d/Y g:i A');
                    $item->updated_at = $item->updated_at->setTimezone($timezone)->format('m/d/Y g:i A');
            }
            $this->setDisplayName($item);
        }
    }

    private function setDisplayName($item)
    {
        $UserData = User::find($item->user_id);

        if ($UserData) {
            // Check if crm_chats.inbound is 0
            if ($item->inbound == 0) {
                // If inbound is 0, set first_name and last_name from the clinic model
                $displayName = $UserData->name;
                if ($item->user_id == 1) {
                    $displayName = 'CRTX Admin';
                }
                $nameParts = explode(' ', $displayName);
                $item->dis_last_name = '';
                if (count($nameParts) >= 2) {
                    $item->dis_first_name = $nameParts[0]; // Assign the first part to dis_first_name
                    $item->dis_last_name = $nameParts[1];  // Assign the second part to dis_last_name
                } elseif (count($nameParts) >= 1) {
                    $item->dis_first_name = $nameParts[0]; // Assign the first part to dis_first_name
                }
            } elseif ($item->inbound == 1) {
                // If inbound is 1, set dis_first_name to first_name
                $item->dis_first_name = $item->first_name;
                $item->dis_last_name = $item->last_name;
            }
        }
    }
    private function groupResultsByDate($crmcustomer)
    {
        return $crmcustomer->filter(function ($item) {
            return $item->created_at !== null; // Filter out items with null created_at
        })->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d'); // Format only if created_at is not null
        });
    }

    private function getLeadFullName($leadId)
    {
        $leadName = CrmCustomer::where('id', $leadId)->select('first_name', 'last_name')->first();
        return $leadName ? $leadName->first_name . ' ' . $leadName->last_name : '';
    }

    public function leftinbox(Request $request)
    {

        $clinic_id = $request->clinic_id;
        $crmCustomer = CrmCustomer::select("crm_customers.id", "crm_customers.first_name", "crm_customers.last_name", "crm_chats.chat","crm_chats.updated_at")
            ->leftJoin("clinics", "clinics.id", "=", "crm_customers.clinic_id")
            ->leftJoin("crm_chats", function ($join) {
                $join->on("crm_chats.lead_id", "=", "crm_customers.id")
                    ->whereRaw('crm_chats.created_at = (SELECT MAX(created_at) FROM crm_chats WHERE lead_id = crm_customers.id)');
            })
            ->where('has_sms', '=', '1')
            ->where('crm_customers.clinic_id', $clinic_id)
            ->get();



        try {
            return ChatResource::collection($crmCustomer)->additional(['success' => true]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'GetPatientList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }

    public function getContact(Request $request)
    {
        $searchTerm = request()->input('searchTerm'); // Replace 'searchTerm' with the name of your search input field
        $clinicId = explode(',',$request->input('clinic_id')); // Replace 'searchTerm' with the name of your search input field
        $results = CrmCustomer::whereIn('clinic_id', $clinicId) // Replace $clinicId with the actual clinic ID you want to filter by
        ->where(function ($query) use ($searchTerm) {
            $query->where('first_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('phone', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%');
        })
            ->selectRaw("id, CONCAT(first_name, ' ', last_name) AS FullName, email, phone")
            ->take(25) // Limit the result to 25 records
            ->get();


        try {
            return response()->json(['results' => $results]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'GetPatientList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }

    public function getMessageCount(Request $request)
    {
        $inboxId = $request->get('inbox_id');
        $unread_email_count = 0;
        if($inboxId){
            $unread_email_count = ReceivedEmails::where(['email_read'=>false,'clinic_id'=>$request->get('clinic_id')])->count();
        }
        $clinic_id = $request->clinic_id;
        $getCount = CrmCustomer::where('has_sms', '=', '1')
            ->where('crm_customers.clinic_id', $clinic_id)
            ->count();

        try {
            return response()->json(['success' => true, 'count' => $getCount + $unread_email_count,'email_unread_count'=>$unread_email_count]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'GetPatientList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }

    private function saveLeadSms($lead, $request)
    {


        $crmChat = CrmChat::create(
            [
                'user_id' => 1,
                'lead_id' => $lead->id,
                'inbound' => 1,
                'chat' => $request->Body,
                'from' => $request->From,
                'to' => $request->To,
                'is_sms' => 1,
                'platform' => 'sms'
            ]
        );



        if($lead->sms_sequence > 0 && $lead->automation == 0){
            $lead->update(['has_sms' => 1, 'automation'=> 1]);
        }else{
            $lead->update(['has_sms' => 1]);
        }



        $clinic      = Clinic::select('clinic_name','twilio_number','twilio_subid','twilio_token','sms_assistant','chat_api_key')
        ->where('id','=',$lead->clinic_id)
        ->where('auto_reply_sms','=',true)
        ->first();


        if ($clinic) {
            $userMessage = $request->Body;
            $assistantId = $clinic->sms_assistant;
            $chatApiKey  = $clinic->chat_api_key;
           

            $stopPhrases = $this->getStopPhrases();
            // Check if $userMessage contains any stop phrase
            foreach ($stopPhrases as $phrase) {
                if (stripos($phrase, $userMessage) !== false) {
                    // If stop phrase is found, return or skip sending the SMS
                    return response()->json(['message' => 'SMS not sent due to stop phrase.']);
                }
            }



            $newAIMessage = $this->openAIAutoService->generateAIResponseWithThread($userMessage, $assistantId,$chatApiKey);

            $receiverNumber = $request->From;

            $account_sid = $clinic->twilio_subid;
            $auth_token = $clinic->twilio_token;
            $twilio_number = $clinic->twilio_number;

            try {
                //$client = new Client($account_sid, $auth_token);
                $httpClient = new CurlClient([
                    CURLOPT_SSL_VERIFYPEER => false,
                ]);



                $smsWebhook = Config::get('app.sms_webhook');
                // Create the Twilio client with the custom HTTP client
                $client = new Client($account_sid, $auth_token, null, null, $httpClient);

                $client->messages->create($receiverNumber, [
                    'from' => $twilio_number,
                    'statusCallback' => route('sms.crtxinbound', $smsWebhook),
                    'body' => $newAIMessage]);

                $crmChat = CrmChat::create(
                    [
                        'user_id' => 1,
                        'lead_id' => $lead->id,
                        'inbound' => 0,
                        'chat' => $newAIMessage,
                        'from' => $request->To,
                        'to' => $request->From,
                        'is_sms' => 1,
                        'platform' => 'sms'
                    ]
                );

            } catch (Exception $e) {
                dd("Error: ". $e->getMessage());
            }
            return $crmChat;
        }

        return $crmChat;
    }

    public function pending(Request $request, $key = null){

        if ($key !== env('SMS_WEBHOOK')) {
            return response()->json([
                'message' => 'Unauthorized',
                'success' => true
            ], 404);
        }

        if ($request->SmsStatus == 'received') {
            // search for lead with this phone number
            $clinic      = Clinic::select('id','clinic_name','dr_name','lead_center','nurture_automation','email','microsite_website','version')->where('twilio_number','=',$request->To)->first();

            $lead = CrmCustomer::where('phone', $request->From)->where('clinic_id', $clinic->id)->first();

            if (isset($lead->id)) {
                $crmChat = $this->saveLeadSms($lead, $request);
                if($clinic->lead_center == "Yes"){
                    $lead->clinic_name = $clinic->clinic_name;
                    $lead->slackmsg = $clinic->clinic_name." (".$clinic->dr_name.") has received a text from a lead or consult. Please click here to check message: https://lms.microsite.com/admin/crm-customers/".$lead->id."/edit";
                    $lead->slackchannel = "#textnotification";
                    // dd($crmChat);exit;
                    $lead->notify(new \App\Notifications\SendNotification($lead));
                }
                if($clinic->lead_center != "Yes" && $clinic->nurture_automation == "Yes" && $clinic->version == '1.0'){
                    $email_subject = $clinic->clinic_name." (".$clinic->dr_name.") has received a text from a lead or consult";
                    $to = $clinic->email;
                    $email_template =  $clinic->clinic_name." (".$clinic->dr_name.") has received a text from a lead or consult. Please click here to check message: https://lms.microsite.com/admin/crm-customers/".$lead->id."/edit";
                    Mail::send(array(), array(), function ($message) use ($email_template,$to,$email_subject,$clinic) {
                                  $message->to($to)
                                    ->subject($email_subject)
                                    ->from("noreply@".$clinic->microsite_website, $clinic->clinic_name)
                                    ->replyTo($clinic->email, $clinic->clinic_name)
                                    ->setBody($email_template, 'text/plain');
                                });
                }

                if($clinic->lead_center == "Yes" && strtolower($request->Body) == "stop"){
                     CrmCustomer::where('id', $lead->id)->update(['status_id' => 9]);
                }
            }

        }

        if ($request->MessageStatus == 'delivered') {
            $chat = CrmChat::where(['to' => $request->To, 'delivered' => 0])->latest()->first();
            //echo $chat->id;exit;
            if (isset($chat->id)) {
                $chat->isDelivered();
                return ['message' => 'Message marked as delivered'];
            }
        }


        return ['message' => "Saved Successfully", 'success' => true];
    }

    public function handleSMS(Request $request)
    {
        $phoneNumber = $request->input('phone_number');

        $message = $request->input('message');

        $lead_id = $request->input('lead_id');

        $aiResponse = $this->generateAIResponse($message);

        $this->sendSMS($phoneNumber, $aiResponse,$lead_id);

        return response()->json(['message' => 'Response sent successfully']);
    }

    public function handleEmail(Request $request)
    {
        $email = $request->input('email');
        $subject = $request->input('subject');
        $message = $request->input('message');

        $aiResponse = $this->generateAIResponse($message);

        $this->sendEmail($email, $subject, $aiResponse);

        return response()->json(['message' => 'Response sent successfully']);
    }

    function getStopPhrases() {
        return array_map('trim', explode(',', env('SMS_STOP_PHRASES')));
    }
}
