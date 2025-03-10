<?php

namespace App\Console\Commands;

use App\Jobs\AppointmentReminderEmailJob;
use App\Jobs\AppointmentReminderTextJob;
use App\Mail\AppointmentReminderEmail;
use App\Models\Appointment;
use App\Models\CrmCustomer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class AppointmentReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:appointment-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send emails reminding up-coming appointment.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $crm_customers = CrmCustomer::with('clinic', 'clinic.appointment_availability.appointment_reminders')
            ->whereHas('clinic', function($q){
                $q->where('status', 'Active');
            })
            ->has('clinic.appointment_availability.appointment_reminders', '>', 0 )
            ->where('status_id', 12)
            ->where('consultation_booked_date', '>', Carbon::now())
            ->get();

        foreach ($crm_customers as $crm_customer) {
            $appointment = Appointment::where('crm_customer_id', $crm_customer->id)->first();
            $template = new AppointmentReminderEmail(['lead' => $crm_customer, 'appointment' => $appointment, 'clinic' => $crm_customer->clinic]);
            $appointment_reminders = $crm_customer->clinic->appointment_availability->appointment_reminders;
            $timezone = $crm_customer->clinic->appointment_availability->timezone;
            foreach ($appointment_reminders as $appointment_reminder) {
                $interval = $appointment_reminder->interval;
                $timeDifference = Carbon::now($timezone)->diffInMinutes($crm_customer->consultation_booked_date);
                if($appointment_reminder->unit=='minutes'){
                    // No calculation required
                }else if($appointment_reminder->unit=='hours'){
                    $interval = $interval * 60;
                } else if($appointment_reminder->unit=='days'){
                    $interval = $interval * 60 * 24;
                } else{
                    $interval = $interval * 60 * 24 * 7;
                }

                if($timeDifference==$interval){
                    if($appointment_reminder->type == 'email'){
                        AppointmentReminderEmailJob::dispatch($crm_customer, $template, $crm_customer->clinic, $appointment);
                    }else{
                        AppointmentReminderTextJob::dispatch($crm_customer, $appointment, $crm_customer->clinic);
                    }
                }
            }

        }

        return 1;
    }
}
