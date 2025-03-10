<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Http\Resources\CRTX\LeadsResource;
use App\Models\CrmCustomer;
use App\Models\Clinic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ErrorLog;
use App\Traits\ExceptionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ExceptionLog;

    public function myDashboard(Request $request)
    {
        try {
            $user = Auth::user();
            $clinicId[] = $user->managerClinics()->pluck('id')[0];

            if($request->input('clinic_ids')){
                $clinicId = explode(',',$request->input('clinic_ids'));
            }

            //$customMessage = $user->managerClinics()->pluck('custom_message')[0];
            $customMessage = Clinic::select('custom_message')->whereIn('id',$clinicId)->pluck('custom_message');

            $crmCustomerAllTime = CrmCustomer::whereIn('clinic_id', $clinicId)
                ->where('won_lost', "Won")
                ->sum('value');

            $startDateCY = Carbon::now()->subMonths(12)->format('Y-m').'-01 00:00:00';
            $endDateCY = Carbon::now()->format('Y-m-d');
            $CYStartDate = Carbon::now()->startOfYear()->format('Y-m-d H:i:s');

            $crmCustomerCurrentYear = CrmCustomer::whereIn('clinic_id', $clinicId)
                ->where('won_lost', "Won")
                ->whereBetween('won_lost_date', [$CYStartDate, $endDateCY.' 23:59:59'])
                ->sum('value');

            $chartDataObTS = [];
            $crmCustomerCurrentYearData = CrmCustomer::select(
                \DB::raw('YEAR(updated_at) as year'),
                \DB::raw('MONTH(updated_at) as month'),
                \DB::raw('COUNT(*) as count'),
                'updated_at',
                \DB::raw('SUM(value) as total_value')
            )
                ->whereIn('clinic_id', $clinicId)
                ->where('won_lost', "Won")
                ->whereBetween('won_lost_date', [$startDateCY, $endDateCY.' 23:59:59'])
                ->orderBy('updated_at','asc')
                ->groupBy('year','month')
                ->get();
            if($crmCustomerCurrentYearData) {
                foreach ($crmCustomerCurrentYearData as $item) {
                    $itemDate = Carbon::createFromFormat('Y-m-d H:i:s', $item->updated_at)->format('M, Y');
                    $chartDataObTS['x_data'][] = $item->total_value;
                    $chartDataObTS['y_data'][] = $itemDate;
                }
            }

            //All Leads
            $chartDataObAL = [];
            $crmCustomerCurrentYearDataAll = CrmCustomer::select(\DB::raw('YEAR(created_at) as year'),\DB::raw('MONTH(created_at) as month'), \DB::raw('COUNT(*) as count'),'created_at')
                ->whereIn('clinic_id', $clinicId)
                ->whereBetween('created_at', [$startDateCY, $endDateCY.' 23:59:59'])
                ->orderBy('created_at','asc')
                ->groupBy('year','month')
                ->get();

            if($crmCustomerCurrentYearDataAll) {
                foreach ($crmCustomerCurrentYearDataAll as $item) {
                    $itemDate = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('M, Y');
                    $chartDataObAL['x_data'][] = $item->count;
                    $chartDataObAL['y_data'][] = $itemDate;
                }
            }


            $startDateCM = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
            $endDateCM = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
            $crmCustomerCurrentMonth = CrmCustomer::whereIn('clinic_id', $clinicId)
                ->where('won_lost', "Won")
                ->whereBetween('won_lost_date', [$startDateCM, $endDateCM.' 23:59:59'])
                ->sum('value');

            $crmCustomerLifeTimeValueSum = CrmCustomer::whereIn('clinic_id', $clinicId)->sum('lifetimevalue');

            return response()->json(
                [
                    'success' => true,
                    'crmCustomerAllTime' => number_format(round($crmCustomerAllTime)),
                    'crmCustomerCurrentYear' => number_format(round($crmCustomerCurrentYear)),
                    'crmCustomerCurrentMonth' => number_format(round($crmCustomerCurrentMonth)),
                    'crmCustomerLifeTimeValueSum' => number_format(round($crmCustomerLifeTimeValueSum)),
                    'customMessage' => $customMessage,
                    'chart_treatment_sold' => $chartDataObTS,
                    'chart_treatment_all' => $chartDataObAL,
                ],200
            );
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'GetPatientList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }

    public function salesSummary(Request $request)
    {
        try {
            $user = Auth::user();
            $startDate = $request->input('start_date').' 00:00:00';
            $endDate = $request->input('end_date');

            if($startDate == '' || $endDate == ''){
                return response()->json(['success' => true, 'message' => 'Start date or End date is missing'],200);
            }
//            switch ($date_range) {
//                case '1'://Today
//                    $startDate = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
//                    break;
//                case '2'://Yesterday
//                    $startDate = Carbon::now()->startOfDay()->subDay()->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->endOfDay()->subDay()->format('Y-m-d H:i:s');
//                    break;
//                case '3'://This Week
//                    $startDate = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');
//                    break;
//                case '4'://Last 7 Days
//                    $startDate = Carbon::now()->startOfDay()->subDay(6)->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->startOfDay()->format('Y-m-d').' 23:59:59';
//                    break;
//                case '5'://This Month
//                    $startDate = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
//                    break;
//                case '6'://Last 30 Days
//                    $startDate = Carbon::now()->startOfDay()->subDay(29)->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->startOfDay()->format('Y-m-d').' 23:59:59';
//                    break;
//                case '7'://Previous Month
//                    $startDate = Carbon::now()->startOfMonth()->subMonth(1)->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d').' 23:59:59';
//                    break;
//                case '8'://Last 3 Months
//                    $startDate = Carbon::now()->startOfMonth()->subMonth(2)->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->endOfMonth()->format('Y-m-d').' 23:59:59';
//                    break;
//                case '9'://This Quarter
//                    $startDate = Carbon::now()->startOfMonth()->subMonth(3)->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->endOfMonth()->format('Y-m-d').' 23:59:59';
//                    break;
//                case '10'://Previous Quarter
//                    $startDate = Carbon::now()->startOfMonth()->subMonth(5)->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->endOfMonth()->subMonth(4)->format('Y-m-d').' 23:59:59';
//                    break;
//                case '11'://This Year
//                    $startDate = Carbon::now()->startOfYear()->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->endOfYear()->format('Y-m-d').' 23:59:59';
//                    break;
//                case '12'://Previous Year
//                    $startDate = Carbon::now()->startOfYear()->subYear(1)->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->endOfYear()->subYear(1)->format('Y-m-d').' 23:59:59';
//                    break;
//                case '13'://Last 14 Days
//                    $startDate = Carbon::now()->startOfDay()->subDay(14)->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->startOfDay()->format('Y-m-d').' 23:59:59';
//                    break;
//                case '14'://Last 6 Month
//                    $startDate = Carbon::now()->startOfDay()->subMonth(5)->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->startOfDay()->format('Y-m-d').' 23:59:59';
//                    break;
//                case '15'://Previous 3 Months
//                    $startDate = Carbon::now()->startOfDay()->subMonth(2)->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->startOfDay()->format('Y-m-d').' 23:59:59';
//                    break;
//                default:
//                    $startDate = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
//                    $endDate = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
//            }


            $clinicId[] = $user->managerClinics()->pluck('id')[0];
            if($request->input('clinic_ids')){
                $clinicId = explode(',',$request->input('clinic_ids'));
            }
            $newLeadsCount = CrmCustomer::whereIn('clinic_id', $clinicId)
                ->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
                ->count();

            $consultationBookedCount = CrmCustomer::whereIn('clinic_id', $clinicId)
                ->whereBetween('convert_deal_date', [$startDate, $endDate.' 23:59:59'])
                ->count();

            $consultationShowedCount = CrmCustomer::whereIn('clinic_id', $clinicId)
                ->whereBetween('consultation_booked_date', [$startDate, $endDate.' 23:59:59'])
                ->where('status_id', 15)
                ->count();

            $treatmentPresentedCount = CrmCustomer::whereIn('clinic_id', $clinicId)
                ->whereBetween('updated_at', [$startDate, $endDate.' 23:59:59'])
                ->where('status_id', 15)
                ->whereNull('won_lost')
                ->sum('value');

            $consultationFollowupCount = CrmCustomer::whereIn('clinic_id', $clinicId)
                ->whereBetween('updated_at', [$startDate, $endDate.' 23:59:59'])
                ->whereNull('won_lost')
                ->whereNotNull('deal_status')
                ->count();

            $treatmentsSoldValue = CrmCustomer::whereIn('clinic_id', $clinicId)
                ->whereBetween('won_lost_date', [$startDate, $endDate.' 23:59:59'])
                ->where('won_lost', "Won")
                ->sum('value');

            return response()->json(
                [
                    'success' => true,
                    'data' => [
                        'newLeadsCount' => $newLeadsCount,
                        'consultationBookedCount' => $consultationBookedCount,
                        'consultationShowedCount' => $consultationShowedCount,
                        'treatmentsSoldValue' => number_format($treatmentsSoldValue,2,'.',','),
                        'consultationFollowupCount' => $consultationFollowupCount,
                        'treatmentPresentedCount' => number_format($treatmentPresentedCount,2,'.',','),
                    ]
                ],200
            );
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'GetPatientList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }

    public function leadsBySources(Request $request){
        try {
            $user = Auth::user();
            $startDate = $request->input('start_date').' 00:00:00';
            $endDate = $request->input('end_date');

            if($startDate == '' || $endDate == ''){
                return response()->json(['success' => true, 'message' => 'Start date or End date is missing'],200);
            }

            $clinicId[] = $user->managerClinics()->pluck('id')[0];
            if($request->input('clinic_ids')){
                $clinicId = explode(',',$request->input('clinic_ids'));
            }
            //$clinic_zohomarketingdashboard = $user->managerClinics()->pluck('zohomarketingdashboard')[0];
            $clinic_zohomarketingdashboard = Clinic::select('zohomarketingdashboard')->whereIn('id',$clinicId)->pluck('zohomarketingdashboard');
            $leadsBySources = CrmCustomer::select(\DB::raw('*,COUNT(source_id) as count'))
                ->whereIn('clinic_id', $clinicId)
                ->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
                ->groupBy('source_id')
                ->with('source')
                ->get();

            $result = [];
            foreach ($leadsBySources as $key => $lbs){
                $result['x_data'][] = $lbs->source['source_name'];
                $result['y_data'][] = $lbs->count;
            }
            $result['zohomarketingdashboard'] = $clinic_zohomarketingdashboard;
            return response()->json(
                [
                    'success' => true,
                    'data' => $result
                ],200
            );
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'GetPatientList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }

    public function dashboardNurturing(Request $request){
        try {
            $user = Auth::user();
            $startDate = $request->input('start_date').' 00:00:00';
            $endDate = $request->input('end_date');

            if($startDate == '' || $endDate == ''){
                return response()->json(['success' => true, 'message' => 'Start date or End date is missing'],200);
            }

            $clinicId[] = $user->managerClinics()->pluck('id')[0];
            if($request->input('clinic_ids')){
                $clinicId = explode(',',$request->input('clinic_ids'));
            }
            $leadNurtures = CrmCustomer::select(\DB::raw('created_at as month, COUNT(*) as total_records'))
                ->groupBy(\DB::raw('MONTH(created_at)'))
                ->orderBy(\DB::raw('MONTH(created_at)'))
                ->whereIn('clinic_id', $clinicId)
                ->where('email_sequence','>', 0)
                ->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
                ->get();


            $consultBooked = CrmCustomer::select(\DB::raw('created_at as month, COUNT(*) as total_records'))
                ->groupBy(\DB::raw('MONTH(created_at)'))
                ->orderBy(\DB::raw('MONTH(created_at)'))
                ->whereIn('clinic_id', $clinicId)
                ->where('email_sequence','>', 0)
                ->where('convert_to_deal', 1)
                ->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
                ->get();

            $result_leadsNurtured = [];
            foreach ($leadNurtures as $ln){
                $result_leadsNurtured[Carbon::createFromFormat('Y-m-d H:i:s', $ln->month)->format('M Y')] = $ln->total_records;
            }
            $result_consultBooked = [];
            foreach ($consultBooked as $cb){
                $result_consultBooked[Carbon::createFromFormat('Y-m-d  H:i:s', $cb->month)->format('M Y')] = $cb->total_records;
            }

            $table_array = [];
            if(count($result_leadsNurtured) > count($result_consultBooked)){
                foreach ($result_leadsNurtured as $key => $r_ln){
                    $table_array[$key][0] = $key;
                    $table_array[$key][1] = $r_ln;
                }
                foreach ($result_consultBooked as $key => $r_cb){
                    $table_array[$key][2] = $r_cb;
                }

            }else if(count($result_consultBooked) > count($result_leadsNurtured)){
                foreach ($result_consultBooked as $key => $r_cb){
                    $table_array[$key][0] = $key;
                    $table_array[$key][1] = $r_cb;
                }
                foreach ($result_leadsNurtured as $key => $r_ln){
                    $table_array[$key][2] = $r_ln;
                }
            }else{
                foreach ($result_leadsNurtured as $key => $r_ln){
                    $table_array[$key][0] = $key;
                    $table_array[$key][1] = $r_ln;
                }
                foreach ($result_consultBooked as $key => $r_cb){
                    $table_array[$key][2] = $r_cb;
                }

            }

            return response()->json(
                [
                    'success' => true,
                    'data' => $table_array
                ],200
            );
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'GetPatientList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }

    public function getRecentLeads(Request $request)
    {
        $user = Auth::user();
        $clinicIds = $user->managerClinics()->pluck('id')->first();
        $recordsPerPage = $request->input('perPage');
        $searchTerm = $request->input('query');
        $status_id = $request->input('statusId'); //stage_id
        $pageNumber = $request->input('currentPage');   // Page number
        $sort_by = $request->input('sortBy', 'updated_at'); // Default sort column
        $sortOrder = $request->input('sort_order', 'desc'); // Default sort order

        if($request->input('clinic_id')){
            $clinicId = explode(',',$request->input('clinic_id'));
            $clinicId[] = $clinicId;
        }else{
            $clinicId[] = $clinicIds;
        }
        $originalSearchTerm = $searchTerm;

        if(str_contains($searchTerm, '-')){
            $searchTerm = str_replace('-', '', $searchTerm);
        }

        $leads = CrmCustomer::with(['source', 'status', 'users'])
            ->where(function ($query) use ($searchTerm, $originalSearchTerm, $status_id) {
                $query->where(function ($innerQuery) use ($searchTerm, $originalSearchTerm) {
                    $searchTermArr = explode('-', $searchTerm);
                    $q = $innerQuery->where(function ($query) use ($originalSearchTerm) {
                        $query->where('callrail_details->landing_page_url', 'like', '%' . $originalSearchTerm . '%')
                            ->orWhere(function ($homeQuery) use ($originalSearchTerm) {
                                $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                if ($originalSearchTerm === 'home') {
                                    $homeQuery->where(function ($subQuery) use ($landingPageField) {
                                        $subQuery->whereNull('callrail_details')
                                            ->orWhereRaw("{$landingPageField} = ''")
                                            ->orWhereRaw("{$landingPageField} = '/'")
                                            ->orWhereRaw("{$landingPageField} = 'home'");
//                                        ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                    });
                                } else {
                                    $homeQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchTerm . '%']);
                                }
                            });
                    })
                        ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%')
                        ->orWhere('phone', 'like', '%' . $searchTerm . '%')
                        ->orWhere('tagName', 'like', "%$searchTerm%")
                        ->orWhere('dob', 'like', "%$searchTerm%")
                        ->orWhere('phone_form', 'like', "%$searchTerm%")
                        ->orWhereHas('status', function ($statusQuery) use ($searchTerm) {
                            $statusQuery->where('name', 'like', '%' . $searchTerm . '%');
                        })
                        ->orWhereHas('source', function ($sourceQuery) use ($searchTerm) {
                            $sourceQuery->where('source_name', 'like', '%' . $searchTerm . '%');
                        });

                    $firstName = '';
                    $lastName = '';
                    $searchTermExp = explode(' ', $searchTerm);
                    if(count($searchTermExp) > 1){
                        $firstName = $searchTermExp[0];
                        $lastName = $searchTermExp[1];
                    }

                    if(!empty($firstName) && !empty($lastName)){
                        $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                    }

                });
                if (!empty($status_id)) {
                    $query->where('status_id', $status_id);
                }
            })
            ->where('won_lost', NULL)
            ->whereIn('crm_customers.clinic_id', $clinicId);

        $leads->whereNull('crm_customers.deleted_at');

        $leads->leftJoin("tag_leads_mapping", function ($join) {
            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                ->whereNull('tag_leads_mapping.deleted_at');
        })
            ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
            ->groupBy('crm_customers.id');

        if($status_id==12){
            $leads = $leads->whereDate('consultation_booked_date', '>=', Carbon::today());
        }

        $sortBy = '';

        switch ($sort_by) {
            case 'Full Name':
                $sortBy = 'first_name';
                break;
            case 'Email':
                $sortBy = 'email';
                break;
            case 'Source':
                $sortBy = 'source_id';
                break;
            case 'Phone/Form Lead':
                $sortBy = 'phone_form';
                break;
            case 'Phone':
                $sortBy = 'phone';
                break;
            case 'Date of Birth':
                $sortBy = 'dob';
                break;
            case 'Created At':
                $sortBy = 'crm_customers.created_at';
                break;
            case 'Lead Score':
                $sortBy = 'lead_score';
                break;
            case 'Tags':
                $sortBy = 'tagName';
                break;
            default:
                $sortBy = 'crm_customers.updated_at';
                break;
        }

        if (!empty($sortBy)) {
            $leads = $leads->orderBy($sortBy, $sortOrder);
        }


        if($request->count){
            return count($leads->get()->toArray());
        }

        //DB::enableQueryLog();
        $leads = $leads->select(
            'crm_customers.*',
            DB::raw("DATE_FORMAT(crm_customers.updated_at, '%Y-%m-%d %H:%i:%s') AS last_updated"),
            DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") AS tagName')
        )->paginate($recordsPerPage, ['*'], 'page', $pageNumber);

        try {
            // return LeadsResource::collection(CrmCustomer::with(['source','status','users'])->get())->additional(['success'=>true]);
            return LeadsResource::collection($leads)->additional(['success' => true]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'leadsList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }

    public function getRecentLeadsCount(Request $request)
    {
        $user = Auth::user();
        $clinicIds = $user->managerClinics()->pluck('id')->first();

        if($request->input('clinic_id')){
            $clinicId = explode(',',$request->input('clinic_id'));
            $clinicId[] = $clinicId;
        }else{
            $clinicId[] = $clinicIds;
        }

        $request->merge(['statusId' => 12, 'count' => true]);
        $scheduledCount = $this->getRecentLeads($request);

        $request->merge(['statusId' => 6, 'count' => true]);
        $followUpCount = $this->getRecentLeads($request);


        try {
            return response()->json(['success' => true, 'scheduledCount' => $scheduledCount, 'followUpCount' => $followUpCount]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'leadsList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }

}
