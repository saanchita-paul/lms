<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CrmCustomer;
use App\Models\Clinic;
use App\Jobs\UpdatePatientInfo;
use Carbon\Carbon;

class nexhealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nexhealth:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get patient id and update lifetimevalue';

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
        $clinics      = Clinic::select('id','subdomain','location','nexhealthkey')->where('nexhealthselection',"1")->get();


        if($clinics->isNotEmpty())
        {

            $clinicData = [];
            foreach($clinics as $value){
                $clinicData['id'] = $value->id;
                $clinicData['subdomain'] = $value->subdomain;
                $clinicData['location'] = $value->location;
                $clinicData['nexhealthkey'] = $value->nexhealthkey;




                    $chunk_leads = null;
                    $chunk_leads = CrmCustomer::select('id','first_name','last_name','email','phone','phone_form','created_at','updated_at','status_id','clinic_id')->where('clinic_id',$clinicData['id'])->orderBy('id', 'desc')->chunk(100, function ($chunk_leads) use ($clinicData){
                    if ($chunk_leads) {



                       $headers = [];
                        $headers[] = 'Accept:application/vnd.Nexhealth+json;version=2';
                        $token = $clinicData['nexhealthkey'];
                        $headers[] = "Authorization: ".$token;



                        $authcurl = curl_init();
                        curl_setopt_array($authcurl, array(
                            CURLOPT_URL => "https://nexhealth.info/authenticates",
                            CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => "",
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 0,
                              CURLOPT_FOLLOWLOCATION => true,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => "POST",
                              CURLOPT_SSL_VERIFYHOST => 0,
                              CURLOPT_SSL_VERIFYPEER => 0,
                              CURLOPT_POSTFIELDS => array('spam' => 'true'),
                              CURLOPT_HTTPHEADER => $headers,
                        ));

                        $token = curl_exec($authcurl);



                        curl_close($authcurl);

                        $authdata = json_decode($token,true);

                        $token = null;
                        $token = $authdata['data']['token'];

                        $currentTime = Carbon::now();
                        foreach ($chunk_leads as $chunk_lead) {
                            $nexttTime = Carbon::now();
                            $minutes = round(abs(strtotime($currentTime->toTimeString()) - strtotime($nexttTime->toTimeString())) / 60,2);
                           if($minutes == 55 || $minutes == 115 || $minutes == 175 || $minutes == 235)
                            {
                                $outhcurl = curl_init();
                                curl_setopt_array($outhcurl, array(
                                    CURLOPT_URL => "https://nexhealth.info/authenticates",
                                    CURLOPT_RETURNTRANSFER => true,
                                      CURLOPT_ENCODING => "",
                                      CURLOPT_MAXREDIRS => 10,
                                      CURLOPT_TIMEOUT => 0,
                                      CURLOPT_FOLLOWLOCATION => true,
                                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                      CURLOPT_CUSTOMREQUEST => "POST",
                                      CURLOPT_SSL_VERIFYHOST => 0,
                                      CURLOPT_SSL_VERIFYPEER => 0,
                                      CURLOPT_POSTFIELDS => array('spam' => 'true'),
                                      CURLOPT_HTTPHEADER => $headers,
                                ));
                                $token = curl_exec($outhcurl);
                                curl_close($outhcurl);
                                $outhdata = json_decode($token,true);
                                $token = null;
                                $token = $outhdata['data']['token'];
                            }

                            $job = (new UpdatePatientInfo($chunk_lead,$token,$clinicData))->onQueue('nexpatient');
                            dispatch($job);
                        }
                    }
                });
            }
        }
        else
        {
            echo "Please set at least one clinic for nexhealth API";
        }
    }
}
