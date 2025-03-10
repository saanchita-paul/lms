<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CrmCustomer;
use App\Models\Clinic;
use App\Jobs\UpdatePatientInfoFromSikka;

class sikkasoft extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sikkasoft:daily';

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
        $clinics = Clinic::select(
            'id',
            'sikka_soft_app_id', 'sikka_soft_app_key','sikka_soft_office_id','sikka_soft_secret_key')
            ->where('sikka_soft_app_id',"!=",NULL)
            ->where('sikka_soft_app_id',"!=","")->get();

        if($clinics->isNotEmpty())
        {
            $clinicData = [];
            foreach($clinics as $value){
                $clinicData['id'] = $value->id;
                $clinicData['sikka_soft_app_id'] = $value->sikka_soft_app_id;
                $clinicData['sikka_soft_app_key'] = $value->sikka_soft_app_key;
                $clinicData['sikka_soft_office_id'] = $value->sikka_soft_office_id;
                $clinicData['sikka_soft_secret_key'] = $value->sikka_soft_secret_key;

                $headers = [];
                $headers[] = 'Accept:application/json';
                $headers[] = "token_type: Bearer";

                // Add a 3-second delay
                sleep(3);
                $authcurl = curl_init();
                curl_setopt_array($authcurl, array(
                    CURLOPT_URL => "https://api.sikkasoft.com/v2/start?app_id=".$clinicData['sikka_soft_app_id']."&app_key=".$clinicData['sikka_soft_app_key']."&office_id=".$clinicData['sikka_soft_office_id']."&secret_key=".$clinicData['sikka_soft_secret_key'],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_POSTFIELDS => array('spam' => 'true'),
                    CURLOPT_HTTPHEADER => $headers,
                ));

                $token = curl_exec($authcurl);
                curl_close($authcurl);
                $authdata = json_decode($token,true);
                $token = $authdata[0]['request_key'] ?? null;
//                    $token='956eebe51dc44500b74841c7865d18eb';

                $chunk_leads = null;
                $chunk_leads = CrmCustomer::select('id','first_name','last_name','email','phone','phone_form','created_at','updated_at','status_id','clinic_id')
                    ->where('clinic_id',$clinicData['id'])
                    ->orderBy('id', 'desc')
                    ->chunk(100, function ($chunk_leads) use ($clinicData, $token){
                        if ($chunk_leads) {
                            foreach ($chunk_leads as $chunk_lead) {
                                $job = (new UpdatePatientInfoFromSikka($chunk_lead, $token, $clinicData))->onQueue('nexpatient')->delay(now()->addSeconds(3));
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
