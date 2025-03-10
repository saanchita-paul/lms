<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\AuditLog;

class WeeklyMail implements ShouldQueue
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

    public function checkauditlog($userid){  
        
        $userArray = explode(',', $userid);     
        return $checkauditlog = AuditLog::select('id','updated_at')->where('description','updated')->where('subject_type','App\\Models\\CrmCustomer')->whereIn('user_id', $userArray)->orderBy('updated_at','DESC')->take(1)->get();
           
    }

    public function practicefollowup($clinicid){            
        $query = CrmCustomer::with(['clinic']); 
        return $practicefollowup = $query->where('clinic_id',$clinicid)->whereNull('won_lost')->whereNotNull('deal_status')->get()->count();         
    }

    public function consultsbooked($clinicid){            
        $query = CrmCustomer::with(['clinic']);         
        return $consultsbooked = $query->where('clinic_id',$clinicid)->where('convert_to_deal',"1")->whereBetween('convert_deal_date', [now()->subDays(30),now()])->get()->count();
    }

    public function pendingacceptance($clinicid){            
        $query = CrmCustomer::with(['clinic', 'status']); 
        return $treatpresented = $query->where('clinic_id',$clinicid)->where('status_id',"15")->whereNull('won_lost')->get()->count();
    }

    public function totalpendingrevenue($clinicid){    
        $query = CrmCustomer::with(['clinic', 'status']);
        return $totalpendingrevenue = $query->where('clinic_id',$clinicid)->whereNull('won_lost')->get()->sum('value');        
    }

    public function treatsoldall($clinicid){            
        $query = CrmCustomer::with(['clinic', 'status']); 
        return $treatsoldall = $query->where('clinic_id',$clinicid)->where('won_lost',"Won")->whereBetween('won_lost_date', [now()->subdays(30),now()])->get()->sum('value');
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
    
            $clinicid = $this->clinic->id;

            $value = $this->clinic;
            
            $today = new Carbon();
            if($value->reportrecipients != null){

              
                $staffDetails = DB::table('clinic_user') 
                ->select('user_id,clinic_id')         
                ->join('clinics', 'clinics.id', '=', 'clinic_user.clinic_id')
                ->join('role_user', 'role_user.user_id', '=', 'clinic_user.user_id')
                ->select('clinic_user.user_id')
                ->where('clinic_id',$clinicid)
                ->where('role_user.role_id', 5)  
                ->groupBy('clinic_user.user_id')
                ->get(); 

                $combineData = array();
                foreach($staffDetails as $k => $v)
                {                  
                    $combineData[] = $v->user_id;                    
                }
                $staffIDs = implode(",",$combineData);               

                $checkauditlog = $this->checkauditlog($staffIDs); 
                if(!empty($checkauditlog[0])){
                    $toDate = Carbon::parse($checkauditlog[0]->updated_at);
                }else{ 
                    $toDate = Carbon::parse($today);
                }
                
                //$toDate = Carbon::parse('11-01-2023');
                $fromDate = Carbon::parse($today);
                

                $dayDiff = $toDate->diffInDays($fromDate);
                
                if($dayDiff >= 30)
                {
                    $days = 30;                    
                }
                elseif($dayDiff < 30 && $dayDiff >= 14)
                {
                    $days = 14;
                }
                elseif($dayDiff < 14 && $dayDiff >= 7)
                {
                    $days = 7;
                }else{
                    $days = 0;
                }
                                   
                $email_template = '';        
                
                if($days == '14' || $days == '7'){

                    $practicefollowup = $this->practicefollowup($value->id);

                    $consultsbooked = $this->consultsbooked($value->id);
                
                    $pendingacceptance = $this->pendingacceptance($value->id); 
                
                    $totalpendingrevenue     = number_format($this->totalpendingrevenue($value->id), 0, '.', ','); 
                
                    $treatsoldall  = number_format($this->treatsoldall($value->id), 0, '.', ','); 

                    $email_template .= "Hi $value->dr_name "."\r\n\r\n"."";

$email_template .='I noticed your team hasn’t updated your CRTX, your personal CRM sales-and-marketing solution, in the last '.$days.' days! To optimize your marketing and add top-line revenue, updating your CRM with your progress is critical.

I am available and would like to assist!

Please see below for the status of your new patient opportunities

Practice Follow up: '.$practicefollowup.'
Opportunities who want to learn more and remain engaged with your practice

Consultations Booked: '.$consultsbooked.'
Opportunities scheduled to visit your practice in the coming weeks

Pending Acceptance : '.$pendingacceptance.'
Patients who have received treatment plans and are deciding on the next steps or seeking financing.

Total Pending Revenue : $'.$totalpendingrevenue.'
Amount available for top-line revenue growth.

Treatment Sold : $'.$treatsoldall.'
Amount added to top-line revenue in past 30 days.

Please contact me if you have any questions or need support using CRTX, your personal CRM sales-and-marketing solution. My scheduling link is below.

https://calendly.com/lmoore-2020/30min';
}
                    if($days == '30')
                    {
                     $email_template .= "Hi $value->dr_name "."\r\n\r\n"."";

$email_template .='Unfortunately, over the past 30 days, your personal CRM sales-and-marketing solution has not been updated by anyone on your team. This software is critical for tracking your new patient opportunities and helps you close more treatments, leading to top-line revenue growth. 

As a result, you may be leaving money on the table. 

I am here to help and would like to re-engage your team.

Please contact me to schedule a time to review how we can use your CRM to help you increase revenue. My scheduling link is below.

https://calendly.com/lmoore-2020/30min'."\r\n".'';
                    }


                    $subjectline = '';
                     if($days == '14' || $days == '7'){
                         $subjectline = '[CRM Dashboard Updates NEEDED]- No Activity In the Last '.$days.' Days';
                    }
                    if($days == '30')
                    {
                        $subjectline = 'Did you hit your monthly revenue goals? Here’s why.';
                    }

                    if($days > 6){
                        $tolist = $value->reportrecipients;
                        $tolist = explode(",", $tolist);       
                        $bccarray = ['ashesh@microsite.com','lmoore@microsite.com'];
                        foreach($tolist as $key=>$to){
                            Mail::send(array(), array(), function ($message) use ($email_template,$value,$to,$subjectline,$bccarray) {
                              $message->to($to)
                                ->subject($subjectline)
                                ->from('lmoore@microsite.com', 'Lavonnie Moore')
                                ->bcc($bccarray)
                                ->replyTo('lmoore@microsite.com', 'Lavonnie Moore')
                                ->setBody($email_template, 'text/plain');
                            });
                        }                                     
                        echo 'Successfully sent daily report to everyone.';
                    }else{
                        echo 'Nothing to sent';
                    }
                    
            }     
        }
    }
}