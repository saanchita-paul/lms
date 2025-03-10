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
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use DB;

class SendEmail implements ShouldQueue
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

        $currentemail_sequence = $lead->email_sequence;
        $newemail_sequence = $currentemail_sequence + 1; 
        
        if($newemail_sequence == 36){
            $CrmNote = new CrmNote;
            $CrmNote->note = "End of the Email Automation";
            $CrmNote->user_id = 1;
            $CrmNote->customer_id = $this->crmCustomer->id;
            $CrmNote->save();
            
            $lead->automation = 1;
            $lead->status_id = 9;
            $lead->save();

        }else{
            
            $templateValue = ManageTemplate::where('clinic_id', $lead->clinic_id)
               ->where('dayinterval', $newemail_sequence)
               ->first();               
            if (!empty($templateValue)) {
               $automationsequence = ManageTemplate::where('clinic_id', $lead->clinic_id)
               ->where('dayinterval', $newemail_sequence)
               ->first();
            } else {
                $automationsequence = Automationsequence::where('dayinterval',$newemail_sequence)->first();
            }
            
            $email_subject = $automationsequence->email_subject;            

            if($email_subject == 'hold')
            {
                /*$CrmNote = new CrmNote;
                $CrmNote->note = "HOLD - NO COMMUNICATION (Email)";
                $CrmNote->user_id = 1;
                $CrmNote->customer_id = $this->crmCustomer->id;
                $CrmNote->save();*/

            }    
            else{

                $email_subject = str_replace("{{REPLACE_FIRST_NAME}}", $lead->first_name, $email_subject);
                $email_subject = str_replace("{{REPLACE_DOCTOR}}", $lead->clinic->dr_name, $email_subject);

                $clinic_name =$lead->clinic->clinic_name;
                $clinic_drname = $lead->clinic->dr_name;
                $hotline    = $lead->clinic->hotline_phone_number;
                $link1 = $lead->clinic->link1;
                $link2 = $lead->clinic->link2;
                $link3 = $lead->clinic->link3;
                $website = $lead->clinic->microsite_website;
                $clinic_scheduling_link = $lead->clinic->scheduling_link;
                $nurturing_display_name = $lead->clinic->nurturing_display_name;


                if($lead->clinic->lead_center != "Yes"){
                    $hotline    = $lead->clinic->office_number;
                }

                $email_template = $automationsequence->email_template;
                $email_template = str_replace("{{REPLACE_FIRST_NAME}}", $lead->first_name, $email_template);
                $email_template = str_replace("{{REPLACE_PRACTICE}}", $clinic_name, $email_template);
                $email_template = str_replace("{{REPLACE_DOCTOR}}", $clinic_drname, $email_template);
                $email_template = str_replace("{{REPLACE_PHONE}}", $hotline, $email_template);
                if (!empty($nurturing_display_name)) {
                    $email_template = str_replace("Grace", $nurturing_display_name, $email_template);
                }

                $email_template = str_replace("{{REPLACE_LINK_1}}", $link1, $email_template);
                $email_template = str_replace("{{REPLACE_LINK_2}}", $link2, $email_template);
                $email_template = str_replace("{{REPLACE_LINK_3}}", $link3, $email_template);
                $email_template = str_replace("{{REPLACE_WEBSITE}}", $website, $email_template);
                $email_template = str_replace("{{REPLACE_SIGNATURE}}", "The Dental Implant Team at ".$clinic_name." ".$hotline, $email_template);

                if($lead->clinic->lead_center != "Yes"){
                    $email_template = str_replace("https://calendly.com/implanthotline/15min", $clinic_scheduling_link, $email_template);
                }

                if($lead->clinic->smtpMailer == '')  {
                        $email_template = str_replace("<a href='{unsubscribe}'>Unsubscribe</a>", "", $email_template);
                        $email_template = str_replace("Unsubscribe", "", $email_template);
                }
                $to = $lead->email;
                if(stristr($to, "@noreply.com") !== false){
                    $CrmNote = new CrmNote;
                    $CrmNote->note = "Automation Email Not Sent: Invalid Email address";
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
                    $CrmNote->note = "Automation Email Sent ".$newemail_sequence. "<br>Subject: ".$email_subject."<br>".$email_template;
                    $CrmNote->user_id = 1;
                    $CrmNote->customer_id = $this->crmCustomer->id;
                    $CrmNote->save();  
                }                  
            }
            
            $lead->email_sequence = $newemail_sequence;
            $lead->save();
        }
    }
}