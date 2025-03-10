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
use DB;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use Carbon\Carbon;

class ProcessClinics implements ShouldQueue
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
            $chunk_leads = CrmCustomer::where('clinic_id',$this->clinic->id)->where('automation',0)->where('status_id',17)->chunk(100, function ($chunk_leads) use ($clinicid){

                                                if ($chunk_leads) {

                                                    foreach ($chunk_leads as $chunk_lead) {
                                                       // echo "Hello:<pre>",print_r($chunk_lead);exit;
                                                        $job = (new SendEmail($chunk_lead))->onQueue('automationemail');
                                                        dispatch($job);
                                                        $job = (new SendSms($chunk_lead))->onQueue('automationsms');
                                                        dispatch($job);
                                                        
                                                    }
                                                }
                                            });


        }

    }
}
