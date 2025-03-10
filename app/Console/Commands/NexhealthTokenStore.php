<?php

namespace App\Console\Commands;

use App\Models\GlobalData;
use Flyfinder\Specification\Glob;
use Illuminate\Console\Command;
use App\Models\Clinic;
use Illuminate\Support\Facades\Log;

class NexhealthTokenStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nexhealth-token-store:hour';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get nexhealth token store every hour';

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

            $headers = [];
            $headers[] = 'Accept:application/vnd.Nexhealth+json;version=2';
            $token = env('NEXHEALTH_KEY');
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
            if(isset($authdata['data']['token'])){
                $globalData = GlobalData::where(['key'=>'nexhealth_token'])->first();
                $globalData->value = $authdata['data']['token'];
                $globalData->save();
                $this->info('Token stored successfully!');
            }else{
                Log::info("Nexhealth: no token found");
            }

    }
}
