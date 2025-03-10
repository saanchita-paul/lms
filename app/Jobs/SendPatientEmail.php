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
use App\Models\PatientCampaign;
use App\Models\ManagePatientJourneyTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use DB;
use Carbon\Carbon;

class SendPatientEmail implements ShouldQueue
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

        $currentemail_sequence = $lead->patient_journey_email_sequence;

        $newemail_sequence = $currentemail_sequence + 1; 

        if($newemail_sequence == 10){
            $CrmNote = new CrmNote;
            $CrmNote->note = "End of the Patient Journey Email";
            $CrmNote->user_id = 1;
            $CrmNote->customer_id = $this->crmCustomer->id;
            $CrmNote->save();
            
            $lead->patient_journey_automation = 1;
            $lead->save();

        }else{

            $totalemailTemplates  = PatientCampaign::count();

            $templateValue = ManagePatientJourneyTemplate::where('clinic_id', $lead->clinic_id)
               ->where('dayinterval', $newemail_sequence)
               ->first();   

            if (!empty($templateValue)) {
               $automationsequence = ManagePatientJourneyTemplate::where('clinic_id', $lead->clinic_id)
               ->where('dayinterval', $newemail_sequence)
               ->first();
            } else {
                $automationsequence = PatientCampaign::where('dayinterval',$newemail_sequence)->first();
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

            if($newemail_sequence == 1){
                $scheduledAppointment = CrmCustomer::where('id',$this->crmCustomer->id)->first();
            }
            else
            {
                $scheduledAppointment = CrmCustomer::where('id',$this->crmCustomer->id)->whereBetween('next_mail_date', [now(), now()->addHours(12)])->first();                
            }
            
            
            if (!empty($scheduledAppointment) && $scheduledAppointment != null) {
                $email_subject = $automationsequence->email_subject;

                $email_subject = str_replace("{{REPLACE_FIRST_NAME}}", $lead->first_name, $email_subject);
                $email_subject = str_replace("{{REPLACE_DOCTOR}}", $lead->clinic->dr_name, $email_subject);

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

                $email_template = $automationsequence->email_template;
                $email_template = str_replace("{{REPLACE_NAME}}", $lead->first_name, $email_template);
                $email_template = str_replace("{{REPLACE_DOCTOR}}", $clinic_drname, $email_template);
                $email_template = str_replace("{{REPLACE_PHONE}}", $hotline, $email_template);
                $email_template = str_replace("{{REPLACE_TESTIMONIAL}}", $usertestimonials, $email_template);
                $email_template = str_replace("{{REPLACE_TECHNOLOGY}}", $listtechnology, $email_template);
                $email_template = str_replace("{{REPLACE_DATE}}", $formattedDate, $email_template);
                $email_template = str_replace("{{REPLACE_TIME}}", $formattedTime, $email_template);

                $email_template = str_replace("{{REPLACE_SIGNATURE}}", "The Dental Implant Team at ".$clinic_name." ".$hotline, $email_template);

                if($lead->clinic->smtpMailer == '')  {
                        $email_template = str_replace("<a href='{unsubscribe}'>Unsubscribe</a>", "", $email_template);
                        $email_template = str_replace("Unsubscribe", "", $email_template);
                }
                $to = $lead->email;

                if(stristr($to, "@noreply.com") !== false){
                    $CrmNote = new CrmNote;
                    $CrmNote->note = "Patient Journey Email Not Sent: Invalid Email address";
                    $CrmNote->user_id = 1;
                    $CrmNote->customer_id = $this->crmCustomer->id;
                    $CrmNote->save();  
                }else{ 
                    if($lead->clinic->lead_center == "Yes"){  
                     if($lead->clinic->smtpMailer == '')  {
                            Mail::mailer('smtp')->send(array(), array(), function ($message) use ($email_template,$to,$email_subject) {
                                  $message->to($to)
                                    ->subject($email_subject)
                                    ->from('consultation@thedentalimplanthotline.com', 'The Dental Implant Hotline')
                                    ->setBody($email_template, 'text/html');
                                });
                     }
                     else{
                            Mail::mailer($lead->clinic->smtpMailer)->send(array(), array(), function ($message) use ($email_template,$to,$email_subject) {
                                  $message->to($to)
                                    ->subject($email_subject)
                                    ->from('consultation@thedentalimplanthotline.com', 'The Dental Implant Hotline')
                                    ->setBody($email_template, 'text/html');
                                });
                     }                   
                    
                    }else{  
                       Mail::mailer('smtp')->send(array(), array(), function ($message) use ($email_template,$to,$email_subject,$lead) {
                                  $message->to($to)
                                    ->subject($email_subject)
                                     ->from("noreply@".$lead->clinic->microsite_website, $lead->clinic->clinic_name)        
                                    ->replyTo($lead->clinic->email, $lead->clinic->clinic_name)
                                    ->setBody($email_template, 'text/html');
                                }); 
                    }

                    $CrmNote = new CrmNote;
                    $CrmNote->note = "Patient Journey Email Sent ".$newemail_sequence. "<br>Subject: ".$email_subject."<br>".$email_template;
                    $CrmNote->user_id = 1;
                    $CrmNote->customer_id = $this->crmCustomer->id;
                    $CrmNote->save();  
               

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
                    $lead->patient_journey_email_sequence = $newemail_sequence;
                } 
            }
            $lead->save();
        }
    }
}