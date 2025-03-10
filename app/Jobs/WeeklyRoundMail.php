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

class WeeklyRoundMail implements ShouldQueue
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

    public function totalNewLeadsThisWeek($clinicId)
    {
        return CrmCustomer::where('clinic_id', $clinicId)
                        ->whereBetween('created_at', [now()->startOfWeek(), now()])
                        ->count();
    }

    public function totalAppointmentsScheduledThisWeek($clinicId)
    {
        return CrmCustomer::where('clinic_id', $clinicId)
                        ->where('convert_to_deal', "1")
                        ->whereBetween('convert_deal_date', [now()->startOfWeek(), now()])
                        ->count();
    }

    public function totalFollowUpsCompletedThisWeek($clinicId)
    {
        return CrmCustomer::where('clinic_id', $clinicId)
                        ->whereNull('won_lost')
                        ->whereNotNull('deal_status')
                        ->whereBetween('updated_at', [now()->startOfWeek(), now()])
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

            $totalNewLeadsThisWeek = $this->totalNewLeadsThisWeek($clinicId);
            $totalAppointmentsScheduledThisWeek = $this->totalAppointmentsScheduledThisWeek($clinicId);
            $totalFollowUpsThisWeek = $this->totalFollowUpsCompletedThisWeek($clinicId);

            $data = [
                'practiceName' => $this->clinic->clinic_name,
                'totalNewLeadsThisWeek' => $totalNewLeadsThisWeek,
                'totalAppointmentsScheduledThisWeek' => $totalAppointmentsScheduledThisWeek,
                'totalFollowUpsThisWeek' => $totalFollowUpsThisWeek
            ];
                
            
            $users = $this->clinic->managers()->whereHas('roles', function ($q) {
                $q->where('id', 5);
            })->whereHas('settings', function ($q) {
                $q->where('weekly_summary_email_notification', 1);
            })->get();

            if ($users->isEmpty()) {
                // Users not found with the specified criteria
                echo "No eligible users found for sending emails.";
                // Optionally, you may want to return or exit here depending on your application flow
                return;
            }
            
            // Populate $toList with user emails
            $toList = $users->pluck('email')->toArray();

            $bccEmail = env('BCC_EMAIL');
            $bccArray = $bccEmail ? explode(',', $bccEmail) : [];
            $subject = "Weekly Roundup: This Week’s Overview at {$this->clinic->clinic_name}";
            foreach ($toList as $key => $to) {
                Mail::send('emails.weeklyRoundup', $data, function ($message) use ($to, $bccArray, $subject) {
                    $message->to($to)
                            ->subject($subject)                            
                            ->from('noreply@microsite.com', 'Microsite-CRTX')
                            ->bcc($bccArray);   
                });
            }
            
        }

    }
}