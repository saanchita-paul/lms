<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\AutomationLog;
use App\Models\CrmCustomer;
use App\Models\Automationsequence;
use App\Models\ManageTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Carbon\Carbon;
use App\Models\CrmChat;
use Twilio\Rest\Client;
use App\Models\CrmStatus;
use App\Models\CrmNote;

class CheckAutomationLogSms implements ShouldQueue
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
        
        $this->crmCustomer = $crmCustomer;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

       
        if ($this->crmCustomer) {
            $statusId = $this->crmCustomer->status_id;
        
            // Set all values to false initially
            $automationRule = array_fill_keys(range(1, 17), false);
        
            // Set the value to true for the specified status_id
            $automationRule[$statusId] = true;
        
            // Convert the array to JSON string
            $automationRuleJson = json_encode($automationRule);
            if ($this->crmCustomer->automation_rule === null) {
                
                $this->crmCustomer->automation_rule = $automationRuleJson;                
             
                $this->crmCustomer->save();
            } 

        }

        $existingRecord = AutomationLog::where('clinic_id', $this->crmCustomer->clinic_id)
            ->where('lead_id', $this->crmCustomer->id)
            ->where('status_id', $this->crmCustomer->status_id)
            ->whereIn('type', ['sms', 'Hold SMS'])
            ->orderBy('created_at', 'desc')
            ->first();
                       
        

        
       
        if ($existingRecord) {
            $currentdayinterval = $existingRecord->dayinterval;
            $newsms_sequence = $currentdayinterval + 1;
        } else {
            $newsms_sequence = 1;
        }
       

        $textTemplate = ManageTemplate::where('clinic_id', $this->crmCustomer->clinic_id)
        ->where('status_id', $this->crmCustomer->status_id)
        ->where('dayinterval', $newsms_sequence)
        ->value('text_template');
    
        if ($textTemplate === null) {
            $textTemplate = Automationsequence::where('dayinterval', $newsms_sequence)
                ->where('status_id', $this->crmCustomer->status_id)
                ->value('text_template');
        
            if ($textTemplate === null) {
                // Handle the case where text_template is not found in either table
                // For example, log a warning
                Log::warning('No text template found for clinic ' . $this->crmCustomer->clinic_id . ', status ' . $this->crmCustomer->status_id . ', and day interval ' . $newsms_sequence);
            }
        }

        $text_template = strip_tags($textTemplate);

        $status = CrmStatus::find($this->crmCustomer->status_id);

        $statusName = $status->name;

        $automationType = "sms";
        if($text_template == 'Hold' || $text_template == 'hold' || $text_template == '<p>Hold</p>' || $text_template == '<p>hold</p>' )
        {
            $automationType = "Hold SMS";
        }
        else{
            $lead = $this->crmCustomer;
            if($lead)
            {
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
                    return ;

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
                        $CrmNote->note = $statusName." Automation SMS Sent ".$newsms_sequence;
                        $CrmNote->user_id = 1;
                        $CrmNote->customer_id = $this->crmCustomer->id;
                        $CrmNote->save();

                        $count = Automationsequence::where('status_id', $this->crmCustomer->status_id)
                ->count();
                    if ($count == $newsms_sequence) {
                        // $crmCustomer = CrmCustomer::where('id', $this->crmCustomer->id)->first();
                        
                            // Construct the new automation_rule JSON
                        if ($this->crmCustomer) {
                            // Retrieve the automation_rule from the fetched record
                            $automationRule = $this->crmCustomer->automation_rule;
                            
                            // Decode the JSON string into an associative array
                            $newAutomationRule = json_decode($automationRule, true);
                            // Check if $this->crmCustomer->status_id exists as a key in the newAutomationRule array
                            if (array_key_exists($this->crmCustomer->status_id, $newAutomationRule)) {
                                // Change the value of the corresponding key to false
                                $newAutomationRule[$this->crmCustomer->status_id] = false;

                                // $leadstore = CrmCustomer::findOrFail($this->crmCustomer->id);
                                $this->crmCustomer->automation_rule = json_encode($newAutomationRule);
                                $this->crmCustomer->save();
                            }  
                           
                        }
                    }
              
                    } catch (Exception $e) {
                        $CrmNote = new CrmNote;
                        $CrmNote->note = "Error: ".$statusName." Automation SMS".$newsms_sequence;
                        $CrmNote->user_id = 1;
                        $CrmNote->customer_id = $this->crmCustomer->id;
                        $CrmNote->save();
                    }                    
                    
                }
            }
        } 

        $AutomationLog = new AutomationLog;
        $AutomationLog->clinic_id = $this->crmCustomer->clinic_id;
        $AutomationLog->lead_id = $this->crmCustomer->id;
        $AutomationLog->status_id = $this->crmCustomer->status_id;
        $AutomationLog->dayinterval = $newsms_sequence;
        $AutomationLog->type = $automationType;
        $AutomationLog->created_at = Carbon::now();
        $AutomationLog->save();

    }
}
