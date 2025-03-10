<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use App\Models\Source;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Session;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class UserReportsController extends Controller
{
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('userreports_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userid = $request->user()->id;

        $isadmin =  $request->user()->getIsAdminAttribute();

        $source_id = request()->input('source_id', "");

        $user_id = request()->input('user_id', "");

        
        if ($request->ajax()) {

            $date = explode(" - ", request()->input('from-to', ""));

            if( (count($date) != 2 || request()->input('from-to', "") == null ))
            {
                $date = [now()->subDays(30)->format("Y-m-d H:i:s"), now()->format("Y-m-d H:i:s")];
            }else{
                $date[0] = date('Y-m-d H:i:s', strtotime($date[0]));

                $date[1] = date('Y-m-d 23:59:59', strtotime($date[1]));  
            }

            
            $datefilter = 'crm_customers.convert_deal_date';
            

            //echo $datefilter;exit;
            if(!$isadmin){
               
                $query = CrmCustomer::with(['clinic', 'source', 'status', 'assignees'])->whereBetween($datefilter, $date)->whereHas('clinic.managers', function ($query ) use($userid) { 
                        return   $query->where('user_id', '=', $userid );
                    })->get();
             
            }else{
                $query = CrmCustomer::with(['clinic', 'source', 'status', 'assignees'])->whereBetween($datefilter, $date)->select(sprintf('%s.*', (new CrmCustomer())->table));
            }
            
            if($source_id > 0){
                $query = $query->where('source_id','=',$source_id);
            }
            if($user_id > 0){
                $query = $query->where('user','=',$user_id);
            }
            
            $query = $query->where('convert_to_deal','=',1);
                        

            
            $selectedclinic = array();

            if(session('selectedclinic')){
                $selectedclinic = session('selectedclinic');
                $query = $query->whereIn('clinic_id',$selectedclinic);
            }

            /*echo $query->toSql();
            print_r($query->getBindings());
            exit;*/

            $table = Datatables::of($query);

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
                                                                    })->get();
        }else{
            $clinics      = Clinic::get();
        }


                

        $sources      = Source::get();
        $sourcesfilter = Source::all()->pluck('source_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $crm_statuses = CrmStatus::get();

        $userfilter = User::whereHas(
                'roles', function($q){
                    $q->where('title', 'Lead Center Associate');
                }
            )->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        
        $users        = User::get();

        $date = explode(" - ", request()->input('from-to', ""));

        if( (count($date) != 2 || request()->input('from-to', "") == null ))
        {
            $date = [now()->subDays(30)->format("Y-m-d H:i:s"), now()->format("Y-m-d H:i:s")];
        }else{
            $date[0] = date('Y-m-d H:i:s', strtotime($date[0]));

            $date[1] = date('Y-m-d 23:59:59', strtotime($date[1]));  
        }
//echo "<pre>";print_r($date);exit;
        $condition = '1 = 1';
        if($source_id > 0){
            $condition = 'source_id = '.$source_id;
        }
        if($user_id > 0){
            $condition = 'user = '.$user_id;
        }

        $selectedclinic = array();
        if(session('selectedclinic')){
            $selectedclinic = session('selectedclinic');
            $condition .= ' and clinic_id in ('.implode(",",$selectedclinic).')';
        }

        if(!$isadmin){
            $condition .= ' and exists ( select * from `clinic_user` where true and `clinic_user`.`clinic_id` = `crm_customers`.`clinic_id` and user_id = '.$userid.')';
        }

        
        $aggregate_function = "count";
        $aggregate_field = "id";


        
        $chartdatefilter = 'convert_deal_date';
        $date[0] = date('Y-m-d H:i:s', strtotime($date[0]));
        $date[1] = date('Y-m-d 23:59:59', strtotime($date[1])); 
        $group_by_field_format = 'm/d/Y H:i:s';
        $title = 'Consultations Booked';
        $condition .= ' and convert_to_deal=1';
        

//echo  "<pre>";print_r($date);exit;
        
        $settings = [
            'chart_title'           => $title,
            'chart_type'            => 'line',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\CrmCustomer',
            'conditions'            => [
                ['name' => $title,  'condition' => $condition, 'color' => '#ff4081', 'fill' => true],
            ],
            'group_by_field'        => $chartdatefilter,
            'group_by_period'       => 'day',
            'aggregate_function'    => $aggregate_function,
            'aggregate_field'       => $aggregate_field,
            'filter_field'          => $chartdatefilter,
            'range_date_start'      => $date[0],
            'range_date_end'        => $date[1],
            'group_by_field_format' => $group_by_field_format,
            'column_class'          => 'col s12 m8 12',
            'entries_number'        => '5',
            'continuous_time'       => true,
        ];
//echo  "<pre>";print_r($settings);exit;
        $chart = new LaravelChart($settings);
//dd($chart);exit;
        return view('admin.userreports.index', compact('clinics', 'sources', 'sourcesfilter', 'userfilter', 'crm_statuses', 'users','selectedclinic','chart','title'));
    }

    


}
