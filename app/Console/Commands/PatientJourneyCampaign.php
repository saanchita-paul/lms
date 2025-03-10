<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use Carbon\Carbon;
use App\Jobs\ProcessPatientJourney;
use Illuminate\Foundation\Bus\DispatchesJobs;

class PatientJourneyCampaign extends Command
{
    use DispatchesJobs;    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patientjourney:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Respectively send an patient journey email and text to leads.';

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
                
        $clinics      = Clinic::where('patient_journey_campaign',"Yes")->get();

        foreach ($clinics as $key => $value) {
                
                if($value->timezone == 'America/New_York'){
                    $on = now()->addMinutes(10);
                }elseif($value->timezone == 'America/Chicago'){
                    $on = now()->addMinutes(60);
                }elseif($value->timezone == 'America/Denver'){
                    $on = now()->addMinutes(120);
                }elseif($value->timezone == 'America/Los_Angeles'){
                    $on = now()->addMinutes(180);
                }else{
                    $on = now()->addMinutes(10);
                }
                     
                
                $job = (new ProcessPatientJourney($value))->onQueue('patientjourney');
                
                        dispatch($job)->delay($on);
                           
        }
        
    }
}
