<?php

namespace App\Jobs;

use App\Mail\AppointmentReminderEmail;
use App\Models\CrmCustomer;
use App\Models\CrmNote;
use App\Services\TemplateService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AppointmentReminderEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $crm_customer, $template, $clinic, $appointment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($crm_customer, $template, $clinic, $appointment)
    {
        $this->crm_customer = $crm_customer;
        $this->template = $template;
        $this->clinic = $clinic;
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            config([
                'mail.mailers.smtp.host' => $this->clinic->smtpServer,
                'mail.mailers.smtp.port' => $this->clinic->smtpPort,
                'mail.mailers.smtp.encryption' => 'tls',
                'mail.mailers.smtp.username' => $this->clinic->smtpUsername,
                'mail.mailers.smtp.password' => $this->clinic->smtpPassword,
            ]);

            Mail::purge();

            $templateService = new TemplateService();
            $email_template = $templateService->getTemplateByClinicAndType($this->clinic->id, 'reminder-email');

            // Placeholder data
            $data = [
                'lead_first_name' => $this->crm_customer->first_name,
                'lead_last_name' => $this->crm_customer->last_name,
                'lead_email' => $this->crm_customer->email,
                'lead_phone' => $this->crm_customer->phone,
                'clinic_dr_name' => $this->clinic->dr_name,
                'appointment_date' => Carbon::createFromFormat('Y-m-d', $this->appointment->date)->format('m/d/Y'),
                'appointment_time' => Carbon::createFromFormat('H:i:s', $this->appointment->time)->format('h:i A'),
                'appointment_patient_type' => !empty($this->appointment->patient_type)
                    ? $this->appointment->patient_type
                    : '',
                'appointment_service_name' => !empty($this->appointment->services)
                    ? $this->appointment->services->name
                    : '',
                'appointment_comments' => !empty($this->appointment->comments)
                    ? $this->appointment->comments
                    : '',
                'appointment_change_link' => !empty($this->appointment->id)
                    ? '<a href="' . url('/crtx/schedule-appointment/' . $this->clinic->id . '/sa-step-1?id=' . $this->appointment->id) . '">Change appointment details</a>'
                    : '<a href="' . url('/crtx/schedule-appointment/' . $this->clinic->id . '/sa-step-1') . '">Change appointment details</a>',
                'practice_contact_info'=> $this->clinic->office_number,
                'practice_name'=>$this->clinic->clinic_name
            ];

            if(!empty($this->crm_customer->email)){
                if ($email_template) {
                    // Case when a template is found
                    $emailBody = $templateService->appointmentreplacePlaceholders($email_template->body, $data);
                    $emailSubject = $templateService->appointmentreplacePlaceholders($email_template->subject, $data);
                    Mail::to($this->crm_customer->email)->send(new AppointmentReminderEmail($emailBody, $emailSubject, $this->clinic));
                }else{
                    Mail::to($this->crm_customer->email)->send($this->template);
                }

                CrmNote::create([
                    'note' => 'Appointment Reminder Email Sent',
                    'user_id' => 1,
                    'customer_id' => $this->crm_customer->id,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Mail Sending Failed | ' . $e->getMessage());
        }
    }
}
