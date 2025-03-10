<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SendNotificationTrait;
use App\Models\CrmCustomerCallDetail;
use App\Notifications\LeadReconnecting;
use Illuminate\Http\Request;
use App\Models\Callrail;
use App\Http\Requests\StoreCrmCustomerRequest;
use App\Http\Requests\StoreCrmChatRequest;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use App\Models\CrmChat;
use App\Models\Source;
use App\Models\CrmCall;
use App\Models\Calendly;
use App\Models\CrmNote;
use Carbon\Carbon;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use DateTime;
use Illuminate\Support\Facades\Http;
use App\Notifications\GreenLeadCreated; // Import the notification class
use App\Models\Setting;
use App\Services\ImmediateTemplateService;
use App\Models\Tag;
use App\Models\TagLeadMapping;

class CallrailController extends Controller
{
    use SendNotificationTrait;

    protected $immediatetemplateService;

    public function __construct(ImmediateTemplateService $immediatetemplateService)
    {
        $this->immediatetemplateService = $immediatetemplateService;
    }

    public function savedata(Request $request)
    {

        $data = file_get_contents('php://input');
        $request['jdata'] = $data;



        //start Code to exclude downloadable guide form hormonereset
        if ($request['cr_company_id'] == '927880378' && strpos($request['jdata'], "cSVIBuOhqKZ5MR248M") !== FALSE) {
            echo "excluded";
            http_response_code(200);
            exit;
        }


        //End of code
        if ($request['cr_company_id'] == '157408831' && $request['event_type'] == 'form') {
            if (!empty($_POST)) {
                $request['jdata'] = '{"form_data":' . json_encode($_POST) . '}';
            } else {
                $request['jdata'] = '{"form_data":' . $data . '}';
            }
        }




        $callrail = Callrail::create($request->all());
        $leadid = $this->createlead($request);
        if ($leadid['id'] > 0) {
            Callrail::where('id', $callrail['id'])->update(['lead_id' => $leadid['id'], 'lead_convert' => 1, 'lms_clinic_id' => $leadid['clinic_id']]);
        }
        if ($leadid['id'] == -2) {
            Callrail::where('id', $callrail['id'])->update(['lead_id' => -2, 'lead_convert' => -2, 'lms_clinic_id' => -2]);
        }


        http_response_code(200); //Acknowledge you received the response

    }


    public function callrailpostcall(Request $request)
    {
        $data = file_get_contents('php://input');
        $request['jdata'] = $data;
        $request['lead_convert'] = 99;
        $callrail = Callrail::create($request->all());
        $leadid = $this->updatephonelead($request);
        if ($leadid['id'] > 0) {
            Callrail::where('id', $callrail['id'])->update(['lead_id' => $leadid['id'], 'lead_convert' => 100, 'lms_clinic_id' => $leadid['clinic_id']]);
        }
        if ($leadid['id'] == -2) {
            Callrail::where('id', $callrail['id'])->update(['lead_id' => -200, 'lead_convert' => -200, 'lms_clinic_id' => -2]);
        }

        http_response_code(200);
    }

    public function updatephonelead(Request $request)
    {
        $clinic = Clinic::where('callrail_company', $request['cr_company_id'])->first();
        $phonenumber = $request['customer_phone_number'];
        $crmcustomer = CrmCustomer::where('phone', $phonenumber)->where('phone_form', 'Phone Call')->where('clinic_id', $clinic->id)->first();

        if (isset($crmcustomer['id']) && $crmcustomer['id'] > 0) {

            $todaytime = Carbon::now();
            $todaysdate = $todaytime->toDateTimeString();

            $date1 = date_create($crmcustomer['created_at']);
            $date2 = date_create($todaysdate);
            $diff = date_diff($date1, $date2);
            $diffdays = $diff->format("%a");

           // $crmcustomer->callrail_details = $request['jdata'];
            $allcalldata = json_decode($request['jdata'],true);

           // $crmcustomer->call_summary = $allcalldata['call_summary'];
           // $crmcustomer->full_transcript = $allcalldata['transcription'];

            if ($diffdays > 90) {
                $crmcustomer->created_at = $todaysdate;
            }

            $crmcustomer->save();

            $crmcustomerCallDetail = CrmCustomerCallDetail::create([
                'crm_customer_id' => $crmcustomer->id,
                'call_summary' => $allcalldata['call_summary'],
                'full_transcript' => $allcalldata['transcription'],
                'audio_file_url' => null,
                'callrail_details'  => $request['jdata'],
                'callrail_time' => null
            ]);

            // Get Recording data and save it to storage

            $timezone = "America/New_York"; // Default timezone

            // Check if a clinic is associated with the resource and has a timezone
            if ($clinic && !empty($clinic->timezone)) {
                $timezone = $clinic->timezone;
            }

            $callRail_time = '';

            $play_file = '';

            if (!empty($crmcustomerCallDetail->callrail_details)) {
                $callRailDetails = json_decode($crmcustomerCallDetail->callrail_details, true);

                // audio file download start

                if(isset($callRailDetails['recording_redirect']) && isset($callRailDetails['resource_id']) && $callRailDetails['recording_redirect'] != ''){
                    $queryString = parse_url($callRailDetails['recording_redirect'], PHP_URL_QUERY);
                    parse_str($queryString, $queryParams);
                    $accessKey = isset($queryParams['access_key']) ? $queryParams['access_key'] : null;
                    //            $accessKey = 'a44381fe5fbd4b7a552a';
                    $fileUrl = "https://microsite.callreports.com/calls/".$callRailDetails['resource_id']."/recording/redirect?access_key=".$accessKey;
                    $fileName = 'downloaded_file.mp3';

                    $response = Http::get($fileUrl);

                    // Store the file in the storage/app directory
                    $todayDate = Carbon::now()->format('d_m_Y');
                    $filename = $clinic->id . '/' . $crmcustomer->id . '/' . uniqid() . '_'.$todayDate.'.mp3';
                    $disk = 'public';
                    Storage::disk($disk)->put($filename, $response->body());
                    $play_file = Storage::disk($disk)->url($filename);

                    $play_file = str_replace('http', 'https', $play_file);

                    $device_request_time = Carbon::parse(json_decode($crmcustomerCallDetail->callrail_details)->created_at);

                    if ($timezone == "America/New_York") {
                        $callRail_time = $device_request_time->format('Y-m-d H:i:s');
                    } else {
                        $callRail_time = $device_request_time->setTimezone($timezone)->format('Y-m-d H:i:s');
                    }
                }
            }

            $crmcustomerCallDetail->audio_file_url = $play_file;
            $crmcustomerCallDetail->callrail_time = $callRail_time;
            $crmcustomerCallDetail->save();

            //

            $CrmNote = new CrmNote;
            $CrmNote->note = "Post Call Summary Added";
            $CrmNote->user_id = 1;
            $CrmNote->customer_id = $crmcustomer['id'];
            $CrmNote->save();

            // Lead scoring for keyword and duration

            $lead_score = $crmcustomer['lead_score'];
            if(empty($lead_score)){
                $lead_score = 0;
                if ($crmcustomer['phone_form'] === "Phone Call") {
                    $lead_score = random_int(480, 500);
                }elseif ($crmcustomer['phone_form'] === "Web Form") {
                    $lead_score = random_int(280, 300);
                } else {
                    $lead_score = 0;
                }
            }

            $crmCustomerData = json_decode($crmcustomerCallDetail->callrail_details, true);

            $formattedDuration = $crmCustomerData['duration'];

            // Parse the duration string to get the minutes
            $duration = (int) filter_var($formattedDuration, FILTER_SANITIZE_NUMBER_INT);


            // Determine the lead score based on the call duration

            if($duration > 30 && $duration < 60) {
                $lead_score += random_int(75, 80);
            } elseif ($duration >= 60 && $duration <= 120) {
                $lead_score += random_int(81, 89);
            } elseif ($duration > 120) {
                $lead_score += random_int(91, 99);
            }

            // Words that increase the score
            $positiveWords = [
                'Schedule', 'Booking', 'Appointment', 'Consultation', 'Treatment', 'Availability',
                'Pricing', 'Urgent', 'Urgency', 'Immediate', 'Immediately', 'Visit', 'Treatment options',
                'Next steps', 'Procedure', 'Follow-up', 'Assessment', 'Financing options'
            ];

            // Words that decrease the score
            $negativeWords = [
                'Free', 'Government grants', 'Cheap', 'Discount', 'Deal', 'Bargain', 'Low cost',
                'Just looking', 'Maybe', 'Later', 'Not sure', 'Shop around', 'High price', 'Too high',
                'Expensive', "Can't afford", 'No budget', 'Just wondering', 'Unnecessary'
            ];

            $positiveWordsFound = false;
            $negativeWordsFound = false;
            // Check if any positive word found in call summary or full transcript
            foreach ($positiveWords as $word) {
                if (stripos($crmcustomer['call_summary'], $word) !== false || stripos($crmcustomer['full_transcript'], $word) !== false) {
                    if(!$positiveWordsFound){
                        // Add points between 91 to 99
                        $lead_score += random_int(91, 99);
                        $positiveWordsFound = true;
                    }

                }
            }
            // Check if any negative word found in call summary or full transcript
            foreach ($negativeWords as $word) {
                if (stripos($crmcustomer['call_summary'], $word) !== false || stripos($crmcustomer['full_transcript'], $word) !== false) {
                    if(!$negativeWordsFound) {
                        // Subtract points between 91 to 99
                        $lead_score -= random_int(91, 99);
                        $negativeWordsFound = true;
                    }
                }
            }

            CrmCustomer::where('id', $crmcustomer['id'])->update(['lead_score' => $lead_score ]);

            //-----------------------------------------------------

            return $crmCustomer = CrmCustomer::select('id', 'clinic_id')->find($crmcustomer['id']);
        }
    }




