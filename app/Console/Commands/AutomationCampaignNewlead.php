<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use App\Models\Automationsequence;
use App\Models\ManageTemplate;
use Carbon\Carbon;
use App\Jobs\AutomationCampaign;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AutomationCampaignNewlead extends Command
{
    use DispatchesJobs;    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automation:schedule {status_id}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Respectively send  email and text to new leads.';

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
        $statusId = $this->argument('status_id');
        $Ids = explode(',', env('STATUS_IDS'));
        $status = CrmStatus::withTrashed()
                ->whereIn('id', $Ids)
                ->find($statusId);


        if (!$status) {
            return $this->error('Status not found.');
        }

        $statuskey = $statusId;
        
        $clinics = Clinic::whereJsonContains('automation_campaign', [$statuskey => 'true'])
        ->where('status', 'Active')
        ->where('version', '2.0')
        ->get();
        
        foreach ($clinics as $key => $clinic) {
                
                if($clinic->timezone == 'America/New_York'){
                    $on = now()->addMinutes(10);
                }elseif($clinic->timezone == 'America/Chicago'){
                    $on = now()->addMinutes(60);
                }elseif($clinic->timezone == 'America/Denver'){
                    $on = now()->addMinutes(120);
                }elseif($clinic->timezone == 'America/Los_Angeles'){
                    $on = now()->addMinutes(180);
                }else{
                    $on = now()->addMinutes(10);
                }

                $job = (new AutomationCampaign($clinic,$statuskey))->onQueue('automationcampaign');
                dispatch($job); //

                        
        }

        
        
    }
}
