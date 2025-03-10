<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CrmNote;
use App\Models\CrmCustomer;
use App\Models\Automationsequence;
use App\Models\ManageTemplate;
use App\Models\CrmChat;
use App\Models\Clinic;
use Twilio\Rest\Client;
use DB;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $crmCustomer;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(crmCustomer $crmCustomer)
    {
        //
        $this->crmCustomer = $crmCustomer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        

        $lead = CrmCustomer::with(['clinic'])->where('id',$this->crmCustomer->id)->first();
        //
        $currentsms_sequence = $lead->sms_sequence;
        
        $newsms_sequence = $currentsms_sequence + 1; 
        
        if($newsms_sequence == 36){

            $CrmNote = new CrmNote;
            $CrmNote->note = "End of the SMS Automation";
            $CrmNote->user_id = 1;
            $CrmNote->customer_id = $this->crmCustomer->id;
            $CrmNote->save();
            
            $lead->automation = 1;
            $lead->status_id = 9;
            $lead->save();

        }else{

            $templateValue = ManageTemplate::where('clinic_id', $lead->clinic_id)
               ->where('dayinterval', $newsms_sequence)
               ->first(); 

            if (!empty($templateValue)) {
               $automationsequence = ManageTemplate::where('clinic_id', $lead->clinic_id)
               ->where('dayinterval', $newsms_sequence)
               ->first();
            } 
            else {
                $automationsequence = Automationsequence::where('dayinterval',$newsms_sequence)->first();
            }

            $text_template = $automationsequence->text_template;

            if($text_template == 'hold')
            {
                /*$CrmNote = new CrmNote;
                $CrmNote->note = "HOLD - NO COMMUNICATION (SMS)";
                $CrmNote->user_id = 1;
                $CrmNote->customer_id = $this->crmCustomer->id;
                $CrmNote->save();*/

            }    
            else{

                //
                $clinic_name =$lead->clinic->clinic_name;
                $clinic_drname = $lead->clinic->dr_name;
                $clinic_scheduling_link = $lead->clinic->scheduling_link;
                $hotline    = $lead->clinic->hotline_phone_number;
                $link1 = $lead->clinic->link1;
                $link2 = $lead->clinic->link2;
                $link3 = $lead->clinic->link3;
                $website = $lead->clinic->microsite_website;
                $nurturing_display_name = $lead->clinic->nurturing_display_name;
                

                if($lead->clinic->lead_center != "Yes"){
                    $hotline    = $lead->clinic->office_number;
                }

                $receiverNumber = $lead->phone;

                if($lead->phone == '+11111111111'){
                    $CrmNote = new CrmNote;
                    $CrmNote->note = "Automation SMS Not Sent ".$newsms_sequence;
                    $CrmNote->user_id = 1;
                    $CrmNote->customer_id = $this->crmCustomer->id;
                    $CrmNote->save();

                }
                else{
                    $message =  strip_tags($text_template);

                    if($lead->clinic->lead_center != "Yes"){
                        $message = str_replace("https://calendly.com/implanthotline/15min", $clinic_scheduling_link, $message);
                    }

                    $message = str_replace("{{REPLACE_FIRST_NAME}}", $lead->first_name, $message);
                    $message = str_replace("{{REPLACE_PRACTICE}}", $clinic_name, $message);

                    $message = str_replace("{{REPLACE_DOCTOR}}", $clinic_drname, $message);
                    $message = str_replace("{{REPLACE_PHONE}}", $hotline, $message);
                    if (!empty($nurturing_display_name)) {
                        $message = str_replace("Grace", $nurturing_display_name, $message);
                    }

                    $message = str_replace("{{REPLACE_LINK_1}}", $link1, $message);
                    $message = str_replace("{{REPLACE_LINK_2}}", $link2, $message);
                    $message = str_replace("{{REPLACE_LINK_3}}", $link3, $message);
                    $message = str_replace("{{REPLACE_WEBSITE}}", $website, $message);

                    // Remove &nbsp; entities
                    $message = html_entity_decode($message);

                    $message .= ' To opt-out of future messages, simply reply with STOP.';

                    $account_sid = $lead->clinic->twilio_subid;
                    $auth_token = $lead->clinic->twilio_token;
                    $twilio_number = $lead->clinic->twilio_number;

                    $request['from']  = $twilio_number;
                    $request['to']  = $receiverNumber;

                    $crmChat = CrmChat::create(
                    [
                        'user_id' => 1,
                        'lead_id' => $lead->id,
                        'inbound' => 0,
                        'chat' => $message,
                        'from' => $twilio_number,
                        'to' => $receiverNumber,
                        'is_sms' => 1,
                        'platform' => 'sms'
                    ]
                    );
                    
                    try {
              
                        $client = new Client($account_sid, $auth_token);
                        $client->messages->create($receiverNumber, [
                            'from' => $twilio_number,
                            'statusCallback' => route('sms.inbound', env('SMS_WEBHOOK')), 
                            'body' => $message]);
              
                        $CrmNote = new CrmNote;
                        $CrmNote->note = "Automation SMS Sent ".$newsms_sequence;
                        $CrmNote->user_id = 1;
                        $CrmNote->customer_id = $this->crmCustomer->id;
                        $CrmNote->save();
              
                    } catch (Exception $e) {
                        $CrmNote = new CrmNote;
                        $CrmNote->note = "Error: Automation SMS".$newsms_sequence;
                        $CrmNote->user_id = 1;
                        $CrmNote->customer_id = $this->crmCustomer->id;
                        $CrmNote->save();
                    }
                }
            }

            $lead->sms_sequence = $newsms_sequence;
            $lead->save();
        }
    }
}