    public function createlead(Request $request)
    {
        $event_type = $request['event_type'];




        $clinic = Clinic::where('callrail_company', $request['cr_company_id'])->first();


        //echo "<pre>Company:";dd($request['cr_company_id']);exit;
        $request['clinic_id'] = $clinic->id;

        if ($event_type == 'form') {
            $request['phone_form'] = 'Web Form';
            if (isset($request['form_data'])) {
                $formdata = $request['form_data'];
            } else {
                $request['form_data'] = json_decode($request->getContent(), true);
                $formdata = $request['form_data'];
            }

            $filleddata = '';
            $emailkey = '';
            $phonekey = '';
            $email = '';
            $fnamekey = '';
            $lnamekey = '';
            $formdataarry = json_decode(json_encode($formdata), true);

            if(isset($formdataarry)){
                foreach ($formdataarry as $k => $v) {
                    if (stripos($k, 'Email') !== false || stripos($k, 'Field14') !== false) {
                        $emailkey = substr($k, strrpos($k, '_'));
                    }
                    if (stripos($k, 'Phone') !== false || stripos($k, 'Field15') !== false || stripos($k, 'phone_number') !== false || stripos($k, 'Phone Number') !== false)  {
                        $phonekey = substr($k, strrpos($k, '_'));
                    }
                    if (stripos($k, 'First Name') !== false || stripos($k, 'first-name') !== false || stripos($k, 'Field12') !== false) {
                        $fnamekey = substr($k, strrpos($k, '_'));
                    }
                    if (stripos($k, 'Last Name') !== false || stripos($k, 'last-name') !== false || stripos($k, 'Field13') !== false) {
                        $lnamekey = substr($k, strrpos($k, '_'));
                    }
                }
            }

            $jformdata = json_encode($formdata, JSON_PRETTY_PRINT);
            $filleddata = str_replace('", "', '<br>', $jformdata);
            if (isset($formdata["$emailkey"])) {
                $email = $formdata["$emailkey"];
            }

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
               // echo "Email address '$email' is considered valid.\n";
            } else {
                $alljdata = json_decode($request['jdata'], true);
                //dd($alljdata['resource_id']);
                echo $email = $alljdata['resource_id'] . "@noreply.com";
                $filleddata = "<b style='color:red'>Invalid Email Address received from Callrail</b> " . $filleddata;
            }

            if (isset($formdata['Full Name'])){
                $fullname = explode(" ", $formdata['Full Name']);
                $formdata['First'] = $fullname[0];
                if(count($fullname) > 2){
                    $formdata['Last'] = $fullname[1]." ".$fullname[2];
                }else{
                    if(isset($fullname[1]))
                        $formdata['Last'] = $fullname[1];
                }

            }

            if(isset($formdata['Phone Number'])){
                $phonekey= 'Phone Number';

            }

            if (isset($formdata['First'])) {
                $request['first_name'] = $formdata['First'];
            } else {
                $request['first_name'] = "XXXXX";
            }

            if ($fnamekey != '') {
                $request['first_name'] = $formdata["$fnamekey"];
            }
            if ($lnamekey != '') {
                $request['last_name'] = $formdata["$lnamekey"];
            }

            if (isset($formdata['Last'])) {
                $request['last_name'] = $formdata['Last'];
            }

            if ($request['cr_company_id'] == '825898785') {
                if (isset($formdata['first_name'])) {
                    $request['first_name'] = $formdata["first_name"];
                }
                if (isset($formdata['last_name'])) {
                    $request['last_name'] = $formdata["last_name"];
                }
            }


            $request['email'] = $email;

            if ($phonekey == '') {
                $phone = "1111111111";
            } else {
                $phone = $formdata["$phonekey"];
            }

            $phone = str_replace('+1', '', $phone);
            $request['phone'] = "+1" . preg_replace("/[^a-zA-Z0-9]/", "", $phone);

            $sourcename = $request['source'];
            if ($sourcename == '') {
                $sourcename = $request['source_name'];
            }

            $source = '';
            $customsourcename = '';
            $form_id = '';

            if (stristr($sourcename, "Direct") !== false) {
                $source = '1';
            } elseif (stristr($sourcename, "Facebook") !== false) {
                $source = '3';
            } elseif (stristr($sourcename, "Google Ads") !== false) {
                $source = '2';
            } elseif (stristr($sourcename, "Google Organic") !== false) {
                $source = '4';
            } elseif (stristr($sourcename, "Yahoo") !== false) {
                $source = '8';
            } elseif (stristr($sourcename, "Msn") !== false) {
                $source = '9';
            } elseif (stristr($sourcename, "Bing Ads") !== false) {
                $source = '23';
            } elseif (stristr($sourcename, "Bing") !== false) {
                $source = '10';
            } elseif (stristr($sourcename, "Youtube") !== false) {
                $source = '6';
            } elseif (stristr($sourcename, "TV") !== false) {
                $source = '12';
            } elseif (stristr($sourcename, "Infomercial") !== false) {
                $source = '13';
            } elseif (stristr($sourcename, "Radio") !== false) {
                $source = '14';
            } elseif (stristr($sourcename, "Tiktok") !== false) {
                $source = '15';
            } elseif (stristr($sourcename, "Instagram") !== false) {
                $source = '24';
            } else {
                $source = '11';
                $customsourcename = $sourcename;
            }
            $form_id = $request['resource_id'];

            if ($request['cr_company_id'] == '348544341' && strpos($request['jdata'], "webinar") !== false) {
                $request['badge'] = "Webinar";
            }


            $request['source_id'] = $source;
            $request['status_id'] = 1;
            $request['source_other'] = $customsourcename;
            $request['form_data'] = " FormData:" . $filleddata;
            $request['form_id'] = $form_id;
            $request['callrail_details'] = $request['jdata'];

            //-----------Update Lead Score Logic Start---------------------
            $clinicwithdynamicls = ['35','3','256','155','253','6','13','15','80','202','284','287','298','296'];
            if(in_array($clinic->id, $clinicwithdynamicls)){
              // Because it is new lead add random no 280-299 to lead score
              $leadScore = random_int(280, 299);

              // Array of question with answer and their weightage
              $questions = [
                  'input_1' => [49 => "I’m ready to book an appointment for dental implants.", 34 => "I’m considering dental implants and want more information.", 17 => "I’m just beginning to explore my dental implant options."],
                  'input_46' => [46 => "I’d like to start within the month.", 30 => "I'd like to start in 2-3 months.", 16 => "I’d like to start in 4-6 months.", 8 => "I’m just researching implants with no fixed timeline."],
                  'input_47' => [75 => "Yes, I’ve already talked to a dental professional.", 25 => "No, I have NOT talked to a dental professional."],
                  'input_2' => [17 => "I have most of my teeth.", 34 => "I am missing one or more teeth.", 49 => "I am not missing any teeth."],
                  'input_48' => [49 => "I am fully aware and have allocated a budget.", 34 => "I have a rough idea but need more details on costs.", 17 => "I am currently seeking information about the costs and financing options."],
                  'input_44' => [34 => "Yes, I am interested in financing options.", 49 => "No, I plan to pay upfront.", 17 => "I’m unsure about financing and would like more information."],
                  'input_45' => [100 => "Yes, my credit score is above 650.", 0 => "No, my credit score is below 650.", 50 => "I’m not sure of my credit score."],
                  'input_49' => [75 => "Yes, I make my own dental care decisions.", 25 => "No, I plan to consult with someone."]
              ];

                foreach ($questions as $index => $question) {
                    if(isset($formdata[$index])){
                    $answer = $formdata[$index];
                        if (!empty($answer)) {
                            foreach ($question as $point => $option) {
                                if ($answer == $option) {
                                $leadScore += $point;
                                }
                            }
                        }
                    }
                }
            }else{
              $leadScore = random_int(450, 750);
            }
            $request['lead_score'] = $leadScore;

            //--------------- Update Lead Score Logic Ends -----------------------------
        }
//End : Form Data Request

//Start : Call Data Request
        if ($event_type == 'call') {

            $request['phone_form'] = 'Phone Call';

            $email = $request['customer_phone_number'] . "@noemail.com";
            $request['email'] = str_replace("+", "", $email);

            $name = trim($request['customer_name']);
            $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
            $first_name = trim(preg_replace('#' . preg_quote($last_name, '#') . '#', '', $name));

            $request['first_name'] = $first_name;
            $request['last_name'] = $last_name;
            $request['phone'] = $request['customer_phone_number'];

            $request['city'] = $request['customer_city'];
            $request['state'] = $request['customer_state'];

            $sourcename = $request['source'];

            if ($request['source_name'] == "Facebook Ads Extension") {
                $sourcename = "Facebook";
            }

            if ($sourcename == '') {
                $sourcename = $request['source_name'];
            }


            $source = '';
            $customsourcename = '';

            if ($request['source'] == '' && isset($request['milestones']['first_touch']['source'])) {
                $sourcename = $request['milestones']['first_touch']['source'];
                $request['source'] = $sourcename;
            }
            if ($request['source'] == '' && isset($request['milestones']['last_touch']['source'])) {
                $sourcename = $request['milestones']['last_touch']['source'];
                //$request['source'] = $sourcename;
            }

            if (stristr($sourcename, "Direct") !== false) {
                $source = '1';
            } elseif (stristr($sourcename, "Facebook") !== false) {
                $source = '3';
            } elseif (stristr($sourcename, "Google Ads") !== false) {
                $source = '2';
            } elseif (stristr($sourcename, "Google Organic") !== false) {
                $source = '4';
            } elseif (stristr($sourcename, "Yahoo") !== false) {
                $source = '8';
            } elseif (stristr($sourcename, "Msn") !== false) {
                $source = '9';
            } elseif (stristr($sourcename, "Bing Ads") !== false) {
                $source = '23';
            } elseif (stristr($sourcename, "Bing") !== false) {
                $source = '10';
            } elseif (stristr($sourcename, "Youtube") !== false) {
                $source = '6';
            } elseif (stristr($sourcename, "TV") !== false) {
                $source = '12';
            } elseif (stristr($sourcename, "Infomercial") !== false) {
                $source = '13';
            } elseif (stristr($sourcename, "Radio") !== false) {
                $source = '14';
            } elseif (stristr($sourcename, "Tiktok") !== false) {
                $source = '15';
            } elseif (stristr($sourcename, "Instagram") !== false) {
                $source = '24';
            } else {
                $source = '11';
                $customsourcename = $sourcename;
            }

            $lead_score = random_int(480, 500);
            $request['lead_score'] = $lead_score;

            $request['source_id'] = $source;
            $request['status_id'] = 1;
            $request['source_other'] = $customsourcename;
        }
//End : Call Data Request



