<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Helpers\Zoho\ZohoAnalyticsPHPClient\AnalyticsClient;
use App\Http\Controllers\Controller;
use App\Models\GlobalData;
use App\Models\ZohoAnalyticsCost;
use Carbon\Carbon;
use Dotenv\Store\File\Reader;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ZohoAnalyticsController extends Controller
{
    public $ac = NULL;
    public $client_id = null;
    public $client_secret = null;
    public $access_token = "";

    public $refresh_token = "";

    public $code = null;

    public $org_id = "20071995688";
    public $workspace_id = "98238000000076245";
    public $view_id_fb = "98238000000207134";

    public $view_id_gg = "98238000000207003";

    public $job_id = null;

    public $client = null;

    public $type = '';

    public function __construct()
    {
        $this->client_id = config('app.zoho_analytics_client_id');
        $this->client_secret = config('app.zoho_analytics_client_secret');
        $this->code = config('app.zoho_analytics_code');
    }

    public function generateAccessToken()
    {
        $this->client = new Client();
        $res = $this->client->post('https://accounts.zoho.eu/oauth/v2/token?code=' . $this->code . '&client_id=' . $this->client_id . '&client_secret=' . $this->client_secret . '&grant_type=authorization_code');

        if ($res->getStatusCode() == 200 && !json_decode($res->getBody())->error) {

            $token_exists = false;

            $globalDataSet = GlobalData::all();

            foreach ($globalDataSet as $globalData) {
                if ($globalData['key'] == "zoho_refresh_token") {
                    $token_exists = true;
                    $globalData['value'] = json_decode($res->getBody())->refresh_token;
                    $globalData->save();
                }
            }

            $this->access_token = json_decode($res->getBody())->access_token;

            if (!$token_exists) {
                GlobalData::create([
                    "key" => "zoho_refresh_token",
                    "value" => json_decode($res->getBody())->refresh_token
                ]);
            }

            echo 'Access Token Generated';
        }else{
            echo $res->getBody();
        }
    }

    public function regenerateAccessToken($type)
    {
        $this->type = $type;

        $globalDataSet = GlobalData::all();

        foreach ($globalDataSet as $globalData) {
            if ($globalData['key'] == "zoho_refresh_token") {
                $this->refresh_token = $globalData['value'];
            }
        }

        $this->ac = new AnalyticsClient($this->client_id, $this->client_secret, $this->refresh_token);

        $this->initiateBulkExport($this->type == 'facebook'? $this->view_id_fb : $this->view_id_gg);
    }

    function initiateBulkExport($view_id) {
        $response_format = "csv";
        $bulk = $this->ac->getBulkInstance($this->org_id, $this->workspace_id);
        $this->job_id = $bulk->initiateBulkExport($view_id, $response_format);

        $status=TRUE;

        do {

            $response = $this->getExportJobDetails();
            sleep(5);

            if($response['jobStatus'] == 'JOB COMPLETED'){
                $status=FALSE;

                $this->exportBulkData();
            }

        } while($status==TRUE);
    }


    function getExportJobDetails()
    {
        $bulk = $this->ac->getBulkInstance($this->org_id, $this->workspace_id);
        return $bulk->getExportJobDetails($this->job_id);
    }

    public function exportBulkData()
    {
        $file_path = $this->type."_costs.csv";
        $bulk = $this->ac->getBulkInstance($this->org_id, $this->workspace_id);
        try{
            $response = $bulk->exportBulkData($this->job_id, $file_path);

            $data = $this->csvToArray($this->type.'_costs.csv');

            if($this->type=='google')
                ZohoAnalyticsCost::truncate();

            foreach ($data as $row){
                 ZohoAnalyticsCost::create([
                    'date' => Carbon::createFromFormat('d M, Y H:i:s', $row['Date']),
                    'clinic_id' => $row['Clinic ID'],
                    'source' => $row['Source'],
                    'cost' => $row['Cost']
                ]);
            }

            File::delete($this->type.'_costs.csv');
        }catch (\App\Helpers\Zoho\ZohoAnalyticsPHPClient\ServerException $e){
            dd($e);
        }
        echo 'Export Job parsed and saved to database';
    }

    private function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}




