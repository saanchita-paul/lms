<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCrmCustomerRequest;
use App\Http\Requests\StoreCrmCustomerRequest;
use App\Http\Requests\UpdateCrmCustomerRequest;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use App\Models\Source;
use App\Models\User;
use App\Models\CrmChat;
use App\Models\CrmNote;
use App\Models\Callrail;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Session;
use Mail;
use PDF;


class CrmCustomerController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('crm_customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userid = $request->user()->id;

        $isadmin =  $request->user()->getIsAdminAttribute();

        $user = $request->user()->roles;
        $userrole = $user[0]['title'];

        $allowedClinics = Clinic::select('clinic_id')->leftJoin('clinic_user', 'clinics.id', '=', 'clinic_user.clinic_id')->where('user_id', $userid)->get();
 
        $selectedclinicValue = array();

        foreach($allowedClinics as $key => $value){
            $selectedclinicValue[] = $value['clinic_id'];
        }


        

        if ($request->ajax()) {
            
            if(!$isadmin){
                if($userrole == 'Manager'){
                    $query = CrmCustomer::with(['source', 'status'])->whereIn('crm_customers.clinic_id',$selectedclinicValue)->select(sprintf('%s.*', (new CrmCustomer())->table));   
                }else{
                $query = CrmCustomer::with(['clinic', 'source', 'status', 'assignees'])->whereHas('clinic.managers', function ($query ) use($userid) { 
                        return   $query->where('user_id', '=', $userid );
                    })->select(sprintf('%s.*', (new CrmCustomer())->table)); 
                                    } 
            }else{
                if(request()->input('view_deleted') == "DeletedRecords"){
                    $query = CrmCustomer::with(['clinic', 'source', 'status', 'assignees'])->select(sprintf('%s.*', (new CrmCustomer())->table))->onlyTrashed();
                }else{
                    $query = CrmCustomer::with(['clinic', 'source', 'status', 'assignees'])->select(sprintf('%s.*', (new CrmCustomer())->table));    
                }
                
            }

            if(request()->input('show')=="followup"){
                $query = $query->whereNull('won_lost')->whereNotNull('deal_status');
            }    
            $selectedclinic = array();

            if(session('selectedclinic')){
                $selectedclinic = session('selectedclinic');
                $query = $query->whereIn('clinic_id',$selectedclinic);
            }
                
            $table = Datatables::eloquent($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'crm_customer_show';
                $editGate = 'crm_customer_edit';
                $deleteGate = 'crm_customer_delete';
                $crudRoutePart = 'crm-customers';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('clinic_clinic_name', function ($row) {
                return $row->clinic ? $row->clinic->clinic_name : '';
            });

            $table->editColumn('first_name', function ($row) {
                return $row->first_name ? $row->first_name : '';
            });
            $table->editColumn('last_name', function ($row) {
                return $row->last_name ? $row->last_name : '';
            });
            $table->editColumn('phone_form', function ($row) {
                return $row->phone_form ? CrmCustomer::PHONE_FORM_SELECT[$row->phone_form] : '';
            });
            $table->addColumn('source_source_name', function ($row) {
                return $row->source ? $row->source->source_name : '';
            });

            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });

            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('phone', function ($row) {
                return $row->phone ? $row->phone : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'clinic', 'source', 'status']);

            return $table->make(true);
        }

         
        if(!$isadmin){
            $clinics = Clinic::whereHas('managers', function ($query ) use($userid) { 
                                                                     return   $query->where('user_id', '=', $userid );
                                                                    })->orderBy('clinic_name', 'ASC')->get();
        }else{
            $clinics      = Clinic::orderBy('clinic_name', 'ASC')->get();
        }


        $sources      = Source::get();
        $crm_statuses = CrmStatus::get();

        $user = auth()->user()->roles;
        $userrole = $user[0]['title']; 
        if($userrole == 'Lead Center Associate'){
            $board_statuses = CrmStatus::where('board','=','lead')->where('name','!=','Practice Follow-Up')->orderByRaw('FIELD(id, "1","5","2","3","4","17")')->get();
        }else{
            if($clinics->contains('nurture_automation', "Yes")){
                $board_statuses = CrmStatus::where('board','=','lead')->orderByRaw('FIELD(id, "1","5","2","3","4","17","6")')->get();
            }else{
                $board_statuses = CrmStatus::where('board','=','lead')->where('id','!=','17')->orderByRaw('FIELD(id, "1","5","2","3","4","6")')->get();
            }
            
        }

        if(request()->input('view')=="consults"){
            $board_statuses = CrmStatus::where('board','=','consult')->get();
        }
        
        $users        = User::get();

        $selectedclinic = array();
        if(session('selectedclinic')){
            $selectedclinic = session('selectedclinic');
        }
        
        

        return view('admin.crmCustomers.index', compact('clinics', 'sources', 'crm_statuses', 'board_statuses','users','selectedclinic'));
    }

    public function updatedlist(Request $request)
    {
        abort_if(Gate::denies('crm_customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');   
        $user = auth()->user()->roles;
        $userrole = $user[0]['title']; 
        $crm_statuses = CrmStatus::get();
        $isadmin =  $request->user()->getIsAdminAttribute();
        $userid = $request->user()->id;
        if(!$isadmin){
            $clinics = Clinic::whereHas('managers', function ($query ) use($userid) { 
                                                                     return   $query->where('user_id', '=', $userid );
                                                                    })->orderBy('clinic_name', 'ASC')->get();
        }else{
            $clinics      = Clinic::orderBy('clinic_name', 'ASC')->get();
        }

        if($userrole == 'Lead Center Associate'){
            $board_statuses = CrmStatus::where('board','=','lead')->where('name','!=','Practice Follow-Up')->orderByRaw('FIELD(id, "1","5","2","3","4","17")')->get();
        }else{
            if($clinics->contains('nurture_automation', "Yes")){
                $board_statuses = CrmStatus::where('board','=','lead')->orderByRaw('FIELD(id, "1","5","2","3","4","17","6")')->get();
            }else{
                $board_statuses = CrmStatus::where('board','=','lead')->where('id','!=','17')->orderByRaw('FIELD(id, "1","5","2","3","4","6")')->get();
            }
        }

        $bordercolor = "blue";
        if(request()->input('view')=="consults"){
            $board_statuses = CrmStatus::where('board','=','consult')->get();
            $bordercolor = "blue";
        }
        $kanban_board_data = array();
        //$kanban_board_data = '';
        foreach($board_statuses as $key => $item) {
             
            $user = auth()->user();
            $userid = $user->id;
            $isadmin =  $user->getIsAdminAttribute();

            if(!$isadmin && $userrole != 'Manager'){
                if($userrole == 'Lead Center Associate' ){
                    if(request()->input('view')=="consults"){
                        $leads = CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('won_lost', NULL)->orderByRaw("(case when consultation_booked_date <= now() - interval 7 day then 1 else 0 end),  
             (case when consultation_booked_date <= now() - interval 7 day then consultation_booked_date end) desc,  
             consultation_booked_date asc  ")->whereHas('clinic.managers', function ($query ) use($userid) { 
                            return   $query->where('user_id', '=', $userid );
                        })->get();
                    }else{
                       if($item->id != 1){
                            $selectedclinic = array();
                            if(session('selectedclinic')){
                                $leads = CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('created_at', '>', now()->subDays(90)->endOfDay())->where('won_lost', NULL)->orderBy('id', 'desc')->whereHas('clinic.managers', function ($query ) use($userid) { 
                                        return   $query->where('user_id', '=', $userid );
                                    })->get();
                                $selectedclinic = session('selectedclinic');
                                $leads = $leads->whereIn('clinic_id',$selectedclinic);
                            }else{
                                $leads = array();
                            }
                       }else{
                            $leads = CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('created_at', '>', now()->subDays(90)->endOfDay())->where('won_lost', NULL)->orderBy('id', 'desc')->whereHas('clinic.managers', function ($query ) use($userid) { 
                            return   $query->where('user_id', '=', $userid );
                            })->get();
                       } 
                        
                    }
                }else{
                    if(request()->input('view')=="consults"){
                        $leads = CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('won_lost', NULL)->orderByRaw("(case when consultation_booked_date <= now() - interval 7 day then 1 else 0 end),  
             (case when consultation_booked_date <= now() - interval 7 day then consultation_booked_date end) desc,  
             consultation_booked_date asc  ")->whereHas('clinic.managers', function ($query ) use($userid) { 
                            return   $query->where('user_id', '=', $userid );
                        })->get();
                    }else{
                       $leads = CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('created_at', '>', now()->subDays(90)->endOfDay())->where('won_lost', NULL)->orderBy('id', 'desc')->whereHas('clinic.managers', function ($query ) use($userid) { 
                            return   $query->where('user_id', '=', $userid );
                        })->get(); 
                    }
                }
            
                $selectedclinic = array();
                if(session('selectedclinic')){
                    $selectedclinic = session('selectedclinic');
                    $leads = $leads->whereIn('clinic_id',$selectedclinic);
                }    
             
            }else{
                $selectedclinic = array();
                if(session('selectedclinic')){
                    $selectedclinic = session('selectedclinic');
                    if(request()->input('view')=="consults"){
                        $leads = CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('won_lost', NULL)->orderByRaw("(case when consultation_booked_date <= now() - interval 7 day then 1 else 0 end),  
         (case when consultation_booked_date <= now() - interval 7 day then consultation_booked_date end) desc,  
         consultation_booked_date asc  ")->get();
                    }else{
                        $leads = CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('created_at', '>', now()->subDays(90)->endOfDay())->where('won_lost', NULL)->orderBy('updated_at', 'desc')->get();
                    }    
                    
                    $leads = $leads->whereIn('clinic_id',$selectedclinic);
                }else{
                    $leads = array();
                }
                
            }

            

            //$kanban_board_data ='{id: "'.$item->id.'",title: "'.$item->name.'",headerBg: "green",item: []';
            
                        $leadsarray =  array();   
                        foreach ($leads as $lkey => $data)
                          {
                            $phone = '';
                            
                            if(($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager') && $data->ccapture == 1 ){
                                $phone .= "<span class='right'><i class='material-icons tiny icon-demo'>credit_card</i></span>";
                            }  

                            if($data->three_plus_attempts){
                                $phone .= "<span class='right task-cat teal accent-4'>".$data->three_plus_attempts."</span>";
                            }
                            if($data->badge){
                                $phone .= "<span class='badge gradient-45deg-deep-orange-orange gradient-shadow mt-2 mr-2'>".str_replace('"', '', trim($data->badge, '"'))."</span>";
                            }

                            if($data->phone){
                                $phone .= "<div><i class='material-icons tiny icon-demo'>phone_iphone</i>";
                                if(  preg_match( '/^\+\d(\d{3})(\d{3})(\d{4})$/', $data->phone,  $matches ) )
                                {
                                      $result = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
                                      $phone .= $result;
                                } 
                                $phone .= "</div>";
                            }

                              
                            $comment = "<div style='float:left;padding-right:5px;'>";  
                              if($data->phone_form == 'Phone Call'){
                                    $comment .= "<div class='timeline-badge light-blue'><i class='material-icons small white-text'>call</i></div>";
                                }else{
                                    $comment .= "<div class='timeline-badge light-blue'><i class='material-icons small white-text'>laptop_mac</i></div>";
                                }
                            $comment .= "</div>";

                            $attachment = '';
                            if($data->source){
                               $attachment = " ".$data->source->source_name;
                            }
                            
                            $date = '';
                            $date .=  "<div class='item-createddate tooltip'>".$data->created_at->format('m/d/Y')."<span class='tooltiptext'>Created On</span></div>"; 
                            if($data->value > 0){
                                    $date .= "<div class='item-value'>$".number_format($data->value)."</div>";
                            } 

                            if(request()->input('view')=="consults"){
                                if($data->convert_to_deal){
                                         // $date .= "<br> Consultation Booked At: ".$data->consultation_booked_date;
                                          $date .= "Consult Booked: ". (new \Carbon\Carbon($data->consultation_booked_date))->format('m/d/Y g:i A')." ";
                                }
                            }
                            else{
                                $date .= "Last Activity: ".$data->updated_at->format('m/d/Y');
                            }
                            $has_sms = '';
                            if($data->has_sms == 1){
                                $has_sms = "has_sms";
                            } 

                            $badgecolor = '';
                            if($data->deal_status  != ""){
                                $badgecolor = $data->deal_status;
                            }   
                            $leadsarray [] = (object) array('id'=> $item->id.'_'.$data->id, 'class'=> $has_sms, 'title'=> str_replace('"', '', trim($data->first_name, '"'))." ".str_replace('"', '', trim($data->last_name, '"')). " ".$phone, 'border'=> $bordercolor, 'dueDate'=> $date, 'comment'=> $comment, 'attachment'=>$attachment, 'badgeContent'=> $data->clinic->clinic_name."<br>".$data->clinic->dr_name, 'badgeColor' => $badgecolor);

                            
                             
                           } 

                $kanban_board_data []= (object) array('id' => "$item->id", 'title' => $item->name." (".count($leads).")",'headerBg' => 'green', 'item' => $leadsarray);           
               

        }      
            
       

        return $kanban_board_data;

    }

    public function moveStage(Request $request)
    {
        abort_if(Gate::denies('crm_customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');  
        $status_id = $request->input('status_id');
        $leadid = $request->input('leadid'); 
        $value = $request->input('leadvalue');
        $won_lost = $request->input('won_lost');
        $deal_status = $request->input('deal_status');
        $reason = $request->input('reason');
        $verbal_confirmation = $request->input('verbal_confirmation');
        $informed_consult_fee = $request->input('informed_consult_fee');
        $budget = $request->input('budget');
        $description = $request->input('description');
        $id = explode("_",$leadid);
        $consultation_booked_date = Null;
        $datetime = Null;
        $convert_to_deal = 0;
        

        $crmCustomer = CrmCustomer::find($id['1']);
        $current_consulatation_booked_date =   $crmCustomer->consultation_booked_date;
        $current_status = $crmCustomer->status_id;

        $userid = $crmCustomer->user;
        if(empty($crmCustomer->user))
        {
            $userid = Auth()->user()->id;
        }        

        if($crmCustomer) {
            if($status_id != NUll){
                $crmCustomer->status_id = $status_id;
                if($status_id == 12){
                    $datetime = Carbon::now();
                    $datetime = date('m/d/Y H:i:s'); 
                    $convert_to_deal = 1;
                    $crmCustomer->convert_deal_date = $datetime;
                    $crmCustomer->convert_to_deal = $convert_to_deal;
                    $crmCustomer->user = $userid;
                }
            }
            if($request->input('consultation_booked_date')){
                $mysqlTimeMeeting = Carbon::createFromFormat('m/d/Y h:ia', $request->input('consultation_booked_date'));
                //echo "AAA ".$mysqlTimeMeeting->format('m/d/Y H:i:s'). " BBB:". $current_consulatation_booked_date;exit;
                if($request->input('consultation_booked_date') != '' && ($mysqlTimeMeeting->format('m/d/Y H:i:s') != $current_consulatation_booked_date)){
                    $status_id = 12;
                    
                    $consultation_booked_date = $mysqlTimeMeeting->format('m/d/Y H:i:s');

                    $datetime = Carbon::now();
                    $datetime = date('m/d/Y H:i:s'); 
                    $convert_to_deal = 1;
                    $crmCustomer->status_id = $status_id;
                    $crmCustomer->consultation_booked_date = $consultation_booked_date;
                    $crmCustomer->convert_deal_date = $datetime;
                    $crmCustomer->convert_to_deal = $convert_to_deal;
                    $crmCustomer->user = $userid;
                }
            }
            if($request->input('status_id') == 13 && $current_status != 13){
                $datetime = Carbon::now();
                $datetime = date('m/d/Y H:i:s');
                $crmCustomer->no_showed_date  = $datetime;
            }

            if($value != NULL){
                $crmCustomer->value= str_replace(",", "", $value);
            }else{
                $crmCustomer->value = NULL;
            }
            if($won_lost != NULL){
                $crmCustomer->won_lost = $won_lost;
                $datetime = Carbon::now();
                $datetime = date('m/d/Y H:i:s');
                $crmCustomer->won_lost_date = $datetime;
            }else{
                $crmCustomer->won_lost = NULL;
            }    
            if($deal_status != NULL){
                $crmCustomer->deal_status = $deal_status;
            }

            if($verbal_confirmation != NULL){
                $crmCustomer->verbal_confirmation = $verbal_confirmation;
            }
            if($informed_consult_fee != NULL){
                $crmCustomer->informed_consult_fee = $informed_consult_fee;
            }
            if($budget != NULL){
                $crmCustomer->budget = $budget;
            }
            if($description != NULL){
                $crmCustomer->description = $description;
            }

            if($reason != '' && $status_id == 9){
                $crmCustomer->reason = $reason;
            }
            if($reason == '' && $status_id == 9){
                return "fail";
            }

            $crmCustomer->save();
            return "success";
        }else{
            return "fail";
        }
        
    }

    public function create()
    {
        abort_if(Gate::denies('crm_customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userid = Auth()->user()->id;
        $isadmin =  Auth()->user()->getIsAdminAttribute();
        if(!$isadmin){
            $clinics =  Clinic::whereHas('managers', function ($query ) use($userid) { 
                                                                     return   $query->where('user_id', '=', $userid );
                                                                    })->pluck('clinic_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        }else{
            $clinics = Clinic::all()->pluck('clinic_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        }


        $sources = Source::all()->pluck('source_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $statuses = CrmStatus::where('id','!=','17')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assignees = User::all()->pluck('name', 'id');

        return view('admin.crmCustomers.create', compact('clinics', 'sources', 'statuses', 'assignees'));
    }

    public function store(StoreCrmCustomerRequest $request)
    {
        if($request->input('phone') != ''){
            $phone = preg_replace("/[^A-Za-z0-9]/", '', $request->input('phone'));
            if(strlen($phone) == 10){
                $phone = "+1".$phone;
            }
            if(strlen($phone) == 11){
                $phone = "+".$phone;
            }
            $request['phone']  = $phone;
        }
        $crmCustomer = CrmCustomer::create($request->all());
        $crmCustomer->assignees()->sync($request->input('assignees', []));
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $crmCustomer->id]);
        }

        return redirect()->route('admin.crm-customers.index');
    }

    public function edit(CrmCustomer $crmCustomer)
    {
        abort_if(Gate::denies('crm_customer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userid = Auth()->user()->id;
        $isadmin =  Auth()->user()->getIsAdminAttribute();
        $clinicData = array();
        if(!$isadmin){
            $crmCustomer = $crmCustomer->load('clinic', 'source', 'status', 'assignees');
            $clinic = $crmCustomer->clinic;

            $hasclinicaccess = $clinic->whereHas('managers', function ($query ) use($userid) { 
                                                                     return   $query->where('user_id', '=', $userid );
                                                                    })->where('id', '=', $clinic->id)->first();

            if(!$hasclinicaccess){
                return redirect()->route('admin.crm-customers.index');
            }

            $clinics =  Clinic::whereHas('managers', function ($query ) use($userid) { 
                                                                     return   $query->where('user_id', '=', $userid );
                                                                    })->pluck('clinic_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        }else{
            $clinics = Clinic::all()->pluck('clinic_name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $crmCustomer->load('clinic', 'source', 'status', 'assignees');
            $clinicData = $crmCustomer->clinic->toArray();
        }

        $sources = Source::all()->pluck('source_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        if( $crmCustomer->convert_to_deal){
           $statuses = CrmStatus::where('board','!=',"Lead")->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        }else{
           if($crmCustomer->clinic->nurture_automation == 'Yes'){
                $statuses = CrmStatus::where('board','!=',"Consult")->orWhere('board', '=', NULL)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
           }else{
                $statuses = CrmStatus::where('id','!=','17')->where('board','!=',"Consult")->orWhere('board', '=', NULL)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
           }
           
       }
        
        $assignees = User::all()->pluck('name', 'id');

        $chats = CrmChat::where('lead_id','=',$crmCustomer->id)->orderBy('created_at', 'DESC')->get();

        $notes = CrmNote::select("crm_notes.id","crm_notes.note","crm_notes.created_at","users.name")->leftJoin("users", "users.id", "=", "crm_notes.user_id")->where('crm_notes.customer_id','=',$crmCustomer->id)->orderBy('crm_notes.created_at', 'DESC')->get();
       
        return view('admin.crmCustomers.edit', compact('clinics', 'sources', 'statuses', 'assignees', 'crmCustomer', 'chats', 'notes', 'clinicData'));
        
        
    }

    public function update(UpdateCrmCustomerRequest $request, CrmCustomer $crmCustomer)
    {
        $current_status =  $crmCustomer->status->id;
        $current_convert_to_deal =  $crmCustomer->convert_to_deal;
        $current_won_lost  =  $crmCustomer->won_lost;
        $current_consulatation_booked_date = $crmCustomer->consultation_booked_date;
        $current_won_lost_date = $crmCustomer->won_lost_date;

        $datetime = Carbon::now();
        $datetime = date('m/d/Y H:i:s'); 
        $view = 'kanban';
        $userid = $crmCustomer->user;
        if(empty($crmCustomer->user))
        {
            $userid = Auth()->user()->id;
        }

        if($request->input('status_id') == 13 && $current_status != 13){
               $view = 'consults';
               $request['no_showed_date']  = $datetime;
        }
        if($request->input('consultation_booked_date') != '' ){
            $view = 'consults';
            $mysqlTimeMeeting = Carbon::createFromFormat('m/d/Y h:ia', $request->input('consultation_booked_date'));
            //echo "time: " . $mysqlTimeMeeting->format('m/d/Y H:i:s') . "\n";
            //echo "AAA ".$mysqlTimeMeeting->format('m/d/Y H:i:s'). " BBB:". $current_consulatation_booked_date;exit;
            if($request->input('consultation_booked_date') != '' && ($mysqlTimeMeeting->format('m/d/Y H:i:s') != $current_consulatation_booked_date)){
                
                //echo "time: " . $mysqlTimeMeeting->format('m/d/Y H:i:s') . "\n";
                $request['consultation_booked_date'] = $mysqlTimeMeeting->format('m/d/Y H:i:s');
                $request['status_id'] = 12;
                $request['convert_to_deal'] = 1;
                $request['convert_deal_date'] = $datetime;
                $request['user'] = $userid;
            }else{
                $request['consultation_booked_date'] = $mysqlTimeMeeting->format('m/d/Y H:i:s');
                $request['user'] = $userid;
            }
        }

        
        if($current_won_lost !=  $request['won_lost'] && $request['won_lost'] != ''){
            $request['won_lost_date']  = $datetime;
        }elseif($current_won_lost ==  $request['won_lost'] && $request['won_lost'] != ''){
            if($request->input('won_lost_date') != ''){
                $mysqlTimeWonLost = Carbon::createFromFormat('m/d/Y h:ia', $request->input('won_lost_date'));
                $request['won_lost_date']  = $mysqlTimeWonLost->format('m/d/Y H:i:s');
            }else{
                $request['won_lost_date']  = $current_won_lost_date;
            }
        }else{
            $request['won_lost_date']  = null;
        }
        
        if($request['value'] != NULL){
            $request['value']= str_replace(",", "", $request->input('value'));
        }else{
            $request['value'] = NULL;
        }
        
        if($current_convert_to_deal == 1){
            $view = 'consults';
        }

        if($request->input('phone') != ''){
            $phone = preg_replace("/[^A-Za-z0-9]/", '', $request->input('phone'));
            if(strlen($phone) == 10){
                $phone = "+1".$phone;
            }
            if(strlen($phone) == 11){
                $phone = "+".$phone;
            }
            $request['phone']  = $phone;
        }

        if($request->input('reason') == '' && $request->input('status_id') == 9){
                return redirect()->route('admin.crm-customers.edit', $crmCustomer->id)->with('error', 'Please select Lost Reason (Why patient did not schedule)!');;
        }

        $crmCustomer->update($request->all());
        $crmCustomer->assignees()->sync($request->input('assignees', []));

        return redirect()->route('admin.crm-customers.edit', $crmCustomer->id)->with('success', 'Changes saved successfully!');;
    }

    public function show(CrmCustomer $crmCustomer)
    {
        abort_if(Gate::denies('crm_customer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //
        $userid = Auth()->user()->id;
        $isadmin =  Auth()->user()->getIsAdminAttribute();

        if(!$isadmin){

            $crmCustomer = $crmCustomer->load('clinic', 'source', 'status', 'assignees');
            $clinic = $crmCustomer->clinic;

            $hasclinicaccess = $clinic->whereHas('managers', function ($query ) use($userid) { 
                                                                     return   $query->where('user_id', '=', $userid );
                                                                    })->where('id', '=', $clinic->id)->first();

            if($hasclinicaccess){
                return view('admin.crmCustomers.show', compact('crmCustomer'));
            }else{
                return redirect()->route('admin.crm-customers.index');
            }
            
            
        }else{
            $crmCustomer->load('clinic', 'source', 'status', 'assignees');
            return view('admin.crmCustomers.show', compact('crmCustomer'));
        }
                

        
    }

    public function hassms(Request $request)
    {
        CrmCustomer::where('id', "=", $request->input('leadid'))->update(['has_sms' => 0]);
        return true;
    }

    public function sendemail(Request $request)
    {
        $lead = CrmCustomer::with(['clinic', 'source', 'status', 'assignees'])->where('id', $request->input('leadid'))->get();
         
        $emails_for_scheduling = $lead[0]->clinic->emails_for_scheduling;
        $lead_center = $lead[0]->clinic->lead_center;
        if($emails_for_scheduling != '' && $lead_center == "Yes"){
            $emaillist = explode(", ", $emails_for_scheduling);
            $flag = 0;
            foreach($emaillist as $key => $recepient){
                $flag = 1;
                $subject = '';
                $emailformat = '';
                $bookingdatetime = (new \Carbon\Carbon($lead[0]->consultation_booked_date))->format('l, F jS, Y @ h:iA');

                if($lead[0]->status->id == 6 ){
                    $subject = "Re:  New Practice Follow Up";
                    $emailformat = 1;
                }
                if($lead[0]->status->id == 12 ){
                    $subject = "Consultation Booked: [".$bookingdatetime."]";
                    $emailformat = 2;
                }

                $verbal_confirmation = $lead[0]->verbal_confirmation;
                $informed_consult_fee = $lead[0]->informed_consult_fee;

                $profile_url = 'https://lms.microsite.com/admin/crm-customers/'.$request->input('leadid').'/edit';

                if($lead[0]->clinic->version == '2.0'){
                    $profile_url = 'https://mycrtx.com/crtx/patient-profile/'.$request->input('leadid');
                }

                $data = array('email'=>$recepient,'url'=>$profile_url,'emailformat'=>$emailformat,'patientname'=>$lead[0]->first_name." ".$lead[0]->last_name,'patientdob'=>$lead[0]->dob,'appointmentdate'=>$bookingdatetime,'patientphone'=>$lead[0]->phone,'patientemail'=>$lead[0]->email, 'verbal_confirmation'=>$verbal_confirmation, 'informed_consult_fee'=>$informed_consult_fee, 'practicename'=>$lead[0]->clinic->clinic_name);
                
                Mail::send('mail', $data, function($message) use($subject,$data) {
                     $message->to($data['email'])->replyTo('consultation@thedentalimplanthotline.com', 'Dental Implants')->subject
                        ($subject);
                     $message->from('noreply@microsite.com','Dental Implants');
                });

                $CrmNote = new CrmNote;
                $CrmNote->note = "Notification Email is sent to ".$recepient." with subject line ".$subject;
                $CrmNote->user_id = Auth()->user()->id;
                $CrmNote->customer_id = $request->input('leadid');
                $CrmNote->save();
            }

            
        }else{
             return ['message' => "<b style='color:red'>There is no Emails For Scheduling for this practice.</b>", 'success' => false];
        }

        return ['message' => "Email sent Successfully", 'success' => false];
        
    }

    public function settings(Request $request){
        
        $request->session()->put('selectedclinic', $request->input('filter_clinic'));
        $referer = request()->headers->get('referer');
        if(str_contains($referer, 'view=consults')){
            return redirect()->route('admin.crm-customers.index',['view'=>'consults']);
        }elseif(str_contains($referer, 'view=table')){
            return redirect()->route('admin.crm-customers.index',['view'=>'table']);
        }else{
          return redirect()->route('admin.crm-customers.index');  
        }
        
    }

    public function markasspam(Request $request)
    {
        abort_if(Gate::denies('crm_customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');  
        $leadid = $request->input('leadid');
        $callraildata = Callrail::where('lead_id',$leadid)->first();
        //echo $callraildata->id;
        if(!empty($callraildata)){
            $orgdata = json_decode($callraildata->jdata, true);
            $resource_id = $orgdata['resource_id'];
        
            if($resource_id != ''){
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.callrail.com/v3/a/564577310/calls/".$resource_id.".json",
                    CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "PUT",
                      CURLOPT_POSTFIELDS => array('spam' => 'true'),
                      CURLOPT_HTTPHEADER => array(
                        "Authorization: Token token=\"4a42567f2622d34a69bf2bad734eb48a\"",
                        "Cookie: remember_device_token=5ff11cb8-7ab8-4581-a588-4e7a2958e87b"
                      ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                
                
                $resdata = json_decode($response,true);

                $CrmNote = new CrmNote;
                $CrmNote->note = "Marked as Spam";
                $CrmNote->user_id = Auth()->user()->id;
                $CrmNote->customer_id = $request->input('leadid');
                $CrmNote->save();
                CrmCustomer::where('id', $leadid)->delete();
                //return redirect()->route('admin.crm-customers.index'); 
                //return back()->with('success', 'Lead Maked as Spam successfully');
                return ['message' => "Lead Maked as Spam successfully", 'success' => true];
            }

              
        }else{
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    public function destroy(CrmCustomer $crmCustomer)
    {
        abort_if(Gate::denies('crm_customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $crmCustomer->delete();

        return back();
    }

    public function massDestroy(MassDestroyCrmCustomerRequest $request)
    {
        CrmCustomer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function restore($id)
    {
        CrmCustomer::withTrashed()->find($id)->restore();

        return back()->with('success', 'Lead Restore successfully');
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('crm_customer_create') && Gate::denies('crm_customer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new CrmCustomer();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function sendcreditcardpdf(Request $request){
        
        $data["email"] = "cccapture@microsite.com";
        $data["title"] = "From Microsite LMS";
        $data["creditcardnumber"] = $request->input('card_number');
        $data["nameoncard"] = $request->input('name_on_card');
        $data["expmonth"] = $request->input('exp_date');
        $data["cardcvv"] = $request->input('cvv');
        $data["zipcode"] = $request->input('zipcode');
        $data["url"] = 'https://lms.microsite.com/admin/crm-customers/'.$request->input('leadid').'/edit';
        $data["password"] = $request->input('cr_id').$request->input('leadid');
        $data["fname"] = $request->input('name');
        $data["practicename"] = $request->input('practicename');
        $data["leadid"] = $request->input('leadid');

        $pdf = PDF::loadView('emails.ccinfoMail', $data);
        $pdf->setEncryption($data["password"], $data["password"]);

  
        Mail::send('emails.creditcardMail', $data, function($message)use($data, $pdf) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"])
                    ->attachData($pdf->output(), rand().".pdf");
            $message->from('noreply@microsite.com','Microsite');
        });
    
        $CrmNote = new CrmNote;
        $CrmNote->note = "CreditCard Email is sent";
        $CrmNote->user_id = Auth()->user()->id;
        $CrmNote->customer_id = $request->input('leadid');
        $CrmNote->save();
        CrmCustomer::where('id', "=", $request->input('leadid'))->update(['ccapture' => 1]);
        return ['message' => "CreditCard Email sent Successfully", 'success' => false];
    }

    public function setEncryption($password) {
        $this->render();
        $this->dompdf->get_canvas()->get_cpdf()->setEncryption($password, $password);
    }
}