        $crmcustomer = CrmCustomer::where('email', $request['email'])->where('clinic_id', $clinic->id)->first();

        if (isset($crmcustomer['id']) && $crmcustomer['id'] > 0) {

            //LAM Clinic check if the specific string exists in the data
            if($request['cr_company_id'] == '801450008')
            {

                if(strpos($request['jdata'], "root-cause-virtual-membership") !== false || strpos($request['jdata'], "Root Cause Virtual Membership") !== false)
                {
                    $crm_customer = CrmCustomer::find($crmcustomer['id']); // Replace with the actual way you retrieve the customer


                    $vtag = Tag::where('tagName', 'Virtual')->where('clinic_id', $crm_customer->clinic_id)->first();

                    // If the tag does not exist, create it
                    if (!$vtag) {
                        $vtag = new Tag();
                        $vtag->tagName = 'Virtual';
                        $vtag->clinic_id = $crm_customer->clinic_id;
                        $vtag->save();
                    }

                    // Create a new TagLeadMapping entry
                    $vtagLeadMapping = new TagLeadMapping();
                    $vtagLeadMapping->tag_id = $vtag->id;
                    $vtagLeadMapping->lead_id = $crm_customer->id;
                    $vtagLeadMapping->save();
                }

            }

            $calldata = array();
            $request['subject'] = 'Inbound call from same email address';
            $request['type'] = 'inbound';
            $request['customer_id'] = $crmcustomer['id'];
            $crmcall = CrmCall::create($request->all());

            // send lead reconnecting notification via browser, email and text.
            $this->sendLeadReconnectingNotification($clinic->id, $crmcustomer->id);

            // Check if the 'Reconnecting' tag exists
            $tag = Tag::where('tagName', 'Reconnecting')->where('clinic_id', $crmcustomer->clinic_id)->first();

            // If the tag does not exist, create it
            if (!$tag) {
                $tag = new Tag();
                $tag->tagName = 'Reconnecting';
                $tag->clinic_id = $crmcustomer->clinic_id;
                $tag->save();
            }

            // Create a new TagLeadMapping entry
            $tagLeadMapping = new TagLeadMapping();
            $tagLeadMapping->tag_id = $tag->id;
            $tagLeadMapping->lead_id = $crmcustomer->id;
            $tagLeadMapping->save();

            if (!empty($clinic)) {
                // Retrieve users with role id 5
                $users = $clinic->managers()->whereHas('roles', function ($q) {
                    $q->where('id', 5);
                })->get();

                if (!empty($crmcustomer)) {
                    foreach ($users as $user) {

                        $setting = Setting::where('user_id', $user->id)->first();
                        if ($setting) {
                            if ($setting->do_not_disturb == 0 && $setting->lead_reconnecting_text_notification == 1) {
                                // Send notification to each user
                                $notification = new LeadReconnecting($crmcustomer);
                                $notification->toTwilio($user, $crmcustomer);
                            }
                        } else {
                            // User does not have settings saved, send notifications by default
                            $notification = new LeadReconnecting($crmcustomer);
                            $notification->toTwilio($user, $crmcustomer);
                        }
                    }
                }
            }

            $todaytime = Carbon::now();
            $todaysdate = $todaytime->toDateTimeString();

            $date1 = date_create($crmcustomer['created_at']);
            $date2 = date_create($todaysdate);
            $diff = date_diff($date1, $date2);
            $diffdays = $diff->format("%a");

            if ($diffdays > 90) {
                $crmcustomer->created_at = $todaysdate;
            }

            if ($diffdays >= 5 && in_array($crmcustomer->status_id, [13, 17, 9, 14])) {
                // Consider as a new lead and update the status to 1
                $crmcustomer->status_id = 1;
                $crmcustomer->consultation_booked_date = null;
                $crmcustomer->convert_to_deal = null;
                $crmcustomer->convert_deal_date = null;

            }


            $crmcall = CrmCall::where('customer_id', $crmcustomer['id'])->count();

            if ($crmcall == 1) {
                $lead_score = $crmcustomer->lead_score;
                if (empty($lead_score)) {
                    $lead_score = 0;
                    if ($crmcustomer->phone_form === "Phone Call") {
                        $lead_score = random_int(480, 500);
                    } elseif ($crmcustomer->phone_form === "Web Form") {
                        $lead_score = random_int(280, 300);
                    } else {
                        $lead_score = 0;
                    }
                }

                $lead_score += random_int(91, 99);

                $crmcustomer->lead_score = $lead_score;
            }

            if($crmcustomer->lead_score >= '1000'){
              $crmcustomer->lead_score = '990';
            }

            if($crmcustomer->source_id == '11'){
                $crmcustomer->source_id = $source;
            }

            $crmcustomer->save();

            if($event_type == 'form'){
                $clinicDefaultTimezone = 'America/New_York';
                $customers = CrmCustomer::with('clinic')
                    //->where('phone_form', $phoneFormValue)
                    ->where('id', '=', $crmcustomer->id)
                    ->get(['id','first_name', 'last_name', 'email', 'phone', 'created_at', 'clinic_id']);

                $preparedData = $customers->map(function ($customer) use ($clinicDefaultTimezone) {
                    $clinic = $customer->clinic;
                    $clinicTimezone = $clinic->timezone ?? $clinicDefaultTimezone;
                    $assistantId = $clinic->vapi_assistant_id ?? null;
                    $phoneNumberId = $clinic->vapi_phone_number_id ?? null;
                    $officeStartHour = 9; // 9:00 AM
                    $officeEndHour = 21; // 9:00 PM
                    $leadCreationTime = Carbon::parse($customer->created_at, 'UTC')
                        ->setTimezone($clinicTimezone);
                    $isWithinOfficeHours = $leadCreationTime->hour >= $officeStartHour && $leadCreationTime->hour < $officeEndHour;
                    $postdata = [
                        'id' => $customer->id,
                        'first_name' => $customer->first_name,
                        'last_name' => $customer->last_name,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                        'timezone' => $isWithinOfficeHours ? 'Yes' : 'No',
                        'vapi_assistant_id' => $assistantId,
                        'vapi_phone_number_id' => $phoneNumberId,
                    ];
                    if($assistantId != '' && $customer->phone != '+11111111111')
                    {
                        $responsedata = Http::post('https://hook.eu2.make.com/5du4nhae7cqkviv44gnulxa2i7nhj51p', [
                            'data' => $postdata ?? null,
                            'message' => 'Outbound Calling Webhook processed successfully'
                        ]);
                    }
                });
            }


            $CrmNote = new CrmNote;
            $CrmNote->note = "Previous lead reconnecting";
            $CrmNote->user_id = 1;
            $CrmNote->customer_id = $crmcustomer['id'];
            $CrmNote->save();

            return $crmCustomer = CrmCustomer::find($crmcustomer['id']);

        }
        else {

            $request['value'] = NUll;

                $crmCustomer = CrmCustomer::create($request->all());

            if($event_type == 'form'){
                $clinicDefaultTimezone = 'America/New_York';
                $customers = CrmCustomer::with('clinic')
                    //->where('phone_form', $phoneFormValue)
                    ->where('id', '=', $crmCustomer->id)
                    ->get(['id','first_name', 'last_name', 'email', 'phone', 'created_at', 'clinic_id']);

                $preparedData = $customers->map(function ($customer) use ($clinicDefaultTimezone) {
                    $clinic = $customer->clinic;
                    $clinicTimezone = $clinic->timezone ?? $clinicDefaultTimezone;
                    $assistantId = $clinic->vapi_assistant_id ?? null;
                    $phoneNumberId = $clinic->vapi_phone_number_id ?? null;
                    $officeStartHour = 9; // 9:00 AM
                    $officeEndHour = 21; // 9:00 PM
                    $leadCreationTime = Carbon::parse($customer->created_at, 'UTC')
                        ->setTimezone($clinicTimezone);
                    $isWithinOfficeHours = $leadCreationTime->hour >= $officeStartHour && $leadCreationTime->hour < $officeEndHour;
                    $postdata = [
                        'id' => $customer->id,
                        'first_name' => $customer->first_name,
                        'last_name' => $customer->last_name,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                        'timezone' => $isWithinOfficeHours ? 'Yes' : 'No',
                        'vapi_assistant_id' => $assistantId,
                        'vapi_phone_number_id' => $phoneNumberId,
                    ];
                    if($assistantId != '' && $customer->phone != '+11111111111')
                    {
                        $responsedata = Http::post('https://hook.eu2.make.com/5du4nhae7cqkviv44gnulxa2i7nhj51p', [
                            'data' => $postdata ?? null,
                            'message' => 'Outbound Calling Webhook processed successfully'
                        ]);
                    }
                });
            }
                if(isset($request['form_data'])){
                    // Decode the form data
                    $formData = json_decode($request->getContent(), true);

                 // Check if the specific string exists in the form data
                    if (isset($formData) && is_array($formData)) {
                     $appointmentString = "Book Appointment: I'm ready for an in-office visit";
                     $stringFound = false;

                        if(strpos($request['jdata'], $appointmentString) !== false){
                               $stringFound = true;
                        }

                        if ($stringFound) {
                             // Assuming you have the CRM customer object (e.g., from session or passed in the request)
                             $crm_customer = CrmCustomer::find($crmCustomer->id); // Replace with the actual way you retrieve the customer

                             // Check if the 'No Schedule' tag exists
                             $tag = Tag::where('tagName', 'No Schedule')->where('clinic_id', $crm_customer->clinic_id)->first();

                             // If the tag does not exist, create it
                             if (!$tag) {
                                 $tag = new Tag();
                                 $tag->tagName = 'No Schedule';
                                 $tag->clinic_id = $crm_customer->clinic_id;
                                 $tag->save();
                             }

                             // Create a new TagLeadMapping entry
                             $tagLeadMapping = new TagLeadMapping();
                             $tagLeadMapping->tag_id = $tag->id;
                             $tagLeadMapping->lead_id = $crm_customer->id;
                             $tagLeadMapping->save();
                        }
                    }
                }

            //LAM Clinic check if the specific string exists in the data
            if($request['cr_company_id'] == '801450008')
            {

                if(strpos($request['jdata'], "root-cause-virtual-membership") !== false || strpos($request['jdata'], "Root Cause Virtual Membership") !== false)
                {
                    $crm_customer = CrmCustomer::find($crmCustomer->id); // Replace with the actual way you retrieve the customer


                    $vtag = Tag::where('tagName', 'Virtual')->where('clinic_id', $crm_customer->clinic_id)->first();

                    // If the tag does not exist, create it
                    if (!$vtag) {
                        $vtag = new Tag();
                        $vtag->tagName = 'Virtual';
                        $vtag->clinic_id = $crm_customer->clinic_id;
                        $vtag->save();
                    }

                    // Create a new TagLeadMapping entry
                    $vtagLeadMapping = new TagLeadMapping();
                    $vtagLeadMapping->tag_id = $vtag->id;
                    $vtagLeadMapping->lead_id = $crm_customer->id;
                    $vtagLeadMapping->save();
                }

            }

                // Check if the specific string exists in the form data
                if ($request['cr_company_id'] == '656972327') {

                    if(strpos($request['jdata'], "Website Brooklyn") !== false || strpos($request['jdata'], "Brooklyn (415 Knickerbocker Avenue)") !== false)
                    {
                        $crm_customer = CrmCustomer::find($crmCustomer->id); // Replace with the actual way you retrieve the customer


                        $tag = Tag::where('tagName', 'Brooklyn Office')->where('clinic_id', $crm_customer->clinic_id)->first();

                        // If the tag does not exist, create it
                        if (!$tag) {
                             $tag = new Tag();
                             $tag->tagName = 'Brooklyn Office';
                             $tag->clinic_id = $crm_customer->clinic_id;
                             $tag->save();
                        }

                        // Create a new TagLeadMapping entry
                        $tagLeadMapping = new TagLeadMapping();
                        $tagLeadMapping->tag_id = $tag->id;
                        $tagLeadMapping->lead_id = $crm_customer->id;
                        $tagLeadMapping->save();
                    }
                    if(strpos($request['jdata'], "Website Harlem") !== false || strpos($request['jdata'], "Harlem (32 West 125th Street)") !== false)
                    {
                        $crm_customer = CrmCustomer::find($crmCustomer->id); // Replace with the actual way you retrieve the customer


                        $tag = Tag::where('tagName', 'Harlem Office')->where('clinic_id', $crm_customer->clinic_id)->first();

                        // If the tag does not exist, create it
                        if (!$tag) {
                             $tag = new Tag();
                             $tag->tagName = 'Harlem Office';
                             $tag->clinic_id = $crm_customer->clinic_id;
                             $tag->save();
                        }

                        // Create a new TagLeadMapping entry
                        $tagLeadMapping = new TagLeadMapping();
                        $tagLeadMapping->tag_id = $tag->id;
                        $tagLeadMapping->lead_id = $crm_customer->id;
                        $tagLeadMapping->save();
                    }

                }

                /*// Send notification if the lead is green lead.
                if($crmCustomer->lead_score >= 700){
                   $this->sendLeadCreatedNotification($clinic->id, $crmCustomer->id);

                    if (!empty($clinic)) {
                        // Retrieve users with role id 5
                        $users = $clinic->managers()->whereHas('roles', function ($q) {
                            $q->where('id', 5);
                        })->get();

                        // Find the lead (CrmCustomer)
                        $lead = CrmCustomer::find($crmCustomer->id);

                        if (!empty($lead)) {
                            foreach ($users as $user) {

                                $setting = Setting::where('user_id', $user->id)->first();
                                if ($setting) {
                                    if ($setting->do_not_disturb == 0 && $setting->lead_text_notification == 1) {
                                        // Send notification to each user
                                        $notification = new GreenLeadCreated($lead);
                                        $notification->toTwilio($user,$lead);
                                    }
                                } else {
                                     // User does not have settings saved, send notifications by default
                                    $notification = new GreenLeadCreated($lead);
                                    $notification->toTwilio($user,$lead);
                                }
                            }
                        }
                    }
                }*/

                if (strpos($request['jdata'], "Phon3V3r1fyY35") !== false) {
                    $request['lead_id'] = $crmCustomer['id'];
                    $verifysms = $this->sendverfiysms($request);
                }
                if ($event_type == 'form' && $clinic->lead_center == 'Yes' && $clinic->autosms == 1 && $request['phone'] != "+11111111111" && strpos($request['jdata'], "AS3Msu7unSysH8SAVJ") !== false) {
                    $request['lead_id'] = $crmCustomer['id'];
                    //$smsid = $this->sendsms($request);
                }
                if ($event_type == 'form' && $clinic->lead_center != 'Yes' && $clinic->autosms == 1 && $request['phone'] != "+11111111111" && strpos($request['jdata'], "AS3Msu7unSysH8SAVJ") !== false && $clinic->autosmstext != '') {
                    $request['lead_id'] = $crmCustomer['id'];
                    $request['autosmstext'] = $clinic->autosmstext;
                    //$smsid = $this->sendsms($request);
                }

                if($clinic->version == '2.0' && $clinic->immediate_sms == 1 && $request['phone'] != "+11111111111"){
                    $request['lead_id'] = $crmCustomer['id'];
                    $immediatetext = $this->immediatesms($request);
                }

                if ($clinic->version == '2.0' && $clinic->immediate_mail == 1) {
                    // Check if the email contains "@noemail.com" or "@noreply.com"
                    if (strpos($request['email'], "@noemail.com") === false && strpos($request['email'], "@noreply.com") === false) {
                        $request['lead_id'] = $crmCustomer['id'];
                        $immediatemail = $this->immediateemail($request);
                    }
                }

                return $crmCustomer;
            }
    }

    public function sendverfiysms(Request $request)
    {
        $clinic = Clinic::select('clinic_name', 'twilio_number', 'twilio_subid', 'twilio_token', 'leadcenteraccountmanager', 'dr_name', 'hotline_phone_number')->where('id', '=', $request->input('clinic_id'))->first();
        $receiverNumber = $request->input('phone');

        $lead_id = $request['lead_id'];

        $account_sid = $clinic->twilio_subid;
        $auth_token = $clinic->twilio_token;
        $twilio_number = $clinic->twilio_number;

        $randomNum = rand(pow(10, 4-1), pow(10, 4)-1);

        $crm_customer = CrmCustomer::find($lead_id);
        $crm_customer->phone_code = $randomNum;
        $crm_customer->save();

        $message = "Dental Implant Hotline verification code: " . $randomNum;

        $request['from'] = $twilio_number;
        $request['to'] = $receiverNumber;
        $request['chat'] = $message;
        $request['user_id'] = 1;
        $request['is_sms'] = 1;
        $crmChat = CrmChat::create($request->all());

        try {

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'statusCallback' => route('sms.inbound', env('SMS_WEBHOOK')),
                'body' => $message]);

            //dd('SMS Sent Successfully.');

        } catch (Exception $e) {
            //dd("Error: ". $e->getMessage());
        }


        //Send Verification Email
        $receiveremail = $request->input('email');

        $randomNum = rand(pow(10, 4-1), pow(10, 4)-1);

        $emailvcode = $randomNum;

        $crm_customer = CrmCustomer::find($lead_id);
        $crm_customer->email_code = $emailvcode;
        $crm_customer->save();


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
        $CrmNote->note = "Verification Email Sent";
        $CrmNote->user_id = 1;
        $CrmNote->customer_id = $lead_id;
        $CrmNote->save();

        //
        return $crmChat;
    }

    public function sendsms(Request $request)
    {


        //
        $clinic = Clinic::select('clinic_name', 'twilio_number', 'twilio_subid', 'twilio_token', 'leadcenteraccountmanager', 'dr_name', 'hotline_phone_number', 'workingdaysmstext', 'nonworkingdaysmstext')->where('id', '=', $request->input('clinic_id'))->first();

        $receiverNumber = $request->input('phone');

        $estdayTime = (new DateTime('America/New_York'))->format('D:H:i');
        $daytime = explode(":", $estdayTime);
        $day = $daytime[0];
        $hour = $daytime[1];
        $minute = $daytime[1];
        $currentday = (new DateTime('America/New_York'))->format('Y-m-d');
        $holidayDayList = config('holidaydate.holidayDate');

        //$holidays = array("2021-11-25","2021-11-26","2021-12-24","2021-12-31","2022-01-17","2022-05-30","2022-06-20","2022-07-04","2022-09-05","2022-11-24","2022-11-25","2022-12-26");
        $holidays = $holidayDayList;

        if (($day == 'Mon' || $day == 'Tue' || $day == 'Wed' || $day == 'Thu' || $day == 'Fri') && $hour >= '09' && $hour < '21' && !in_array($currentday, $holidays)) {
            if (isset($clinic->workingdaysmstext) && $clinic->workingdaysmstext != "") {
                $message = $clinic->workingdaysmstext;
            } else {
                $message = "Hi, this is " . $clinic->leadcenteraccountmanager . ", a Dental Implant Concierge from " . $clinic->dr_name . "’s office. Based on the questionnaire you just completed, you are a likely candidate for Dental Implants! If you’d like to talk with me to ask questions, discuss possible solutions or schedule an in-office consultation, please call me at " . $clinic->hotline_phone_number . ". If I don’t pick up right away, please leave a message, and I’ll get right back to you.
    \nReply STOP to cancel further texts.";
            }
        } else {
            if (isset($clinic->nonworkingdaysmstext) && $clinic->nonworkingdaysmstext != "") {
                $message = $clinic->nonworkingdaysmstext;
            } else {
                $message = "Hi, this is " . $clinic->leadcenteraccountmanager . ", a Dental Implant Concierge from " . $clinic->dr_name . "’s office. Based on the questionnaire you just completed, you are a likely candidate for Dental Implants! If you’d like to talk with me to ask questions, discuss possible solutions or schedule an in-office consultation, please call me during regular business hours at " . $clinic->hotline_phone_number . ". If I don’t pick up right away, please leave a message, and I’ll get right back to you. I look forward to speaking with you.
    \nReply STOP to cancel further texts.";
            }
        }

        if (isset($request['autosmstext'])) {
            $message = $request['autosmstext'];
        }

        $account_sid = $clinic->twilio_subid;
        $auth_token = $clinic->twilio_token;
        $twilio_number = $clinic->twilio_number;

        $request['from'] = $twilio_number;
        $request['to'] = $receiverNumber;
        $request['chat'] = $message;
        $request['user_id'] = 1;
        $request['is_sms'] = 1;
        $crmChat = CrmChat::create($request->all());

        try {

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'statusCallback' => route('sms.inbound', env('SMS_WEBHOOK')),
                'body' => $message]);

            //dd('SMS Sent Successfully.');

        } catch (Exception $e) {
            //dd("Error: ". $e->getMessage());
        }
        //
        return $crmChat;
        //return redirect()->route('admin.crm-chats.index');
    }

    public function immediatesms(Request $request)
    {
        // Retrieve clinic information from the database
        $lead      = CrmCustomer::select('first_name','last_name','phone','clinic_id')->where('id','=',$request->input('lead_id'))->first();
        $clinic = Clinic::select('clinic_name', 'twilio_number', 'twilio_subid', 'twilio_token', 'leadcenteraccountmanager', 'dr_name', 'hotline_phone_number')
                        ->where('id', '=', $request->input('clinic_id'))
                        ->first();
        $receiverNumber = $request->input('phone');
        $clinic_id = $request->input('clinic_id');
        $immediatetemplateService = new ImmediateTemplateService();
        $template = $immediatetemplateService->getImmediateTemplate($clinic_id);

        // Twilio credentials and phone number
        $account_sid = $clinic->twilio_subid;
        $auth_token = $clinic->twilio_token;
        $twilio_number = $clinic->twilio_number;

        $message = strip_tags($template[0]->text_template,"<br>");

        $message = str_replace("{{REPLACE_FIRST_NAME}}", $lead->first_name, $message);

        $message = str_replace('<br>', "\r\n", $message);

        $message = str_replace('&nbsp;', ' ', $message);

        $request['from'] = $twilio_number;
        $request['to'] = $receiverNumber;
        $request['chat'] = $message;
        $request['user_id'] = 1;
        $request['is_sms'] = 1;
        $crmChat = CrmChat::create($request->all());

        // Customize your message
        try {
            // Create a new Twilio client and send the message
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'statusCallback' => route('sms.inbound', env('SMS_WEBHOOK')),
                'body' => $message
            ]);
        } catch (Exception $e) {
            // Handle errors
             echo("Error: ". $e->getMessage());
        }
    }

    public function immediateemail(Request $request)
    {

        // Retrieve clinic information from the database
        $lead  = CrmCustomer::select('first_name','last_name','phone','clinic_id')->where('id','=',$request->input('lead_id'))->first();
        $receiveremail = $request->input('email');
        $clinic_id = $request->input('clinic_id');
        $lead_id = $request['lead_id'];
        $immediatetemplateService = new ImmediateTemplateService();
        $template = $immediatetemplateService->getImmediateTemplate($clinic_id);

        // Customize your message
        $email_subject = strip_tags($template[0]->email_subject);
        $email_template = $template[0]->email_template;

        $email_template = str_replace("{{REPLACE_FIRST_NAME}}", $lead->first_name, $email_template);

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
        $CrmNote->note = "Immediate Reply Email Sent <br/> Subject: ".$email_subject."<br>".$email_template;
        $CrmNote->user_id = 1;
        $CrmNote->customer_id = $lead_id;
        $CrmNote->save();
    }

    public function pending(Request $request)
    {
        $pendingdata = Callrail::where('lead_convert', 0)->where('event_type','!=' ,'postcall')->get();

        if (count($pendingdata) > 0) {
            $lead = new CrmCustomer;
            $lead->clinic_name = "Alert:";
            $lead->slackmsg = "Error ! While creating a new lead from callrail data.";
            $lead->slackchannel = "#lmsalerts";
            $lead->notify(new \App\Notifications\SendNotification($lead));
        }

        foreach ($pendingdata as $data) {
            $orgdata = json_decode($data['jdata'], true);
            $formdata = json_decode($data->jdata);
            //echo "A";dd($formdata->form_data);exit;

            $plead = new CrmCustomer;
            $plead->clinic_name = "Alert:";
            $plead->slackmsg = "Error ! While creating a new lead from callrail data.-".$data['id'];
            $plead->slackchannel = "#lmsalerts";
            $plead->notify(new \App\Notifications\SendNotification($plead));


            $data = json_decode(json_encode($data), true);

            $request->merge($data);

            if ($data['event_type'] == 'form') {

                if(isset($formdata->form_data))
                {
                    $request['form_data'] = json_decode(json_encode($formdata->form_data), true);
                }
                else
                {
                    $request['form_data'] = $orgdata;
                }

            } else {
                $request->merge($orgdata);
            }


            $leadid = $this->createlead($request);
            echo "LeadiD:" . $leadid['id'];

            if ($leadid['id'] > 0) {
                Callrail::where('id', $data['id'])->update(['lead_id' => $leadid['id'], 'lead_convert' => 1, 'lms_clinic_id' => $leadid['clinic_id']]);
            }
            if ($leadid['id'] == -2) {
                Callrail::where('id', $data['id'])->update(['lead_id' => -2, 'lead_convert' => -2, 'lms_clinic_id' => -2]);
            }
        }


    }


    //check and update source
    public function checksource(Request $request)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.callrail.com/v3/a/564577310/form_submissions.json?date_range=yesterday&per_page=250",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Token token=\"4a42567f2622d34a69bf2bad734eb48a\"",
                "Cookie: remember_device_token=5ff11cb8-7ab8-4581-a588-4e7a2958e87b"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $allformdata = json_decode($response, true);
        $forms = $allformdata['form_submissions'];

        foreach (CrmCustomer::where('form_id', 'LIKE', "FRM%")->get() as $lead) {
            $formid = $lead->form_id;
            $leadid = $lead->id;
            $key = array_search($formid, array_column($forms, 'id'));
            $sourcename = $forms[$key]['source'];
            $source = '';
            $customsourcename = '';

            if (stristr($sourcename, "Direct") !== false) {
                $source = '1';
            } elseif (stristr($sourcename, "Facebook") !== false) {
                $source = '3';
            } elseif (stristr($sourcename, "Google Ads") !== false) {
                $source = '2';
            } elseif (stristr($sourcename, "Google Organic") !== false) {
                $source = '4';
            } elseif (stristr($sourcename, "Yahoo") !== false) {
                $source = '8';
            } elseif (stristr($sourcename, "Msn") !== false) {
                $source = '9';
            } elseif (stristr($sourcename, "Bing Ads") !== false) {
                $source = '23';
            } elseif (stristr($sourcename, "Bing") !== false) {
                $source = '10';
            } elseif (stristr($sourcename, "Youtube") !== false) {
                $source = '6';
            } elseif (stristr($sourcename, "TV") !== false) {
                $source = '12';
            } elseif (stristr($sourcename, "Infomercial") !== false) {
                $source = '13';
            } elseif (stristr($sourcename, "Radio") !== false) {
                $source = '14';
            } elseif (stristr($sourcename, "Tiktok") !== false) {
                $source = '15';
            } elseif (stristr($sourcename, "Instagram") !== false) {
                $source = '24';
            } else {
                $source = '11';
                $customsourcename = $sourcename;
            }

            CrmCustomer::where('id', $leadid)->update(['source_id' => $source, 'form_id' => "Updated:" . $formid, 'source_other' => $customsourcename]);
        }
    }

    //end of check

    public function getSingleCall(Request $request)
    {

        $crmCustomers = CrmCustomer::whereNotNull('callrail_details')
        ->where('phone_form', 'Phone Call')
        ->where('clinic_id', '!=','244')
        ->where('needs_callrail_update', true) // Only process records that need an update
        ->whereDate('created_at', '>', Carbon::parse('2023-12-31'))
        ->get();



    $apiKey = env('CALLRAIL_API');
    $accountId = env('CALLRAIL_ACCOUNTID');

    foreach ($crmCustomers->chunk(100) as $chunk) { // Process records in batches of 100
        foreach ($chunk as $customerData) {
            $callrailDetails = json_decode($customerData->callrail_details, true);

             if (isset($callrailDetails['resource_id'])) {
                $callId =  $callrailDetails['resource_id'];
                $updatedDate = $customerData->updated_at;



                $response = Http::withoutVerifying()->withHeaders([
                    'Authorization' => 'Token token=' . $apiKey
                ])->get("https://api.callrail.com/v3/a/$accountId/calls/$callId.json?fields=call_type,company_id,company_name,company_time_zone,created_at,device_type,first_call,formatted_call_type,formatted_customer_location,formatted_business_phone_number,formatted_customer_name,prior_calls,formatted_customer_name_or_phone_number,formatted_customer_phone_number,formatted_duration,formatted_tracking_phone_number,formatted_tracking_source,formatted_value,good_lead_call_id,good_lead_call_time,lead_status,note,source,source_name,tags,total_calls,value,waveforms,tracker_id,speaker_percent,keywords,medium,campaign,referring_url,landing_page_url,last_requested_url,referrer_domain,utm_source,utm_medium,utm_term,utm_content,utm_campaign,utma,utmb,utmc,utmv,utmz,ga,gclid,fbclid,msclkid,milestones,timeline_url,integration_data,keywords_spotted,call_highlights,call_summary,transcription,conversational_transcript,agent_email,keypad_entries");

                if ($response->successful()) {
                    $callData = $response->json();

                    $callData['recording_redirect'] = $callrailDetails['recording_redirect'];
                    $callData['resource_id'] = $callrailDetails['resource_id'];
                    $customerData->timestamps = false; // Disable automatic timestamps
                    $customerData->callrail_details = json_encode($callData);
                    $customerData->call_summary = $callData['call_summary'];
                    $customerData->full_transcript = $callData['transcription'];
                    $customerData->needs_callrail_update = false; // Mark record as updated
                    $customerData->updated_at = $updatedDate;

                    $sourcename = '';
                    $sourceValue = '';
                    if (isset($callData['milestones']['first_touch']['source'])) {
                        $sourceValue = $callData['milestones']['first_touch']['source'];
                    }
                    $sourcename = $sourceValue;

                    $source = '';
                    $customsourcename = '';
                    if (stristr($sourcename, "Direct") !== false) {
                        $source = '1';
                    } elseif (stristr($sourcename, "Facebook") !== false) {
                        $source = '3';
                    } elseif (stristr($sourcename, "Google Ads") !== false) {
                        $source = '2';
                    } elseif (stristr($sourcename, "Google Organic") !== false) {
                        $source = '4';
                    } elseif (stristr($sourcename, "Yahoo") !== false) {
                        $source = '8';
                    } elseif (stristr($sourcename, "Msn") !== false) {
                        $source = '9';
                    } elseif (stristr($sourcename, "Bing Ads") !== false) {
                        $source = '23';
                    } elseif (stristr($sourcename, "Bing") !== false) {
                        $source = '10';
                    } elseif (stristr($sourcename, "Youtube") !== false) {
                        $source = '6';
                    } elseif (stristr($sourcename, "TV") !== false) {
                        $source = '12';
                    } elseif (stristr($sourcename, "Infomercial") !== false) {
                        $source = '13';
                    } elseif (stristr($sourcename, "Radio") !== false) {
                        $source = '14';
                    } elseif (stristr($sourcename, "Tiktok") !== false) {
                        $source = '15';
                    } elseif (stristr($sourcename, "Instagram") !== false) {
                        $source = '24';
                    } else {
                        $source = '11';
                        $customsourcename = $sourcename;
                    }

                    $customerData->source_id = $source;
                    $customerData->source_other = $customsourcename;

                    $customerData->save();
                    $customerData->timestamps = true; // Re-enable automatic timestamps

                    echo "Record Updated";
                } else {
                    // Handle the error
                    return "Error: " . $response->status();
                }
            } else {
                echo "Error: resource_id is missing";
            }
        }
    }
    }


    public function calendly(Request $request)
    {
        $data = file_get_contents('php://input');
        $request['cdata'] = $data;
        $calendly = Calendly::create($request->all());
        $eventurl = $request['payload']['event'];
        $first_name = $request['payload']['first_name'];
        $last_name = $request['payload']['last_name'];
        $email = $request['payload']['email'];

        $crm_id = $request['crm_id'];

        if ($crm_id == 98) {
            $calendlytoken = "eyJraWQiOiIxY2UxZTEzNjE3ZGNmNzY2YjNjZWJjY2Y4ZGM1YmFmYThhNjVlNjg0MDIzZjdjMzJiZTgzNDliMjM4MDEzNWI0IiwidHlwIjoiUEFUIiwiYWxnIjoiRVMyNTYifQ.eyJpc3MiOiJodHRwczovL2F1dGguY2FsZW5kbHkuY29tIiwiaWF0IjoxNjU4MTUxMjc1LCJqdGkiOiJkMWU5NDI2Ni0zMGJkLTRlOGMtOGViZi0yZmVhNzUzZDA4ZWUiLCJ1c2VyX3V1aWQiOiI0NDU4ODNjNS02ZWJiLTQ1NDEtYjI0OS1hOGVlN2VjOGUxMDUifQ.wjBpv1ek5a52pJaPVCxURGTAOQYZF6hwciezUt3tBgnTzL-ss7quBLoJu_wRMO04W6BW_Ef_kPXYIGW3KvvHlw";
        } elseif ($crm_id == 97) {
            $calendlytoken = "eyJraWQiOiIxY2UxZTEzNjE3ZGNmNzY2YjNjZWJjY2Y4ZGM1YmFmYThhNjVlNjg0MDIzZjdjMzJiZTgzNDliMjM4MDEzNWI0IiwidHlwIjoiUEFUIiwiYWxnIjoiRVMyNTYifQ.eyJpc3MiOiJodHRwczovL2F1dGguY2FsZW5kbHkuY29tIiwiaWF0IjoxNjU4MTQzNDIyLCJqdGkiOiIwYjk0MzkwOC0yN2I4LTQyMzUtOWM5My1iMmNmNzA4MDc1MWMiLCJ1c2VyX3V1aWQiOiJiZWVjOGU3Yy04MzliLTQxMmUtOTEyZC0xNDQ2MDcyYjE3MzgifQ.KzNoeAGvr8C1ay8HOZVLvL9HnUudrkxUSXD949eaB5PlHZXJGnA0GPeyU35WEOzg3sZ_d84yhVOZRBgVqlzVyA";
            $clinic_id = '97';
        } elseif ($crm_id == 80) {
            $calendlytoken = "eyJraWQiOiIxY2UxZTEzNjE3ZGNmNzY2YjNjZWJjY2Y4ZGM1YmFmYThhNjVlNjg0MDIzZjdjMzJiZTgzNDliMjM4MDEzNWI0IiwidHlwIjoiUEFUIiwiYWxnIjoiRVMyNTYifQ.eyJpc3MiOiJodHRwczovL2F1dGguY2FsZW5kbHkuY29tIiwiaWF0IjoxNjU4NzQ5NjQyLCJqdGkiOiIwNGNjNGRkNS00Y2RlLTRjMmQtOGFlNy02YmE0MzMzMWIyZGIiLCJ1c2VyX3V1aWQiOiIyNWZjNzU3Zi0zNDk0LTRiNjEtOGJlNC1hMTQ0MDc4MWNkMWQifQ.xkuaryL-kdh-Z2NKDr5xJ6sQVQfvZd3k79qX4r5pU-vmmXQN-IbLPLDZvUauB6qMCCR_vIeSjOEwbgMdubw_fA";
            $clinic_id = '80';
        } elseif ($crm_id == 87) {
            $calendlytoken = "eyJraWQiOiIxY2UxZTEzNjE3ZGNmNzY2YjNjZWJjY2Y4ZGM1YmFmYThhNjVlNjg0MDIzZjdjMzJiZTgzNDliMjM4MDEzNWI0IiwidHlwIjoiUEFUIiwiYWxnIjoiRVMyNTYifQ.eyJpc3MiOiJodHRwczovL2F1dGguY2FsZW5kbHkuY29tIiwiaWF0IjoxNjU4ODYyMDg3LCJqdGkiOiIwZDQ2ZjM5MC0zOGU4LTQwYjctYjhjOS1mNmQ4M2Y3MjlhYWMiLCJ1c2VyX3V1aWQiOiIwNWQ1ZGY3OC04ZDk5LTQ0MTAtYTNkMy0xOGYyNzgxMGI0NzYifQ.xJWh0u2GpWjH6c9CQKWzMDFYsTqxsMBd1WP88oLaxM2MgDIZKkEV7TV0TxZNOYRYuXbI_75VTNLcliIzu1QYpg";
            $clinic_id = '87';
        } elseif ($crm_id == 30) {
            $calendlytoken = "eyJraWQiOiIxY2UxZTEzNjE3ZGNmNzY2YjNjZWJjY2Y4ZGM1YmFmYThhNjVlNjg0MDIzZjdjMzJiZTgzNDliMjM4MDEzNWI0IiwidHlwIjoiUEFUIiwiYWxnIjoiRVMyNTYifQ.eyJpc3MiOiJodHRwczovL2F1dGguY2FsZW5kbHkuY29tIiwiaWF0IjoxNjU4OTU5MjcwLCJqdGkiOiIyMjdkNzNhNy1lZWZjLTQwZjMtOTk4Zi1hOWRlNjIwNzhmNmIiLCJ1c2VyX3V1aWQiOiJCQUdEVlpISDNZRUNGM05YIn0.v3Vzh881UwXSuBnuYV2zIHmizvf81MUYion_ddcT3mWuvkjQoXxWisDdQVUrDvygXtXKk3zysJA8Dh2rTl54Ig";
            $clinic_id = '30';
        } else {
            $calendlytoken = '';
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $eventurl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $calendlytoken . "",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            //echo "<pre>";print_r($response->resource);
            $start_time = $response->resource->start_time;
            $resourcename = $response->resource->name;
            $datetime = Carbon::now();
            $datetime = date('m/d/Y H:i:s');
            // create a $dt object with the UTC timezone
            $dt = new DateTime($start_time, new \DateTimeZone('UTC'));

            // change the timezone of the object without changing its time
            $dt->setTimezone(new \DateTimeZone('America/New_York'));

            // format the datetime
            $bookdate = $dt->format('m/d/Y H:i:s');

            if (stristr($resourcename, "Sullivan Dental Implants") !== false) {
                $clinic_id = '98';
            }
            if (stristr($resourcename, "Eon Clinic Dental Implant Consultation") !== false) {
                $clinic_id = '116';
            }
            if (stristr($resourcename, "Wisdom Teeth Removal Consultation") !== false) {
                $clinic_id = '124';
            }
            if (stristr($resourcename, "PV Smiles: 15 Min Dental Implant Discovery Session") !== false) {
                $clinic_id = '81';
            }


            $crmCustomer = CrmCustomer::where('email', $email)->where('clinic_id', $clinic_id)->first();
            if ($crmCustomer) {

                $crmCustomer->consultation_booked_date = $bookdate;
                $crmCustomer->convert_deal_date = $datetime;
                $crmCustomer->convert_to_deal = 1;
                $crmCustomer->status_id = 12;
                $crmCustomer->badge = "Self Scheduled";
                $crmCustomer->save();

                $CrmNote = new CrmNote;
                $CrmNote->note = "Self-scheduling from Calendly";
                $CrmNote->user_id = 1;
                $CrmNote->customer_id = $crmCustomer->id;
                $CrmNote->save();

                Calendly::where('id', $calendly['id'])->update(['lead_id' => $crmCustomer->id, 'crm_id' => $crmCustomer->clinic_id]);

            }

        }


        http_response_code(200); //Acknowledge you received the response
    }

    // Method to send emails to all email addresses in the account
    protected function sendEmailsToAccountEmails($crmCustomer)
    {
        // Fetch all email addresses associated with the account
        $accountEmails = $this->getAccountEmails(); // Assuming you have a method to get account emails

        foreach ($accountEmails as $email) {
            Mail::to($email)->send(new LeadCreatedMail($crmCustomer));
        }
    }

    public function teammember(Request $request)
    {
        $clinicId = $request->query('crtx_clinic_id');
        $DataJson = file_get_contents('php://input');
        $Data = json_decode($DataJson, true);

        if (isset($Data['first_name']) && isset($Data['last_name']) && isset($Data['phone']) && isset($Data['message']) ) {
            $crmCustomer = CrmCustomer::where('first_name', $Data['first_name'])
                ->where('last_name', $Data['last_name'])
                ->where('phone', $Data['phone'])
                ->where('clinic_id', $clinicId)
                ->first();
            if (isset($crmCustomer['id'])) {
                $CrmNote = new CrmNote;
                $CrmNote->note = $Data['message'];
                $CrmNote->user_id = 1;
                $CrmNote->customer_id = $crmCustomer['id'];
                $CrmNote->save();

                // Check if the 'Reconnecting' tag exists
                $tag = Tag::where('tagName', 'CallBack')->where('clinic_id', $clinicId)->first();
                // If the tag does not exist, create it
                if (!$tag) {
                        $tag = new Tag();
                        $tag->tagName = 'CallBack';
                        $tag->clinic_id = $clinicId;
                        $tag->save();
                }
                // Create a new TagLeadMapping entry
                $tagLeadMapping = new TagLeadMapping();
                $tagLeadMapping->tag_id = $tag->id;
                $tagLeadMapping->lead_id = $crmCustomer['id'];
                $tagLeadMapping->save();

                http_response_code(200);
            }
        }
    }
}

