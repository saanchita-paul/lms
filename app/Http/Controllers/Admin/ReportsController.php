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
use DB;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class ReportsController extends Controller
{

    public function index(Request $request)
    {
        abort_if(Gate::denies('reports_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userid = $request->user()->id;

        $isadmin =  $request->user()->getIsAdminAttribute();

        $source_id = request()->input('source_id', "");

        $report_type = request()->input('report_type', "");

        $won_date_type = request()->input('won_date_type', "");

        if ($request->ajax()) {

            $date = explode(" - ", request()->input('from-to', ""));

            if( (count($date) != 2 || request()->input('from-to', "") == null ) && ($report_type != 7 && $report_type != 5))
            {
                $date = [now()->subDays(30)->format("Y-m-d H:i:s"), now()->format("Y-m-d H:i:s")];
            }elseif( (count($date) != 2 || request()->input('from-to', "") == null ) && ($report_type == 7 || $report_type == 5))
            {
                $date = [now()->subYears(30)->format("Y-m-d H:i:s"), now()->format("Y-m-d H:i:s")];
            }else{
                $date[0] = date('Y-m-d H:i:s', strtotime($date[0]));

                $date[1] = date('Y-m-d 23:59:59', strtotime($date[1]));
            }

            $datefilter = 'crm_customers.created_at';
            if($report_type == 1 || $report_type == ''){
                $datefilter = 'crm_customers.created_at';
            }elseif($report_type == 2){
                $datefilter = 'crm_customers.convert_deal_date';
            }elseif($report_type == 3){
                $datefilter = 'crm_customers.updated_at';
            }elseif($report_type == 4){
                $datefilter = 'crm_customers.no_showed_date';
            }elseif($report_type == 5){
                $datefilter = 'crm_customers.updated_at';
            }elseif($report_type == 6){
                if($won_date_type == 2){
                    $datefilter = 'crm_customers.created_at';
                }else{
                    $datefilter = 'crm_customers.won_lost_date';
                }

            }elseif($report_type == 7){
                $datefilter = 'crm_customers.updated_at';
            }

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
            if($report_type == 2){
                $query = $query->where('convert_to_deal','=',1);
            }
            if($report_type == 3){
                $query = $query->where('status_id','=',15);
            }
            if($report_type == 4){
                $query = $query->where('status_id','=',13);
            }
            if($report_type == 5){
                $query = $query->where('status_id','=',15)->whereNull('won_lost');
            }
            if($report_type == 6){
                $query = $query->where('won_lost',"Won");
            }
            if($report_type == 7){
                $query = $query->where('status_id','=',9);
            }


            $selectedclinic = array();

            if(session('selectedclinic')){
                $selectedclinic = session('selectedclinic');
                $query = $query->whereIn('clinic_id',$selectedclinic);
            }

            if($isadmin){
            $quey = $query->select('crm_customers.*','callrail.lead_id', DB::raw('JSON_UNQUOTE(json_extract(callrail.jdata, "$.utm_source")) as utm_source'), DB::raw('JSON_UNQUOTE(json_extract(callrail.jdata, "$.utm_medium")) as utm_medium'), DB::raw('JSON_UNQUOTE(json_extract(callrail.jdata, "$.utm_term")) as utm_term'), DB::raw('JSON_UNQUOTE(json_extract(callrail.jdata, "$.utm_content")) as utm_content'), DB::raw('JSON_UNQUOTE(json_extract(callrail.jdata, "$.utm_campaign")) as utm_campaign'))->leftJoin('callrail', function($leftJoin)
                    {
                        $leftJoin->on('callrail.lead_id', '=', 'crm_customers.id');
                    })->distinct();
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
        $board_statuses = CrmStatus::where('board','=','lead')->get();
        if(request()->input('view')=="consults"){
            $board_statuses = CrmStatus::where('board','=','consult')->get();
        }

        $users        = User::get();



        // ...

        $date = explode(" - ", request()->input('from-to', ""));

        if( (count($date) != 2 || request()->input('from-to', "") == null ) && ($report_type != 7 && $report_type != 5))
        {
            $date = [now()->subDays(30)->format("Y-m-d H:i:s"), now()->format("Y-m-d H:i:s")];
        }elseif( (count($date) != 2 || request()->input('from-to', "") == null ) && ($report_type == 7 || $report_type == 5))
        {
            $date = [now()->subYears(30)->format("Y-m-d H:i:s"), now()->format("Y-m-d H:i:s")];
        }else{
            $date[0] = date('Y-m-d H:i:s', strtotime($date[0]));

            $date[1] = date('Y-m-d 23:59:59', strtotime($date[1]));
        }
//echo "<pre>";print_r($date);exit;
        $condition = '1 = 1';
        if($source_id > 0){
            //$query = $query->where('source','=',$source_id);
            $condition = 'source_id = '.$source_id;
        }

        $selectedclinic = array();
        if(session('selectedclinic')){
            $selectedclinic = session('selectedclinic');
            $condition .= ' and clinic_id in ('.implode(",",$selectedclinic).')';
        }

        if(!$isadmin){
            $condition .= ' and exists ( select * from `clinic_user` where true and `clinic_user`.`clinic_id` = `crm_customers`.`clinic_id` and user_id = '.$userid.')';
        }

        $chartdatefilter = 'created_at';
        $group_by_field_format = 'Y-m-d H:i:s';
        $title = 'New Leads';
        $aggregate_function = "count";
        $aggregate_field = "id";


        if($report_type == 1 || $report_type == ''){
            $chartdatefilter = 'created_at';
        }elseif($report_type == 2){
            $chartdatefilter = 'convert_deal_date';
            $date[0] = date('Y-m-d H:i:s', strtotime($date[0]));
            $date[1] = date('Y-m-d 23:59:59', strtotime($date[1]));
            $group_by_field_format = 'm/d/Y H:i:s';
            $title = 'Consultations Booked';
            $condition .= ' and convert_to_deal=1';
        }elseif($report_type == 3){
            $chartdatefilter = 'updated_at';
            $title = 'Consultations Showed';
            $condition .= ' and status_id = 15';
        }elseif($report_type == 4){
            $chartdatefilter = 'no_showed_date';
            $title = 'Consultations Not Showed';
            $date[0] = date('Y-m-d H:i:s', strtotime($date[0]));
            $date[1] = date('Y-m-d 23:59:59', strtotime($date[1]));
            $group_by_field_format = 'm/d/Y H:i:s';
            $condition .= ' and status_id = 13';
        }elseif($report_type == 5){
            $chartdatefilter = 'updated_at';
            $title = 'Pending Acceptance';
            $condition .= ' and status_id = 15 and won_lost is null';
            $aggregate_function = "sum";
            $aggregate_field = "value";
        }elseif($report_type == 6){
            if($won_date_type == 2){
                $chartdatefilter = 'created_at';
            }else{
                $chartdatefilter = 'won_lost_date';
            }

            $date[0] = date('Y-m-d H:i:s', strtotime($date[0]));
            $date[1] = date('Y-m-d 23:59:59', strtotime($date[1]));
            $group_by_field_format = 'm/d/Y H:i:s';
            $title = 'Treatment Sold';
            $aggregate_function = "sum";
            $aggregate_field = "value";
            $condition .= ' and won_lost = "Won"';
        }elseif($report_type == 7){
            $chartdatefilter = 'updated_at';
            $title = 'Leads Nurturing';
            $condition .= ' and status_id = 9';
        }

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
        return view('admin.reports.index', compact('clinics', 'sources', 'sourcesfilter', 'crm_statuses', 'board_statuses','users','selectedclinic','chart','report_type','won_date_type','title'));
    }




}
