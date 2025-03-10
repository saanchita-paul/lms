<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\AutomationLog;
use App\Models\CrmCustomer;
use App\Models\Automationsequence;
use App\Models\ManageTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\CrmStatus;
use App\Models\CrmNote;

class CheckAutomationLogEmail implements ShouldQueue
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
            ->whereIn('type', ['email', 'Hold Email'])
            ->orderBy('created_at', 'desc')
            ->first();

        if ($existingRecord) {
            $currentdayinterval = $existingRecord->dayinterval;
            $newemail_sequence = $currentdayinterval + 1;
        } else {
            $newemail_sequence = 1;
        }

        $emailSubject = ManageTemplate::where('clinic_id', $this->crmCustomer->clinic_id)
            ->where('status_id', $this->crmCustomer->status_id)
            ->where('dayinterval', $newemail_sequence)
            ->value('email_subject');

        $emailTemplate = ManageTemplate::where('clinic_id', $this->crmCustomer->clinic_id)
            ->where('status_id', $this->crmCustomer->status_id)
            ->where('dayinterval', $newemail_sequence)
            ->value('email_template');

        if ($emailSubject === null || $emailTemplate === null) {
            $templateValues = Automationsequence::where('dayinterval', $newemail_sequence)
                ->where('status_id', $this->crmCustomer->status_id)
                ->select('email_subject', 'email_template')
                ->first();

            if ($emailSubject === null && $templateValues->email_subject !== null) {
                $emailSubject = $templateValues->email_subject;
            }

            if ($emailTemplate === null && $templateValues->email_template !== null) {
                $emailTemplate = $templateValues->email_template;
            }

            if ($emailSubject === null || $emailTemplate === null) {
                // Handle the case where email_subject or email_template is not found in either table
                // For example, log a warning
                Log::warning('No email subject or email template found for clinic ' . $this->crmCustomer->clinic_id . ', status ' . $this->crmCustomer->status_id . ', and day interval ' . $newemail_sequence);
            }
        }

        // $emailSubject and $emailTemplate now contain the values if found, otherwise they may still be null



        $status = CrmStatus::find($this->crmCustomer->status_id);

        $statusName = $status->name;

        $email_subject = html_entity_decode(strip_tags($emailSubject));


        $lead = $this->crmCustomer;


        $automationType = "email";
        if($email_subject == 'Hold' || $email_subject == 'hold' || $email_subject == '<p>Hold</p>' || $email_subject == '<p>hold</p>' )
        {
            $automationType = "Hold Email";
        }
        else
        {
            if($lead)
            {
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

                $email_subject = str_replace("{{REPLACE_FIRST_NAME}}", $lead->first_name, $email_subject);
                $email_subject = str_replace("{{REPLACE_PRACTICE}}", $clinic_name, $email_subject);
                $email_subject = str_replace("{{REPLACE_DOCTOR}}", $clinic_drname, $email_subject);

                $email_template = $emailTemplate;
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
                        if($lead->clinic->smtpMailer == ''){
                            Mail::mailer('smtp')->send(array(), array(), function ($message) use ($email_template,$to,$email_subject) {
                                    $message->to($to)
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
                                Mail::send([], [], function (Message $message) use ($email_template, $email_subject, $to) {
                                    $message->to($to)
                                        ->from('consultation@thedentalimplanthotline.com', 'The Dental Implant Hotline')
                                        ->subject($email_subject)
                                        ->setBody($email_template, 'text/html');;
                                });
                            }else{
                                Mail::purge();
                                Mail::send([], [], function (Message $message) use ($email_template, $email_subject, $to,$lead) {
                                    $message->to($to)
                                        ->subject($email_subject)
                                        ->from("admin@".$lead->clinic->microsite_website, $lead->clinic->clinic_name)
                                        ->replyTo($lead->clinic->email_id, $lead->clinic->clinic_name)
                                        ->setBody($email_template, 'text/html');
                                });
                            }
                        }
                    $CrmNote = new CrmNote;
                    $CrmNote->note = $statusName." Automation Email Sent ".$newemail_sequence. "<br>Subject: ".$email_subject."<br>".$email_template;
                    $CrmNote->user_id = 1;
                    $CrmNote->customer_id = $this->crmCustomer->id;
                    $CrmNote->save();

                    $count = Automationsequence::where('status_id', $this->crmCustomer->status_id)
                        ->count();
                    if ($count == $newemail_sequence) {
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

                                // $this->crmCustomer = CrmCustomer::findOrFail($this->crmCustomer->id);
                                $this->crmCustomer->automation_rule = json_encode($newAutomationRule);
                                $this->crmCustomer->save();
                            }
                        }
                    }
                }
            }
        }



        $AutomationLog = new AutomationLog;
        $AutomationLog->clinic_id = $this->crmCustomer->clinic_id;
        $AutomationLog->lead_id = $this->crmCustomer->id;
        $AutomationLog->status_id = $this->crmCustomer->status_id;
        $AutomationLog->dayinterval = $newemail_sequence;
        $AutomationLog->type = $automationType;
        $AutomationLog->created_at = Carbon::now();
        $AutomationLog->save();

    }
}
