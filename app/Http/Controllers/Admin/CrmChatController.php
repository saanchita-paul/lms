<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCrmChatRequest;
use App\Http\Requests\StoreCrmChatRequest;
use App\Http\Requests\UpdateCrmChatRequest;
use App\Models\CrmCustomer;
use App\Models\Clinic;
use App\Models\CrmChat;
use App\Models\CrmNote;
use Exception;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Twilio\Rest\Client;
use Twilio\Http\CurlClient;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Models\Tag;
use App\Models\TagLeadMapping;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Log;
use App\Services\OpenAIAutoService;

use Illuminate\Mail\Message;

class CrmChatController extends Controller
{
    protected $openAIAutoService;

    public function __construct(OpenAIAutoService $openAIAutoService)
    {
        $this->openAIAutoService = $openAIAutoService;
    }
    public function index(Request $request)
    {
        abort_if(Gate::denies('crm_chat_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $crmCustomer = CrmCustomer::select("crm_customers.id","crm_customers.first_name","crm_customers.last_name","clinics.lead_center","clinics.dr_name","clinics.clinic_name")->leftJoin("clinics", "clinics.id", "=", "crm_customers.clinic_id")->where('has_sms','=','1')->where('clinics.lead_center','=','Yes')->orderBy('crm_customers.updated_at','DESC')->get();

        return view('admin.crmChats.index', compact('crmCustomer'));
    }

    public function create()
    {
        abort_if(Gate::denies('crm_chat_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = CrmCustomer::all()->pluck('first_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        //  return view('admin.crmChats.create', compact('customers'));
    }

    public function store(StoreCrmChatRequest $request)
    {


        //
        $lead      = CrmCustomer::select('first_name','last_name','phone')->where('id','=',$request->input('lead_id'))->first();
        $clinic      = Clinic::select('clinic_name','twilio_number','twilio_subid','twilio_token')->where('id','=',$request->input('clinic_id'))->first();

        $receiverNumber = $lead->phone;
        $message =  $request->input('chat');

        $account_sid = $clinic->twilio_subid;
        $auth_token = $clinic->twilio_token;
        $twilio_number = $clinic->twilio_number;

        $request['from']  = $twilio_number;
        $request['to']  = $receiverNumber;

        $crmChat = CrmChat::create($request->all());




        try {

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'statusCallback' => route('sms.inbound', env('SMS_WEBHOOK')),
                'body' => $message]);

            dd('SMS Sent Successfully.');

        } catch (Exception $e) {
            dd("Error: ". $e->getMessage());
        }
        //
        return $crmChat;
        //return redirect()->route('admin.crm-chats.index');
    }

    public function show(CrmChat $crmChat)
    {
        abort_if(Gate::denies('crm_chat_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $crmChat->load('customer');

        return view('admin.crmChats.show', compact('crmChat'));
    }

    public function destroy(CrmChat $crmChat)
    {
        abort_if(Gate::denies('crm_chat_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $crmChat->delete();

        return back();
    }

    public function massDestroy(MassDestroyCrmChatRequest $request)
    {
        CrmChat::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function pending(Request $request, $key = null){

        if ($key != env('SMS_WEBHOOK')) {
            return abort(401);
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

    public function verifysms(Request $request){

        $phone = "+1".preg_replace("/[^a-zA-Z0-9]/", "", $request->phone );
        $newphone = "+1".preg_replace("/[^a-zA-Z0-9]/", "", $request->newphone );

        if($request->newphone != ''){
            $phone = $newphone;
        }

        $email = $request->email;
        $newemail = $request->newemail;

        if ($newemail != '' && $newemail != $email) {
            $email = $newemail;
        }

        if($request->emailcode != '' && $request->email != '') {

            if ($request->has('emailcode')) {

                $email_lead = CrmCustomer::where('email', $email)->where('phone', $phone)->where('phone_form', '=', 'Web Form')->where('email_code',$request->emailcode)->first();

                if ($email_lead->email_code == $request->emailcode && isset($email_lead->id)) {

                    $lead_score = $email_lead->lead_score;
                    if (empty($lead_score)) {
                        $lead_score = 0;
                        if ($email_lead->phone_form === "Phone Call") {
                            $lead_score = random_int(480, 500);
                        } elseif ($email_lead->phone_form === "Web Form") {
                            $lead_score = random_int(280, 300);
                        } else {
                            $lead_score = 0;
                        }
                    }
                    $lead_score += random_int(45, 50);
                    CrmCustomer::where('id', $email_lead->id)->where('email_verified', 0)->update(['lead_score' => $lead_score, 'email_verified' => 1]);

                    // Check if the 'Email Verified' tag exists
                    $tag = Tag::where('tagName', 'Email Verified')->where('clinic_id', $email_lead->clinic_id)->first();

                    // If the tag does not exist, create it
                    if (!$tag) {
                        $tag = new Tag();
                        $tag->tagName = 'Email Verified';
                        $tag->clinic_id = $email_lead->clinic_id;
                        $tag->save();
                    }

                    // Create a new TagLeadMapping entry
                    $tagLeadMapping = new TagLeadMapping();
                    $tagLeadMapping->tag_id = $tag->id;
                    $tagLeadMapping->lead_id = $email_lead->id;
                    $tagLeadMapping->save();
                } else {
                    $errors = [];
                    $errors['emailcode'] = 'Invalid, please try again later.';
                    return ['errors' => $errors, 'success' => false];
                }
            }
        }

        if ($request->vcode != '' && $request->phone != '') {
            $phone = "+1".preg_replace("/[^a-zA-Z0-9]/", "", $request->phone );
            $newphone = "+1".preg_replace("/[^a-zA-Z0-9]/", "", $request->newphone );

            if($request->newphone != ''){
                $phone = $newphone;
            }
            if($request->newphone != ''){
                $lead = CrmCustomer::where('phone', $newphone)->where('email', $email)->where('phone_form', '=','Web Form')->where('phone_code',$request->vcode)->first();
            }else{
                $lead = CrmCustomer::where('phone', $phone)->where('email', $email)->where('phone_form', '=','Web Form')->where('phone_code',$request->vcode)->first();
            }

            if (isset($lead->id)) {
                $lead->update(['badge' => "SMS Verified" ]);

                // Check if the 'SMS Verified' tag exists
                $tag = Tag::where('tagName', 'SMS Verified')->where('clinic_id', $lead->clinic_id)->first();

                // If the tag does not exist, create it
                if (!$tag) {
                    $tag = new Tag();
                    $tag->tagName = 'SMS Verified';
                    $tag->clinic_id = $lead->clinic_id;
                    $tag->save();
                }

                // Create a new TagLeadMapping entry
                $tagLeadMapping = new TagLeadMapping();
                $tagLeadMapping->tag_id = $tag->id;
                $tagLeadMapping->lead_id = $lead->id;
                $tagLeadMapping->save();

                $lead_score = $lead->lead_score;
                if(empty($lead_score)){
                    $lead_score = 0;
                    if ($lead->phone_form === "Phone Call") {
                        $lead_score = random_int(480, 500);
                    }elseif ($lead->phone_form === "Web Form") {
                        $lead_score = random_int(280, 300);
                    } else {
                        $lead_score = 0;
                    }
                }
                $lead_score += random_int(45,50);
                CrmCustomer::where('id', $request->vcode)->where('phone_verified',0)->update(['lead_score' => $lead_score, 'phone_verified' => 1 ]);

                return ['message' => "Verified Successfully", 'success' => true];
            }else{
                $errors = [];
                $errors['vcode'] = 'Invalid, please try again later.';
                return ['errors' => $errors , 'success' => false];
            }
        }
        else{
            return ['error' => "Error, please try again later.", 'success' => false];
        }
    }

    public function resendverifysms(Request $request){

        //resend email
        if ($request->email != '') {
            $receiveremail = $request->input('email');
            $leaddata = CrmCustomer::where('email', $request->email)->where('phone_form', '=','Web Form')->get()->last();
            if(isset($leaddata->id)){
                if($request->input('newemail') != '' && $receiveremail != $request->input('newemail')){
                    $receiveremail = $request->input('newemail');
                    CrmCustomer::where('id', $leaddata->id)->update(['email' => $receiveremail ]);
                }
                /* writes number into string. */

                $lead_id = $leaddata->id;
                $lead_num = (string) $lead_id;
                /* Reverse the string. */
                $revlead = strrev($lead_num);
                /* writes string into int. */
                $reverseleadid = $revlead;

                $clinicid= $leaddata->clinic_id;

                $emailvcode = $leaddata->email_code;


                $email_subject = "Dental Implant Hotline Email verification code";
                $email_template = "Your Email Verification code is: ".$emailvcode;
                $lead = CrmCustomer::with(['clinic'])->where('crm_customers.id', $lead_id)->first();
                    if($lead->clinic->smtpMailer == '')  {
                        Mail::mailer('smtp')->send(array(), array(), function ($message) use ($email_template,$receiveremail,$email_subject) {
                            $message->to($receiveremail)
                                ->subject($email_subject)
                                ->from('consultation@thedentalimplanthotline.com', 'The Dental Implant Hotline')
                                ->setBody($email_template, 'text/html');
                        });
                    }else{
                        config([
                            'mail.mailers.smtp.host' => $lead->clinic->smtpServer,
                            'mail.mailers.smtp.port' => $lead->clinic->smtpPort,
                            'mail.mailers.smtp.encryption' => 'tls',
                            'mail.mailers.smtp.username' => $lead->clinic->smtpUsername,
                            'mail.mailers.smtp.password' => $lead->clinic->smtpPassword,
                        ]);
                        if($lead->clinic->lead_center == "Yes"){
                            Mail::purge();
                            Mail::send([], [], function (Message $message) use ($email_template,$receiveremail,$email_subject) {
                                $message->to($receiveremail)
                                    ->subject($email_subject)
                                    ->from('consultation@thedentalimplanthotline.com', 'The Dental Implant Hotline')
                                    ->setBody($email_template, 'text/html');
                            });
                        }else{
                            Mail::purge();
                            Mail::send([], [], function (Message $message) use ($email_template,$receiveremail,$email_subject,$lead) {
                                $message->to($receiveremail)
                                    ->subject($email_subject)
                                    ->from("admin@".$lead->clinic->microsite_website, $lead->clinic->clinic_name)
                                    ->replyTo($lead->clinic->email_id, $lead->clinic->clinic_name)
                                    ->setBody($email_template, 'text/html');
                            });
                    }

                }
                $CrmNote = new CrmNote;
                $CrmNote->note = "Verification Email ReSent";
                $CrmNote->user_id = 1;
                $CrmNote->customer_id = $lead_id;
                $CrmNote->save();

            }
        }
        //end of resend email

        if ($request->phone != '') {
            $phone = "+1".preg_replace("/[^a-zA-Z0-9]/", "", $request->phone );
            $lead = CrmCustomer::where('phone', $phone)->where('phone_form', '=','Web Form')->get()->last();
            if (isset($lead->id)) {
                $clinic      = Clinic::select('clinic_name','twilio_number','twilio_subid','twilio_token','leadcenteraccountmanager','dr_name','hotline_phone_number')->where('id','=',$lead->clinic_id)->first();
                $receiverNumber = $phone;

                if($request->newphone != ''){
                    $receiverNumber = "+1".preg_replace("/[^a-zA-Z0-9]/", "", $request->newphone );
                    CrmCustomer::where('id', $lead->id)->update(['phone' => $receiverNumber ]);
                }

                $account_sid = $clinic->twilio_subid;
                $auth_token = $clinic->twilio_token;
                $twilio_number = $clinic->twilio_number;
                $phonecode = $lead->phone_code;
                $message = "Dental Implant Hotline verification code: ".$phonecode;

                $request['from']  = $twilio_number;
                $request['to']  = $receiverNumber;
                $request['chat'] = $message;
                $request['user_id'] = 1;
                $request['is_sms'] = 1;
                $request['lead_id'] = $lead->id;
                $crmChat = CrmChat::create($request->all());

                try {

                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create($receiverNumber, [
                        'from' => $twilio_number,
                        'statusCallback' => route('sms.inbound', env('SMS_WEBHOOK')),
                        'body' => $message]);

                } catch (Exception $e) {
                    //dd("Error: ". $e->getMessage());
                }

                return ['message' => "Resent Successfully", 'success' => true];
            }else{
                return ['error' => "Error in sending SMS", 'success' => false];
            }
        }
        else{
            return ['error' => "Error, please try again later.", 'success' => false];
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

        $crmchats = CrmChat::where('lead_id', $lead->id)->where('inbound',1)->count();

        if ($crmchats == 1) {

            $lead_score = $lead->lead_score;
            if(empty($lead_score)){
                $lead_score = 0;
                if ($lead->phone_form === "Phone Call") {
                    $lead_score = random_int(480, 500);
                }elseif ($lead->phone_form === "Web Form") {
                    $lead_score = random_int(280, 300);
                } else {
                    $lead_score = 0;
                }
            }

            $lead_score += random_int(91,99);

            $lead->update(['lead_score' => $lead_score]);
        }

        
       
        $clinic      = Clinic::select('clinic_name','twilio_number','twilio_subid','twilio_token','sms_assistant','chat_api_key')->where('id','=',$lead->clinic_id)->where('auto_reply_sms','=',true)->first();

       

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

    public function inbox(Request $request){

        $chats = CrmChat::with(['customer'])->where('lead_id','=',$request->leadId)->orderBy('id', 'DESC')->get();
        $crmcustomer = CrmCustomer::with(['clinic'])->leftJoin("crm_chats", "crm_chats.lead_id", "=", "crm_customers.id")->where('crm_customers.id','=',$request->leadId)->get();

        return view('admin.crmChats.index-child', compact('chats','crmcustomer'));

    }
    public function leftinbox(Request $request)
    {

        $crmCustomer = CrmCustomer::select("crm_customers.id","crm_customers.first_name","crm_customers.last_name","clinics.lead_center","clinics.dr_name","clinics.clinic_name")->leftJoin("clinics", "clinics.id", "=", "crm_customers.clinic_id")->where('has_sms','=','1')->where('clinics.lead_center','=','Yes')->orderBy('crm_customers.updated_at','DESC')->get();

        return view('admin.crmChats.index-leftinbox', compact('crmCustomer'));
    }

    function getStopPhrases() {
        return array_map('trim', explode(',', env('SMS_STOP_PHRASES')));
    }

    public function vapiwebhook(Request $request, $key = null)
    {

        if($request['event'] == 'call_analyzed' || $request['event'] == 'call_started'){
            return;
        }

        $clinicid =  $key;

        if($clinicid > 0){
            $clinic      = Clinic::select('clinic_name','twilio_number','twilio_subid','twilio_token','dr_name')->where('id','=',$clinicid)->first();
            $link = '';
            $practice_name = $clinic->clinic_name;
            if ($request->has('link')) {
                $link = $request->input('link');
            }
            if ($request->has('practice_name')) {
                $practice_name = $request->input('practice_name');
            }

            //$smstext = "Thank you for calling ". $practice_name .". To schedule your consultation, please go to ". $link;
            $smstext = "Thank you for contacting ". $practice_name ."! If you have any additional questions, please text me at this number. Ready to schedule an appointment? Click here: ". $link ." .
We look forward to assisting you!";

            if ($request->has('smstext')) {
                $smstext = $request->input('smstext');
            }

            if($request['phone'] != '' )  {
                $customernumber = $request['phone'];
            }
            elseif($request['event'] == 'call_ended'){
                Log::info('SMS for Voice Agent', ['data' => $request['call']['from_number']]);
                $customernumber = $request['call']['from_number'];
            }
            else{
                $customernumber = $request['message']['customer']['number'];
            }

            $customernumber = str_replace('+1', '', $customernumber);
            $customernumber = "+1" . preg_replace("/[^a-zA-Z0-9]/", "", $customernumber);

            $account_sid = $clinic->twilio_subid;
            $auth_token = $clinic->twilio_token;
            $twilio_number = $clinic->twilio_number;

            $lead = CrmCustomer::where('phone', 'like' ,'%'.$customernumber.'%')->where('clinic_id', $clinicid)->first();
            if (isset($lead->id) && !isset($request['notes']))
            {
                $request['from']  = $twilio_number;
                $request['to']  = $customernumber;
                $request['chat'] = $smstext;
                $request['user_id'] = 1;
                $request['is_sms'] = 1;
                $request['lead_id'] = $lead->id;
                $crmChat = CrmChat::create($request->all());
            }
            if($request['notes']!=''){
                    $CrmNote = new CrmNote;
                    $CrmNote->note = $request['notes'];
                    $CrmNote->user_id = 1;
                    $CrmNote->customer_id = $lead->id;
                    $CrmNote->save();
            }
            if (isset($lead->id) && !isset($request['notes']))
            {
                try {

                $client = new Client($account_sid, $auth_token);
                $client->messages->create($customernumber, [
                    'from' => $twilio_number,
                    'statusCallback' => route('sms.inbound', env('SMS_WEBHOOK')),
                    'body' => $smstext]);

                } catch (Exception $e) {
                //dd("Error: ". $e->getMessage());
                return ['error' => "Error in sending SMS", 'success' => false];
                }
            }
            return ['message' => "SMS sent Successfully", 'success' => true];

        }

    }
}
