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
use App\Models\PatientCampaign;
use App\Models\ManagePatientJourneyTemplate;
use App\Models\CrmChat;
use App\Models\Clinic;
use Twilio\Rest\Client;
use Carbon\Carbon;

class SendPatientSms implements ShouldQueue
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
        $lead = CrmCustomer::with(['clinic'])->where('id',$this->crmCustomer->id)->first();

        $currentsms_sequence = $lead->patient_journey_sms_sequence;
        $newsms_sequence = $currentsms_sequence + 1; 
        
        if($newsms_sequence == 10){

            $CrmNote = new CrmNote;
            $CrmNote->note = "End of the Patient Journey SMS";
            $CrmNote->user_id = 1;
            $CrmNote->customer_id = $this->crmCustomer->id;
            $CrmNote->save();
            
            $lead->save();

        }else{

            $templateValue = ManagePatientJourneyTemplate::where('clinic_id', $lead->clinic_id)
                   ->where('dayinterval', $newsms_sequence)
                   ->first();   

            if (!empty($templateValue)) {
                   $automationsequence = ManagePatientJourneyTemplate::where('clinic_id', $lead->clinic_id)
                   ->where('dayinterval', $newsms_sequence)
                   ->first();
            } else {
                    $automationsequence = PatientCampaign::where('dayinterval',$newsms_sequence)->first();
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
                $totalemailTemplates  = PatientCampaign::count();
                $templateValue = ManagePatientJourneyTemplate::where('clinic_id', $lead->clinic_id)
                   ->where('dayinterval', $newsms_sequence)
                   ->first();   

                if (!empty($templateValue)) {
                   $automationsequence = ManagePatientJourneyTemplate::where('clinic_id', $lead->clinic_id)
                   ->where('dayinterval', $newsms_sequence)
                   ->first();
                } else {
                    $automationsequence = PatientCampaign::where('dayinterval',$newsms_sequence)->first();
                }  
               
                $consultsbookedDate = Carbon::parse($lead->consultation_booked_date);
                $consultsbookeddateOnly = $consultsbookedDate->format('Y-m-d');
                $currentDate = Carbon::now();
                $currentDateOnly = $currentDate->format('Y-m-d');

                $startDate = Carbon::parse($consultsbookeddateOnly);
                $endDate = Carbon::parse($currentDateOnly);            

                $diffInDays = $endDate->diffInDays($startDate)-1;

                $todayDate = Carbon::parse($currentDate);
               
                $nextMailDate = Carbon::parse($lead->next_mail_date);

                if($newsms_sequence == 1){
                    $scheduledAppointment = CrmCustomer::where('id',$this->crmCustomer->id)->first();
                }
                else
                {
                    $scheduledAppointment = CrmCustomer::where('id',$this->crmCustomer->id)->whereBetween('next_mail_date', [now(), now()->addHours(12)])->first();                
                }

                if (!empty($scheduledAppointment) && $scheduledAppointment != null) {

                    $clinic_name =$lead->clinic->clinic_name;
                    $clinic_drname = $lead->clinic->dr_name;
                    $hotline    = $lead->clinic->hotline_phone_number;
                    $usertestimonials    = $lead->clinic->usertestimonials;
                    $listtechnology    = $lead->clinic->listtechnology;
                    $link1 = $lead->clinic->link1;
                    $link2 = $lead->clinic->link2;
                    $link3 = $lead->clinic->link3;
                    $website = $lead->clinic->microsite_website;

                    $leadDate = Carbon::parse($lead->consultation_booked_date);

                    $formattedDate = $leadDate->format('Y-m-d');
                    $formattedTime = $leadDate->format('H:i:s');

                    if($lead->clinic->lead_center != "Yes"){
                        $hotline    = $lead->clinic->office_number;
                    }

                    $receiverNumber = $lead->phone;

                    if($lead->phone == '+11111111111'){
                        $CrmNote = new CrmNote;
                        $CrmNote->note = "Patient Journey Automation SMS Not Sent ".$newsms_sequence;
                        $CrmNote->user_id = 1;
                        $CrmNote->customer_id = $this->crmCustomer->id;
                        $CrmNote->save();

                    }
                    else{
                        $message =  strip_tags($text_template);

                        $message = str_replace("{{REPLACE_NAME}}", $lead->first_name, $message);
                        
                        $message = str_replace("{{REPLACE_DOCTOR}}", $clinic_drname, $message);
                        $message = str_replace("{{REPLACE_PHONE}}", $hotline, $message);

                        $message = str_replace("{{REPLACE_TESTIMONIAL}}", $usertestimonials, $message);
                        $message = str_replace("{{REPLACE_TECHNOLOGY}}", $listtechnology, $message);

                        $message = str_replace("{{REPLACE_LINK_1}}", $link1, $message);
                        $message = str_replace("{{REPLACE_LINK_2}}", $link2, $message);
                        $message = str_replace("{{REPLACE_LINK_3}}", $link3, $message);
                        $message = str_replace("{{REPLACE_WEBSITE}}", $website, $message);

                        $message = str_replace("{{REPLACE_DATE}}", $formattedDate, $message);
                        $message = str_replace("{{REPLACE_TIME}}", $formattedTime, $message);

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
                            $CrmNote->note = "Patient Journey Automation SMS Sent ".$newsms_sequence;
                            $CrmNote->user_id = 1;
                            $CrmNote->customer_id = $this->crmCustomer->id;
                            $CrmNote->save();
                  
                        } catch (Exception $e) {
                            $CrmNote = new CrmNote;
                            $CrmNote->note = "Error: Patient Journey Automation SMS".$newsms_sequence;
                            $CrmNote->user_id = 1;
                            $CrmNote->customer_id = $this->crmCustomer->id;
                            $CrmNote->save();
                        }

                        if($diffInDays > 7){   
                           $hourData = $diffInDays*24;           
                           $templateIndex = $hourData / $totalemailTemplates;
                           $lead->next_mail_date = Carbon::parse($lead->next_mail_date)->addHours($templateIndex);
                        }
                        else
                        {  
                            $hourData = $diffInDays*24;  
                            $templateIndex = $hourData / $totalemailTemplates;
                            $lead->next_mail_date = Carbon::parse($lead->next_mail_date)->addHours($templateIndex);
                        }

                        $lead->patient_journey_sms_sequence = $newsms_sequence;
                    }
                }            
            }
            $lead->save();
        }
    }
}