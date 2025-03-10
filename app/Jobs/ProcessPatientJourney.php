<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\SendEmail;
use App\Jobs\SendSms;
use App\Jobs\SendPatientEmail;
use App\Jobs\SendPatientSms;
use DB;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use Carbon\Carbon;

class ProcessPatientJourney implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clinic;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Clinic $clinic)
    {
         $this->clinic = $clinic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(!empty($this->clinic))
        {
            $chunk_leads = null;
    
            $clinicid = $this->clinic->id;

            $chunk_leads = CrmCustomer::select('id','consultation_booked_date')->where('clinic_id',$this->clinic->id)->where('patient_journey_automation',0)->where('status_id',12)->where('consultation_booked_date', '>', Carbon::now())->chunk(100, function ($chunk_leads) use ($clinicid){

                if ($chunk_leads) {
                    foreach ($chunk_leads as $chunk_lead) {
                        $job = (new SendPatientEmail($chunk_lead))->onQueue('patientjourneyemail');
                        dispatch($job);
                        $job = (new SendPatientSms($chunk_lead))->onQueue('patientjourneysms');
                        dispatch($job);
                        
                    }
                }
            });


        }

    }
}
