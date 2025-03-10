<?php

namespace App\Jobs;

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
use Twilio\Rest\Client;

class AppointmentReminderTextJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer, $appointment, $clinic;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customer, $appointment, $clinic)
    {
        $this->customer = $customer;
        $this->appointment = $appointment;
        $this->clinic = $clinic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $date = Carbon::createFromFormat('Y-m-d', $this->appointment->date)->format('m/d/Y');
            $time = Carbon::createFromFormat('H:i:s', $this->appointment->time)->format('h:i A');
            $message = "Hi {$this->customer->first_name} {$this->customer->last_name}, this is a reminder that your appointment with Dr. {$this->clinic->dr_name} is coming up on {$date} at {$time}.
            Need to reschedule? Contact us at {$this->clinic->office_number} at least 24 hours in advance.
            Looking forward to your visit! – {$this->clinic->clinic_name}";

            $templateService = new TemplateService();
            $text_template = $templateService->getTemplateByClinicAndType($this->clinic->id, 'reminder-text');

            // Placeholder data
            $data = [
                'lead_first_name' => $this->customer->first_name,
                'lead_last_name' => $this->customer->last_name,
                'lead_email' => $this->customer->email,
                'lead_phone' => $this->customer->phone,
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

            $phoneNumber = $this->customer->phone;
            if(!empty($phoneNumber) && !empty($this->clinic->twilio_subid) && !empty($this->clinic->twilio_number) && !empty($this->clinic->twilio_token)){
                $twilio = new Client($this->clinic->twilio_subid, $this->clinic->twilio_token);
                if($text_template){
                    $emailBody = $templateService->appointmentreplacePlaceholders($text_template->body, $data);

                    $twilio->messages->create(
                        $phoneNumber,
                        [
                            'from' => $this->clinic->twilio_number,
                            'body' => $emailBody,
                        ]
                    );
                }else{
                    $twilio->messages->create(
                        $phoneNumber,
                        [
                            'from' => $this->clinic->twilio_number,
                            'body' => $message,
                        ]
                    );
                }

                CrmNote::create([
                    'note' => 'Appointment Reminder Text Sent',
                    'user_id' => 1,
                    'customer_id' => $this->customer->id,
                ]);

            } else {
                // Optionally, you can handle the case where $phoneNumber is empty
                // For example, log an error or take some other action
                Log::warning('No phone number provided for notification.');
            }
        } catch (\Exception $e) {
            Log::error('Text Sending Failed | ' . $e->getMessage());
        }
    }
}
