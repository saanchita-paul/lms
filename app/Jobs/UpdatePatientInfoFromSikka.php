<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CrmCustomer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdatePatientInfoFromSikka implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $crmCustomer;
    protected $token;
    protected $clinicData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($crmCustomer,$token,$clinicData)
    {
        $this->crmCustomer = $crmCustomer;
        $this->token = $token;
        $this->clinicData = $clinicData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $firstName = '';
        $emailId = '';
        $phone = '';
        $patient_id = 0;
        $currentTime = Carbon::now();

       if(isset($this->crmCustomer['first_name']))
        {
            $firstName = $this->crmCustomer['first_name'];
        }
        if(isset($this->crmCustomer['email']))
        {
            $emailId = $this->crmCustomer['email'];
        }
        if(isset($this->crmCustomer['phone']))
        {
            $phone = preg_replace('/\+1/','',$this->crmCustomer['phone']);
        }

        $head = [];
        $head[] = 'content-Type:application/json';
        $token = $this->token;
        $head[] = "Request-Key:".$token;

        if ( (!empty($firstName)) && (!empty($phone)) && $phone != '1111111111')
        {
             $email = $emailId;

             // Add a 3-second delay
             sleep(3);
             //Email
             $authcurl = curl_init();
             curl_setopt_array($authcurl, array(
                 CURLOPT_URL => "https://api.sikkasoft.com/v4/patients?email=" . urlencode($email),
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => "",
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 0,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_SSL_VERIFYHOST => 0,
                 CURLOPT_SSL_VERIFYPEER => 0,
                 CURLOPT_POSTFIELDS => array(''),
                 CURLOPT_HTTPHEADER => $head,
             ));

             $token = curl_exec($authcurl);
             curl_close($authcurl);
             $responseData = json_decode($token,true);

             //Cell
             if(!isset($responseData['items'][0]['patient_id'])){

                 // Add a 3-second delay
                 sleep(3);

                 $authcurl = curl_init();
                 curl_setopt_array($authcurl, array(
                     CURLOPT_URL => "https://api.sikkasoft.com/v4/patients?cell=" . urlencode($phone),
                     CURLOPT_RETURNTRANSFER => true,
                     CURLOPT_ENCODING => "",
                     CURLOPT_MAXREDIRS => 10,
                     CURLOPT_TIMEOUT => 0,
                     CURLOPT_FOLLOWLOCATION => true,
                     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                     CURLOPT_CUSTOMREQUEST => "GET",
                     CURLOPT_SSL_VERIFYHOST => 0,
                     CURLOPT_SSL_VERIFYPEER => 0,
                     CURLOPT_POSTFIELDS => array(''),
                     CURLOPT_HTTPHEADER => $head,
                 ));

                 $token = curl_exec($authcurl);
                 curl_close($authcurl);
                 $responseData = json_decode($token,true);
             }

             //Phone
             if(!isset($responseData['items'][0]['patient_id'])){
                 // Add a 3-second delay
                 sleep(3);
                 $authcurl = curl_init();
                 curl_setopt_array($authcurl, array(
                     CURLOPT_URL => "https://api.sikkasoft.com/v4/patients?phone=" . urlencode($phone),
                     CURLOPT_RETURNTRANSFER => true,
                     CURLOPT_ENCODING => "",
                     CURLOPT_MAXREDIRS => 10,
                     CURLOPT_TIMEOUT => 0,
                     CURLOPT_FOLLOWLOCATION => true,
                     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                     CURLOPT_CUSTOMREQUEST => "GET",
                     CURLOPT_SSL_VERIFYHOST => 0,
                     CURLOPT_SSL_VERIFYPEER => 0,
                     CURLOPT_POSTFIELDS => array(''),
                     CURLOPT_HTTPHEADER => $head,
                 ));

                 $token = curl_exec($authcurl);
                 curl_close($authcurl);
                 $responseData = json_decode($token,true);
             }
        }

        $last_visit_date = '';
        if(!empty($responseData['items'][0]['patient_id'])){

            if(!empty($responseData['items'][0]['first_visit'])){
                $last_visit_date = $responseData['items'][0]['first_visit'];
            }

            $patient_id = $responseData['items'][0]['patient_id'];

            // Add a 3-second delay
            sleep(3);
            $authcurl = curl_init();
            curl_setopt_array($authcurl, array(
                CURLOPT_URL => "https://api.sikkasoft.com/v4/transactions?patient_id=" . urlencode($patient_id),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_POSTFIELDS => array(''),
                CURLOPT_HTTPHEADER => $head,
            ));

            $response = curl_exec($authcurl);
            curl_close($authcurl);
            $responseData = json_decode($response,true);

        }else{
            $message = "No Patiend Found";
            CrmCustomer::where('id', $this->crmCustomer['id'])->update([
                'lastFetchDate' => $currentTime->toDateTimeString()
            ]);
            echo response()->json(['status'=>false,'message'=>$message],422);
        }
        $transcation_date = '';
        if(!empty($responseData['items'])){
            $items = $responseData['items'];
            $amount_value = 0;
            foreach ($items as $item){
                if ($item['amount'] > 0) {
                    $amount_value += $item['amount'];
                    $transcation_date = $item['transaction_date'];
                }
            }
            if($amount_value > 0){
                CrmCustomer::where('id', $this->crmCustomer['id'])->update([
                    'patientID' => $patient_id,
                    'lastFetchDate' => $currentTime->toDateTimeString(),
                    'lifetimevalue' => $amount_value,
                    'won_lost' => 'Won',
                    'convert_to_deal' => 1,
                    'status_id' => 15,
                    'convert_deal_date' => $last_visit_date,
                    'consultation_booked_date' => $last_visit_date,
                    'won_lost_date' => $transcation_date
                ]);
            }else{
                CrmCustomer::where('id', $this->crmCustomer['id'])->update([
                    'patientID' => $patient_id,
                    'lastFetchDate' => $currentTime->toDateTimeString(),
                    'lifetimevalue' => $amount_value,
                ]);
            }


            $message = "Patient updated successfully";
            echo response()->json(['status'=>true,'message'=>$message],200);
        }else{
            $message = "No Data Found";
            echo response()->json(['status'=>false,'message'=>$message],422);
        }
    }
}
