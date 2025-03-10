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

class AutomationCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    protected $clinic;
    protected $statuskey;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Clinic $clinic,$statuskey)
    {
         $this->clinic = $clinic;
         $this->statuskey = $statuskey;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $statusId = $this->statuskey;

        

        if(!empty($this->clinic))
        {
           // $customerId = $customer->id;

           $chunk_leads = null;
    
            $clinicid = $this->clinic->id;

            
            $chunk_leads = CrmCustomer::with(['clinic'])
            ->where('clinic_id', $this->clinic->id)
            ->where('status_id', $statusId)
            ->where('created_at', '>', '2024-02-29')
            ->whereNull('won_lost') // Add condition to check for null won_lost field
            ->where(function ($query) use ($statusId) {
                $query->whereJsonContains('automation_rule', [$statusId => true])
                    ->orWhereNull('automation_rule');
            })
            ->chunk(100, function ($chunk_leads) use ($clinicid) {
                if ($chunk_leads) {
                    foreach ($chunk_leads as $chunk_lead) {
                        $job = (new CheckAutomationLogEmail($chunk_lead))->onQueue('automationlogemail');
                        dispatch($job);
                        $job = (new CheckAutomationLogSms($chunk_lead))->onQueue('automationlogsms');
                        dispatch($job);
                    }
                }
            });
        }
    }
    
}
