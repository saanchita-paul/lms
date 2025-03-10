<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use App\Models\Source;
use App\Models\User;
use App\Models\DataUpload;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\DB;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Illuminate\Support\Arr;

class DataUploadController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('dataupload_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userid = $request->user()->id;

        $isadmin =  $request->user()->getIsAdminAttribute();

        
        $user_id = request()->input('user_id', "");

         
        if(!$isadmin){
            $clinics = Clinic::whereHas('managers', function ($query ) use($userid) { 
                                                                     return   $query->where('user_id', '=', $userid );
                                                                    })->get();
        }else{
            $clinics      = Clinic::get();
        }        
        $users        = User::get();
        if($request->input('clinic_id')){
            $dataupload = $clinics->where('id',"=",$request->input('clinic_id'))->first();
        }else{
            $dataupload = $clinics->first();
        }
        
        $patients = array();
        $rowleaddata = array();
        $header = null;//echo $dataupload->uploadfile->getUrl();exit;
        if(isset($dataupload->uploadfile)){
            if($dataupload->uploadfile->getUrl() != ''){
                if (($open = fopen(public_path().$dataupload->uploadfile->getUrl() , "r")) !== FALSE) {

                    CrmCustomer::where('clinic_id', $request->input('clinic_id'))->update(['lifetimevalue' =>0 ]);

                    while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                        
                        if (!$header){
                            $header = $data;
                            // Remove any invalid or hidden characters
                            $header = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header);
                        }
                        else{

                            //$leaddata = $data;//$this->lookupleads(array_combine($header, $data), $request->input('clinic_id'));
                            $singledatawithheader = array_combine($header, $data);
                            $leaddata = $this->lookupleads(array_combine($header, $data), $request->input('clinic_id'));
                            if(!empty($leaddata)){
                                
                                    $key = array_search($leaddata['id'],$rowleaddata,true);

                                    if(in_array($leaddata['id'], $rowleaddata, true)){

                                       $leaddata['lifetimevalue'] = $leaddata['lifetimevalue'] + (int)preg_replace("/([^0-9\\.])/i", "", $singledatawithheader['Total Collection']); 

                                    }else{
                                       $leaddata['lifetimevalue'] = (int)preg_replace("/([^0-9\\.])/i", "", $singledatawithheader['Total Collection']);
                                    }
                                    CrmCustomer::where('id', $leaddata['id'])->update(['lifetimevalue' =>$leaddata['lifetimevalue'] ]);

                                    if($request->input('clinic_id') == 116 && isset($singledatawithheader['Lead Status'])){
                                        if($singledatawithheader['Lead Status'] == "Arrived"){
                                            if($singledatawithheader['Amount']==""){
                                              $singledatawithheader['Amount']=NULL;
                                            }

                                            if($singledatawithheader['Stage'] == "Closed Won (1st Payment Received)"){
                                                CrmCustomer::where('id', $leaddata['id'])->update(['status_id' => 15 , 'value' => $singledatawithheader['Amount'], 'won_lost' => "Won", 'won_lost_date'=> $leaddata['consultation_booked_date'] ]);
                                            }elseif($singledatawithheader['Stage'] == "Closed Lost"){
                                                CrmCustomer::where('id', $leaddata['id'])->update(['status_id' => 15 , 'value' => $singledatawithheader['Amount'], 'won_lost' => "Lost", 'won_lost_date'=> $leaddata['consultation_booked_date']]);
                                            }else{
                                                CrmCustomer::where('id', $leaddata['id'])->update(['status_id' => 15 , 'value' => $singledatawithheader['Amount'] ]);
                                            }
                                            
                                        }else{
                                            CrmCustomer::where('id', $leaddata['id'])->update(['status_id' => 13 , 'no_showed_date' => $leaddata['consultation_booked_date'] ]);
                                        }
                                    }

                                /*if ($key !== false) {
                                    echo $patients[$key]['lfvalue'] = $patients[$key]['lfvalue'] + $singledatawithheader['Total Collection'];exit;
                                    
                                }else{*/
                                    $rowleaddata[] = $leaddata['id'];
                                    
                                    $patients[] = $leaddata;
                                //}

                                
                                
                                
                            }
                            
                        }
                        
                    }

                    fclose($open);
                }
            }
        }
        //echo "<pre>";print_r($patients);exit;
        $patients = array_filter($patients);
        $patients = json_encode($patients, true);
        

//dd( $dataupload);exit;
        return view('admin.dataupload.index', compact('clinics', 'users', 'dataupload', 'patients'))->with('clinic_id', $request->input('clinic_id'));
    }

    function lookupleads($data, $clinic_id){
        $lead = array();
        $firstname = "LsuJs7HStnnSH";
        $lastname  = "HuambAnsUJStm";
        if(isset($data['Patient'])){
            $name = explode(", ",$data['Patient']);
            $lastname = $name[0];
            if(isset($name[1])){
                $firstname = $name[1];
            }
            
        }
        
        //$name = explode(" ",$data['Name']);
        $phone = preg_replace("/[^A-Za-z0-9]/", '', $data['Phone']);

        if($phone==""){
            $phone = 112233445566778899;
        }
        if($data['Email']==""){
            $data['Email'] = 112233445566778899;
        }

        

        
        $lead = CrmCustomer::select('id','first_name','last_name','email','phone','value','lifetimevalue','clinic_id','consultation_booked_date')->where(function ($query) use ($data, $clinic_id, $phone){
                $query->where('phone','LIKE','%' . $phone . '%')
                  ->orWhere('email','LIKE','%' . $data['Email'] . '%');
                })->where(function ($query) use ($clinic_id) {
                    $query->where('clinic_id',"=",$clinic_id);
                })->first();

       
        if(empty($lead)){
            if(isset($firstname) && isset($lastname)){
                $lead = CrmCustomer::select('id','first_name','last_name','email','phone','value','lifetimevalue','clinic_id','consultation_booked_date')->where('clinic_id',"=",$clinic_id)->where('first_name','LIKE','%' . $firstname . '%')->where('last_name','LIKE','%' . $lastname . '%')->first();
            }
        }

        

        //echo count($lead);
        /*if(isset($lead)){
            dd($lead);
        }*/
        
        return $lead;
    }

    public function store(Request $request)
    {  
        $dataupload = Clinic::where('id',"=",$request->input('clinic_id'))->first();
        //dd($dataupload);exit;
        if ($request->input('uploadfile', false)) {
            $dataupload->addMedia(storage_path('tmp/uploads/' . basename($request->input('uploadfile'))))->toMediaCollection('uploadfile');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $dataupload->id]);
        }

        return redirect()->route('admin.dataupload.index',['clinic_id'=> $request->input('clinic_id')]);
    }
}
