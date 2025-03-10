<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jobs\WeeklyMail;

class WeeklyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailcampaign:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Respectively send an exclusive reports to everyone 7 days via email.';

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
        $clinics = Clinic::where('status','Active')->where('emailcampaign',"1")->get();  

         foreach ($clinics as $key => $value) {
                
                if($value->timezone == 'America/New_York'){
                    $on = now()->addMinutes(1);
                }elseif($value->timezone == 'America/Chicago'){
                    $on = now()->addMinutes(60);
                }elseif($value->timezone == 'America/Denver'){
                    $on = now()->addMinutes(120);
                }elseif($value->timezone == 'America/Los_Angeles'){
                    $on = now()->addMinutes(180);
                }else{
                    $on = now()->addMinutes(1);
                }
                            
                $job = (new WeeklyMail($value))->onQueue('weeklymailcampaign');
                        dispatch($job)->delay($on);
                           
        }
        
    }
}
