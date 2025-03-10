<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Models\Setting;

class DailyMail implements ShouldQueue
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

    public function totalNewLeadsToday($clinicid){            
        $query = CrmCustomer::with(['clinic', 'status']); 
        return $query->where('clinic_id', $clinicid)
                          ->whereDate('created_at', now()->toDateString())
                          ->count();
    }

    public function totalAppointmentsScheduledToday($clinicid){            
        $query = CrmCustomer::with(['clinic']); 
        return  $query->where('clinic_id',$clinicid)
        ->where('convert_to_deal',"1")
        ->whereDate('convert_deal_date', now()->toDateString())
        ->get()
        ->count();
    }    
    
    public function totalFollowUpsCompletedToday($clinicid)
        {
            $query = CrmCustomer::with(['clinic']); 
            return $query->where('clinic_id', $clinicid)
                        ->whereNull('won_lost')
                        ->whereNotNull('deal_status')
                        ->whereDate('updated_at', now()->toDateString()) 
                        ->count();
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
            $clinicId = $this->clinic->id;

            $totalNewLeadsToday = $this->totalNewLeadsToday($clinicId);
            
            $totalAppointmentsScheduledToday = $this->totalAppointmentsScheduledToday($clinicId);
            
            $totalFollowUpsCompletedToday = $this->totalFollowUpsCompletedToday($clinicId);                

            $data = [
                'practiceName' => $this->clinic->clinic_name,
                'totalNewLeadsToday' => $totalNewLeadsToday,
                'totalAppointmentsScheduledToday' => $totalAppointmentsScheduledToday,
                'totalFollowUpsCompletedToday' => $totalFollowUpsCompletedToday
            ];

            
            $users = $this->clinic->managers()->whereHas('roles', function ($q) {
                $q->where('id', 5);
            })->whereHas('settings', function ($q) {
                $q->where('daily_summary_email_notification', 1);
            })->get();

            if ($users->isEmpty()) {
                // Users not found with the specified criteria
                echo "No eligible users found for sending emails.";
                // Optionally, you may want to return or exit here depending on your application flow
                return;
            }
            
            // Populate $toList with user emails
            $toList = $users->pluck('email','id')->toArray();

            $bccEmail = env('BCC_EMAIL');
            $bccArray = $bccEmail ? explode(',', $bccEmail) : [];
            $subject = "Today's New Patient Highlights at {$this->clinic->clinic_name}";
            
            foreach ($toList as $to) {
                Mail::send('emails.dailyRoundup', $data, function ($message) use ($to, $bccArray, $subject) {
                    $message->to($to)
                            ->subject($subject)
                            ->from('noreply@microsite.com', 'Microsite-CRTX')
                            ->bcc($bccArray);
                });
            }
            


                      
        }
    }
}