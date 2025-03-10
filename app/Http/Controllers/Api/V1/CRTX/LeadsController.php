<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SendNotificationTrait;
use App\Http\Resources\CRTX\LeadsResource;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use App\Models\Source;
use App\Models\User;
use App\Models\ErrorLog;
use App\Traits\ExceptionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator;
use DB;
use Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\CrmNote;

class LeadsController extends Controller
{
    use ExceptionLog;
    use SendNotificationTrait;

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'status_id'      => 'required',
            'source_id'  => 'required',
            'phone_form' => 'required',
            'first_name' => 'required',
            'phone'     => 'required',
        ]);

        if ($validate->fails()) {
            $error = $this->errorMessages($validate);
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'errors' => $error]);
        }

        if($request->input('clinic_id')){
            $clinicId = $request->input('clinic_id');
        }else{
            $user = Auth::user();
            $clinicId = $user->managerClinics()->pluck('id')->first();
        }

        $request->validate([
            'phone' => ['required'],
        ]);

        $phoneNumber = $request->input('phone');
        // Remove non-numeric characters
        $numericPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Remove brackets and dashes
        $cleanedPhoneNumber = str_replace(['(', ')', '-'], '', $numericPhoneNumber);
        $phoneNumber = $cleanedPhoneNumber;
        // Check if the phone number starts with "+1"
        if (!Str::startsWith($phoneNumber, '+1')) {
            $phoneNumber = '+1' . $phoneNumber;
        }
        // Check if the provided phone number is unique
        $phoneExists = CrmCustomer::where(['phone'=>$phoneNumber,"clinic_id"=>$clinicId])->exists();

        if ($phoneExists) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number already exists. Please use a different phone number.'
            ]);
        }

        // Check if the provided email is unique
        $email = $request->email;
        if($request->email_not_available){
            $email = str_replace('+', '', $phoneNumber).Str::random(10).'@noreply.com';
        }

        $emailExists = CrmCustomer::where(['email'=>$email,"clinic_id"=>$clinicId])->exists();

        if ($emailExists) {
            return response()->json([
                'success' => false,
                'message' => 'Email address already exists. Please use a different email address.'
            ]);
        }

        // Lead Data
        $leadstore = new CrmCustomer();
        $leadstore->status_id  = $request->status_id;
        $leadstore->source_id = $request->source_id;
        $leadstore->phone_form = $request->phone_form;
        $leadstore->first_name = $request->first_name;
        $leadstore->last_name = $request->last_name;
        $leadstore->phone = $phoneNumber;
        $leadstore->phone_verified = $request->phone_verified;
        $leadstore->email_verified = $request->email_verified;
        $leadstore->dob = $request->dob;
        $leadstore->city = $request->city;
        $leadstore->state = $request->state;
        $leadstore->email = $email;
        $leadstore->badge = $request->badge;
        $leadstore->description = $request->description;
        $leadstore->clinic_id = $clinicId;
        $leadstore->created_at = Carbon::now()->format('m/d/Y H:i:s');
        $leadstore->save();

        if($request->phone_form == 'Web Form'){
            $clinicDefaultTimezone = 'America/New_York';
            $customers = CrmCustomer::with('clinic')
                ->where('id', '=', $leadstore->id)
                ->get(['id','first_name', 'last_name', 'email', 'phone', 'created_at', 'clinic_id']);

            $preparedData = $customers->map(function ($customer) use ($clinicDefaultTimezone) {
                $clinic = $customer->clinic;
                $clinicTimezone = $clinic->timezone ?? $clinicDefaultTimezone;
                $assistantId = $clinic->vapi_assistant_id ?? null;
                $phoneNumberId = $clinic->vapi_phone_number_id ?? null;
                $officeStartHour = 9; // 9:00 AM
                $officeEndHour = 21; // 9:00 PM
                $leadCreationTime = Carbon::parse($customer->created_at, 'UTC')
                    ->setTimezone($clinicTimezone);
                $isWithinOfficeHours = $leadCreationTime->hour >= $officeStartHour && $leadCreationTime->hour < $officeEndHour;
                $postdata = [
                    'id' => $customer->id,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'timezone' => $isWithinOfficeHours ? 'Yes' : 'No',
                    'vapi_assistant_id' => $assistantId,
                    'vapi_phone_number_id' => $phoneNumberId,
                ];
                if($assistantId != '')
                {
                    $responsedata = Http::post('https://hook.eu2.make.com/5du4nhae7cqkviv44gnulxa2i7nhj51p', [
                        'data' => $postdata ?? null,
                        'message' => 'Outbound Calling Webhook processed successfully'
                    ]);
                }
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully!',
            'data' => ['data' => $leadstore]
        ], 200);
    }

    public function getleads(Request $request)
    {
        $user = Auth::user();
        $clinicIds = $user->managerClinics()->pluck('id')->first();
        $recordsPerPage = $request->input('recordsPerPage');
        $searchTerm = $request->input('search');
        $status_id = $request->input('status_id'); //stage_id
        $pageNumber = $request->input('page');   // Page number
        $sort_by = $request->input('sort_by', 'updated_at'); // Default sort column
        $sortOrder = $request->input('sort_order', 'desc'); // Default sort order
        $deletedLeads = $request->input('deleted_leads');
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

        if ($deletedLeads == 'yes') {
            $leads->onlyTrashed();
        } else {
            $leads->whereNull('crm_customers.deleted_at');
        }

        $leads->leftJoin("tag_leads_mapping", function ($join) {
            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                ->whereNull('tag_leads_mapping.deleted_at');
        })
        ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
        ->groupBy('crm_customers.id');

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
        //DB::enableQueryLog();
        $leads = $leads->select(
            'crm_customers.*',
            DB::raw("DATE_FORMAT(crm_customers.updated_at, '%Y-%m-%d %H:%i:%s') AS last_updated"),
            DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") AS tagName')
        )
        ->paginate($recordsPerPage, ['*'], 'page', $pageNumber);
        //dd(DB::getQueryLog());


        try {
            // return LeadsResource::collection(CrmCustomer::with(['source','status','users'])->get())->additional(['success'=>true]);
            return LeadsResource::collection($leads)->additional(['success' => true]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'leadsList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }

    }

    public function moveStage(Request $request)
    {
        $status_id = $request->input('status_id');
        $leadid = $request->input('leadid');
        $reason = $request->input('reason');
        $consultation_booked_date = Null;
        $convert_to_deal = 0;

        $crmCustomer = CrmCustomer::find($leadid);
        $current_consulatation_booked_date =   $crmCustomer->consultation_booked_date;
        $current_status = $crmCustomer->status_id;

        $userid = Auth()->user()->id;

        if($crmCustomer) {
            if($status_id != NUll){
                $crmCustomer->status_id = $status_id;

                if($crmCustomer->status_id==9){
                    $crmCustomer->reason = $reason;
                }

                if($status_id == 12){
                    $datetime = date('m/d/Y H:i:s');
                    $convert_to_deal = 1;
                    $crmCustomer->convert_deal_date = $datetime;
                    $crmCustomer->convert_to_deal = $convert_to_deal;
                    $crmCustomer->user = $userid;
                }
            }
            if($request->input('consultation_booked_date')){
                $mysqlTimeMeeting = Carbon::createFromFormat('m/d/Y h:ia', $request->input('consultation_booked_date'));
                if($request->input('consultation_booked_date') != '' && ($mysqlTimeMeeting->format('m/d/Y H:i:s') != $current_consulatation_booked_date)){
                    $status_id = 12;
                    $consultation_booked_date = $mysqlTimeMeeting->format('m/d/Y H:i:s');
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
                $datetime = date('m/d/Y H:i:s');
                $crmCustomer->no_showed_date  = $datetime;
            }

            $crmCustomer->save();

            return response()->json([
                'success' => true,
                'message' => 'Lead moved to selected stage successfully!',
                'data' => ['data' => $crmCustomer]
            ], 200);
        }
        else{
            return response()->json([
                'success' => true,
                'message' => 'Something is wrong',
            ], 200);
        }
    }

    public function getStageCount(Request $request)
    {
        $board = $request->input('board');
        $deletedLeads = $request->input('deleted_leads');
        $clinicId = explode(',',$request->input('clinic_id'));
        if($board == 'board')
        {
            $board_statuses = CrmStatus::where('board','=','lead')->orderByRaw('FIELD(id, "1","5","2","3","4","6","17")')->get();
        }
        else{
            $board_statuses = CrmStatus::where('board','=','consult')->orderByRaw('FIELD(id, "12","13","14","15")')->get();
        }

        $kanban_board_data = array();

        foreach($board_statuses as $key => $item) {
            if(!empty($clinicId)){
                if($board == 'board')
                {
                    $leads = CrmCustomer::with(['clinic', 'source', 'status', 'assignees'])
                        ->whereIn('clinic_id',$clinicId)
                        ->where('status_id', $item->id)
                        ->where('won_lost', NULL)
                        ->orderBy('updated_at', 'desc');
                    if($deletedLeads == 'yes'){
                        $leads->onlyTrashed();
                    }
                    $leads = $leads->count();

                } else {
                    $leads = CrmCustomer::where('status_id', $item->id)
                        ->where('won_lost', NULL)
                        ->whereIn('clinic_id', $clinicId)
                        ->orderByRaw("
                           (case when consultation_booked_date <= now() - interval 7 day then 1 else 0 end),
                           (case when consultation_booked_date <= now() - interval 7 day then consultation_booked_date end) desc,
                           consultation_booked_date asc
                        ");

                    if($deletedLeads == 'yes'){
                        $leads->onlyTrashed();
                    }
                    $leads = $leads->count();
                }
            }
            else{
                $leads = array();
            }
            $kanban_board_data []= (object) array('id' => "$item->id", 'TotalCount' => $leads);
        }

        // Get the count of treatments sold
        if(!$request->has('board') && !empty($clinicId)){
            $treatments_sold = CrmCustomer::where('won_lost', 'Won')->whereIn('clinic_id', $clinicId)
                ->orderByRaw("
                           (case when consultation_booked_date <= now() - interval 7 day then 1 else 0 end),
                           (case when consultation_booked_date <= now() - interval 7 day then consultation_booked_date end) desc,
                           consultation_booked_date asc
                        ");

            if($deletedLeads == 'yes'){
                $kanban_board_data [] =  ['id'=>"0", 'TotalCount'=>$treatments_sold->onlyTrashed()->count()];
            }else{
                $kanban_board_data [] = ['id'=>"0", 'TotalCount'=>$treatments_sold->count()];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Lead data',
            'data' =>  $kanban_board_data
        ], 200);
    }

    public function getconsult(Request $request)
    {
        // Assuming $request, $leadsPerPage, and $leads are already defined
        $searchTerm = $request->input('search');
        $status_id = $request->input('status_id');
        $pageNumber = $request->input('page', 1);
        $sort_by = $request->input('sort_by', 'updated_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $clinicIds = explode(',',$request->input('clinic_id'));
        $deletedLeads = $request->input('deleted_leads');
        $leadsPerPage = $request->input('recordsPerPage'); // You can set this value according to your needs

        $originalSearchTerm = $searchTerm;

        if(str_contains($searchTerm, '-')){
            $searchTerm = str_replace('-', '', $searchTerm);
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
            case 'Treatment Amount':
                $sortBy = 'value';
                break;
            case 'Date of Birth':
                $sortBy = 'dob';
                break;
            case 'Consultation Booked Date':
                $sortBy = 'consultation_booked_date';
                break;
            case 'Lead Score':
                $sortBy = 'lead_score';
                break;
            case 'Tags':
                $sortBy = 'tagName';
                break;
            default:
                // Handle the default sorting option or any other cases
                $sortBy = 'crm_customers.updated_at';
                break;
        }
       $consult = CrmCustomer::with(['source', 'status', 'users'])
            ->where('won_lost', $status_id == 0 ? 'Won' : null)
//           ->where(function ($query) use ($searchTerm, $originalSearchTerm) {
            ->when($searchTerm, function ($query, $searchTerm) use ($originalSearchTerm) {
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
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
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
            })
            ->when($status_id, function ($query, $status_id) {
                $query->where('status_id', $status_id);
            })
            ->when($deletedLeads, function ($query, $deletedLeads) {
                if($deletedLeads == 'yes'){
                    $query->onlyTrashed();
                }
            })
            ->when($clinicIds, function ($query) use ($clinicIds) {
                $query->whereIn('crm_customers.clinic_id', $clinicIds);
            })

            ->leftJoin("tag_leads_mapping", function ($join) {
                $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                    ->whereNull('tag_leads_mapping.deleted_at');
            })
            ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
            ->groupBy('crm_customers.id')
            ->orderBy($sortBy, $sortOrder)
            ->orderByRaw("(case when consultation_booked_date <= now() - interval 7 day then 1 else 0 end),(case when consultation_booked_date <= now() - interval 7 day then consultation_booked_date end) desc,
                consultation_booked_date asc")
                ->select('crm_customers.*', DB::raw("CONCAT('(', SUBSTRING(phone, 1, 3), ') ', SUBSTRING(phone, 4, 3), '-', SUBSTRING(phone, 7)) AS masked_phone, DATE_FORMAT(crm_customers.updated_at, '%Y-%m-%d %H:%i:%s') AS last_updated, GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ', ') AS tagName"))

            ->paginate($leadsPerPage, ['*'], 'page', $pageNumber);
          //  dd(DB::getQueryLog());
        try {
            // return LeadsResource::collection(CrmCustomer::with(['source','status','users'])->get())->additional(['success'=>true]);
            return LeadsResource::collection($consult)->additional(['success' => true]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'leadsList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }

    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $clinicId = $request->input('clinic_id');

        if(str_contains($searchTerm, '-')){
            $searchTerm = str_replace('-', '', $searchTerm);
        }

        $searchTermArr = explode(' ', $searchTerm);

        $results = CrmCustomer::join('clinics', 'crm_customers.clinic_id', '=', 'clinics.id')
            ->where('crm_customers.clinic_id', $clinicId)
            ->where(function ($query) use ($searchTerm, $searchTermArr) {

                $q = $query->where('crm_customers.first_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('crm_customers.last_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('crm_customers.email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('crm_customers.phone', 'like', '%' . $searchTerm . '%');

               /* foreach ($searchTermArr as $searchTerms) {
                    $q->orWhere('crm_customers.first_name', 'like', '%' . $searchTerms . '%')
                        ->orWhere('crm_customers.last_name', 'like', '%' . $searchTerms . '%')
                        ->orWhere('crm_customers.email', 'like', '%' . $searchTerms . '%')
                        ->orWhere('crm_customers.phone', 'like', '%' . $searchTerms . '%');
                }*/

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
            })
            ->select([
                'crm_customers.id',
                'crm_customers.clinic_id',
                \DB::raw("CONCAT(crm_customers.first_name, ' ', crm_customers.last_name) as full_name"),
                'crm_customers.email',
                'crm_customers.phone',
            ])
            ->take(50)
            ->get();

        foreach ($results as $result) {
            // Remove +1 and format phone numbers
            $phoneNumber = $result->phone;
            $phoneNumber = substr($phoneNumber, 2);
            $formattedPhoneNumber = substr($phoneNumber, 0, 3) . '-' . substr($phoneNumber, 3, 3) . '-' . substr($phoneNumber, 6);

            $result->phone = $formattedPhoneNumber;
        }

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved',
            'data' => ['data' => $results]
        ], 200);
    }

    public function addquickConsultbook(Request $request)
    {
        $leadid = $request->input('leadid');
        $consultation_booked_date = Null;
        $datetime = Null;
        $convert_to_deal = 0;

        $crmCustomer = CrmCustomer::find($leadid);
        $current_consulatation_booked_date =   $crmCustomer->consultation_booked_date;
        $current_status = $crmCustomer->status_id;

        $userid = Auth()->user()->id;

        if($crmCustomer) {
            if($request->input('consultation_booked_date') && (!empty($leadid)) ){
                $mysqlTimeMeeting = Carbon::createFromFormat('m/d/Y H:i:s A', $request->input('consultation_booked_date'));

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

            $crmCustomer->save();
            return response()->json([
                'success' => true,
                'message' => 'Quick consultation booked successfully!',
                'data' => ['data' => $crmCustomer]
            ], 200);
        }
        else{
            return response()->json([
                'success' => true,
                'message' => 'Something is wrong',
            ], 200);
        }
    }

    public function deleteLeads(Request $request)
    {
        $leadId = $request->input('id');
        $checkCrmCustomer = CrmCustomer::find($leadId);
        if(!$checkCrmCustomer){
            return response()->json([
                'success' => true,
                'message' => 'No lead found!',
            ], 200);
        }

        $crmCustomer = CrmCustomer::find($leadId)->delete();
        if($crmCustomer){

            // Delete appointments for deleted leads
            Appointment::where('crm_customer_id', $leadId)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Leads deleted successfully',
            ], 200);
        }else{
            return response()->json([
                'success' => true,
                'message' => 'Something went wrong at delete',
            ], 200);
        }

    }

    public function restoreLeads(Request $request)
    {
        $leadId = $request->input('id');

        CrmCustomer::withTrashed()->find($leadId)->restore();

        // Restore appointments for restored leads
        Appointment::where('crm_customer_id', $leadId)->onlyTrashed()->restore();


        return response()->json([
            'success' => true,
            'message' => 'Leads restored successfully',
        ], 200);

    }
}
