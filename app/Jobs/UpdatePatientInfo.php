<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CrmCustomer;
use App\Models\Clinic;
use Carbon\Carbon;

class UpdatePatientInfo implements ShouldQueue
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
        $head[] = 'Accept:application/vnd.Nexhealth+json;version=2';
        $head[] = 'content-Type:application/json';
        $token = $this->token;
        $head[] = "Authorization:Bearer  ".$token;

        $curl = curl_init();
         if ( (!empty($firstName)) && (!empty($phone)) && $firstName !== 'XXXXX' && $phone != '1111111111')
         {
            $options = array(
                CURLOPT_URL => "https://nexhealth.info/patients?subdomain=".$this->clinicData['subdomain']."&location_id=".$this->clinicData['location']."&name=".$firstName."&phone_number=".$phone."&page=1&per_page=50",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => $head
            );
        }

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        $responseData = json_decode($response,true);

       if(empty($responseData['count']))
        {
             $ch = curl_init();
            if ((!empty($emailId)) && (!empty($phone)))
            {
                if (stristr($emailId, "@noreply.com") === false) {
                    $message = "No Patiend Found";
                    echo response()->json(['status'=>false,'message'=>$message],422);
                }
                if (stristr($emailId, "@noemail.com") === false) {
                    $message = "No Patiend Found";
                    echo response()->json(['status'=>false,'message'=>$message],422);
                }
                $options = array(
                    CURLOPT_URL => "https://nexhealth.info/patients?subdomain=".$this->clinicData['subdomain']."&location_id=".$this->clinicData['location']."&email=".$emailId."&phone_number=".$phone."&page=1&per_page=50",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => $head
                );
            }
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            $responseData = json_decode($response,true);


        }

        $err = curl_error($curl);
        $patiendID = '';
        $currentTime = Carbon::now();
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {

            if(!empty($responseData['count'] > 0))
            {
                $patiendID = $responseData['data']['patients'][0]['id'];

                  $ch = curl_init();
                  curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://nexhealth.info/procedures?subdomain=".$this->clinicData['subdomain']."&location_id=".$this->clinicData['location']."&patient_id=".$patiendID."&page=1&per_page=50",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => $head
                ));

                $res = curl_exec($ch);
                $resdd = json_decode($res,true);

                if(!empty($resdd['data']))
                {

                $sum = 0;
                foreach ($resdd['data'] as $reskey => $resdata)
                {
                    if($resdata['status'] == 'completed')
                    {
                       $sum+= $resdata['fee']['amount'];
                    }
                }
                    CrmCustomer::where('id', $this->crmCustomer['id'])->update([
                        'patientID' => $patiendID,
                        'lastFetchDate' => $currentTime->toDateTimeString(),
                        'lifetimevalue' => $sum
                    ]);

                }
                else
                {
                    echo "No Patient Found";
                }
                $message = "Patient updated successfully";
                echo response()->json(['status'=>true,'message'=>$message],200);
            }
            else
            {
                $message = "No Patiend Found";

                CrmCustomer::where('id', $this->crmCustomer['id'])->update([
                        'lastFetchDate' => $currentTime->toDateTimeString()
                    ]);
                echo response()->json(['status'=>false,'message'=>$message],422);
            }

        }

    }
}
