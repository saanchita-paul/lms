<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Http\Resources\CRTX\ReportResource;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use App\Models\ErrorLog;
use App\Models\Source;
use App\Models\User;
use App\Traits\ExceptionLog;
use Auth;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use ExceptionLog;

    public function report(Request $request)
    {

        
        $user = Auth::user();
        $clinicId = $user->managerClinics()->pluck('id');
        if($request->input('clinic_ids')){
            $clinicId = explode(',',$request->input('clinic_ids'));
        }
      
        $timezones = Clinic::whereIn('id', $clinicId)->pluck('timezone');

       $timezone = $timezones->first();
      
       if (!$timezone) {
           $timezone = 'America/New_York'; 
       }

       //$startDate = Carbon::createFromFormat('d/m/Y', $request->input('start_date'))->format('Y-m-d');
       //$endDate = Carbon::createFromFormat('d/m/Y', $request->input('end_date'))->format('Y-m-d');
       
       // Parse and convert dates from the user's timezone to UTC
       $startDate = Carbon::createFromFormat('d/m/Y', $request->input('start_date'))
           ->startOfDay() // Set the time to the start of the day
           ->setTimezone('UTC') // Convert to UTC
           ->format('Y-m-d');

          
       $ReportEnddate = $request->input('end_date').' 23:59:59';
       
       $endDate = Carbon::createFromFormat('d/m/Y H:i:s', $ReportEnddate,$timezone)
           ->setTimezone('UTC') // Convert to UTC
           ->format('Y-m-d');

        $searchKeyword = $request->input('search');
        $status_id = $request->input('status_id');
        $source_id = $request->input('source_id');
        $tagId = $request->input('tagId');
        $type = $request->input('type');
        $formType = $request->input('formType');
        $reason = $request->input('reason');
        $page = $request->input('page_no', 1);
        $numberOfRecord = $request->input('numberOfRecord');
        $perPage = $numberOfRecord;
        $offset = ($page - 1) * $perPage;
        $chartDataOb = [];
        $order = 'asc';
        $total_amounts = 0;
        $total_lifetimevalue = 0;
        $sortOrder = $request->input('sort_order', 'desc');

        $originalSearchKeyword = $searchKeyword;

        if(str_contains($searchKeyword, '-')){
            $searchKeyword = str_replace('-', '', $searchKeyword);
        }

        $sort_by = $request->input('sort_by', 'created_at');

        switch ($sort_by) {
            case 'Clinic':
                $sortBy = 'clinics.clinic_name';
                break;
            case 'First Name':
                $sortBy = 'first_name';
                break;
            case 'Last Name':
                $sortBy = 'last_name';
                break;
            case 'Phone / Form Lead':
                $sortBy = 'phone_form';
                break;
            case 'Phone':
                $sortBy = 'phone';
                break;
            case 'Source':
                $sortBy = 'sources.source_name';
                break;
            case 'Stage':
                $sortBy = 'crm_statuses.name';
                break;
            case 'Email':
                $sortBy = 'crm_customers.email';
                break;
            case 'Treatment Amount':
                $sortBy = 'crm_customers.value';
                break;
            case 'Life Time Value':
                $sortBy = 'crm_customers.lifetimevalue';
                break;
            case 'Reason':
                $sortBy = 'crm_customers.reason';
                break;
            case 'City':
                $sortBy = 'crm_customers.city';
                break;
            case 'Consultation Booked Date':
                $sortBy = 'crm_customers.consultation_booked_date';
                break;
            case 'No Showed Date':
                $sortBy = 'crm_customers.no_showed_date';
                break;
            case 'Convert Deal Date':
                $sortBy = 'crm_customers.convert_deal_date';
                break;
            case 'Lead Score':
                $sortBy = 'crm_customers.lead_score';
                break;
            case 'Phone Score':
                $sortBy = 'crm_customers.phone_score';
                break;
            case 'Email Score':
                $sortBy = 'crm_customers.email_score';
                break;
            case 'Name Score':
                $sortBy = 'crm_customers.name_score';
                break;
            case 'Tag':
                $sortBy = 'tagName';
                break;
            default:
                $sortBy = 'crm_customers.created_at';
                break;
        }

        if($request->input('order') != NULL){
            $order = $request->input('order');
        }
       
        try {
            if ($request->tabs == "treatments_sold") {
                $crmCustomerReport = ReportResource::collection(CrmCustomer::select('*','crm_customers.id as cc_id','crm_customers.created_at as crm_customers_created_at','crm_customers.email as cc_email','crm_statuses.name as crm_status_name','crm_customers.callrail_details as callrail_details',DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName'))
                    ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->leftJoin("clinics", "clinics.id", "=", "crm_customers.clinic_id")
                    ->leftJoin("sources", "sources.id", "=", "crm_customers.source_id")
                    ->leftJoin("crm_statuses", "crm_statuses.id", "=", "crm_customers.status_id")
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull("tag_leads_mapping.deleted_at");
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($type == 'lifetimevalue', function ($query) use ($tagId) {
                        $query->whereNotNull("crm_customers.lifetimevalue");
                        $query->where("crm_customers.lifetimevalue","!=","0.00");
                    })
                    ->where('won_lost', "Won")
                     ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                    $q = $query->when($source_id, function ($data1) use ($source_id) {
                        $data1->where('source_id', $source_id);
                    })
                        ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                            $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                if ($originalSearchKeyword === 'home') {
                                    $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                        $subQuery->whereNull('callrail_details')
                                            ->orWhereRaw("{$landingPageField} = ''")
                                            ->orWhereRaw("{$landingPageField} = '/'")
                                            ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                    });
                                } else {
                                    $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                }
                            })
                        ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                          ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                          ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                          ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                          ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                          ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                          ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                              $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                          })
                        ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                            $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                        })
                        ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                            $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                        });
                    });

                        $firstName = '';
                        $lastName = '';
                        $searchTermExp = explode(' ', $searchKeyword);
                        if(count($searchTermExp) > 1){
                            $firstName = $searchTermExp[0];
                            $lastName = $searchTermExp[1];
                        }

                        if(!empty($firstName) && !empty($lastName)){
                            $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                        }
                })
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('won_lost_date', [$startDate, $endDate.' 23:59:59'])
//                    ->orderBy('crm_customers.created_at',$order)
                    ->groupBy('crm_customers.id')
                    ->orderBy($sortBy, $sortOrder)
                    ->offset($offset)
                    ->limit($perPage)
                    ->distinct() // Ensure distinct results
                    ->get());

                $totalItems = CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                    ->where('won_lost', "Won")
                     ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                            ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->where(function ($query) use ($originalSearchKeyword, $searchKeyword, $status_id, $source_id,$type) {
                    $query->when($status_id, function ($data) use ($status_id) {
                        $data->where('status_id', $status_id);
                    })->when($source_id, function ($data1) use ($source_id) {
                        $data1->where('source_id', $source_id);
                    })
                    ->when($type == 'lifetimevalue', function ($query) {
                        $query->whereNotNull("crm_customers.lifetimevalue");
                        $query->where("crm_customers.lifetimevalue","!=","0.00");
                    })
                        ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                            $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                if ($originalSearchKeyword === 'home') {
                                    $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                        $subQuery->whereNull('callrail_details')
                                            ->orWhereRaw("{$landingPageField} = ''")
                                            ->orWhereRaw("{$landingPageField} = '/'")
                                            ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                    });
                                } else {
                                    $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                }
                            })
                            ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                            });

                            $firstName = '';
                            $lastName = '';
                            $searchTermExp = explode(' ', $searchKeyword);
                            if(count($searchTermExp) > 1){
                                $firstName = $searchTermExp[0];
                                $lastName = $searchTermExp[1];
                            }

                            if(!empty($firstName) && !empty($lastName)){
                                $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                            }
                    });
                })
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('won_lost_date', [$startDate, $endDate.' 23:59:59'])
                    ->count();

                $total_amounts = CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                    ->where('won_lost', "Won")
                     ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                            ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id, $type) {
                        $query->when($status_id, function ($data) use ($status_id) {
                            $data->where('status_id', $status_id);
                        })->when($source_id, function ($data1) use ($source_id) {
                            $data1->where('source_id', $source_id);
                        })
                        ->when($type == 'lifetimevalue', function ($query) {
                            $query->whereNotNull("crm_customers.lifetimevalue");
                            $query->where("crm_customers.lifetimevalue","!=","0.00");
                        })
                            ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                                $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                    $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                    if ($originalSearchKeyword === 'home') {
                                        $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                            $subQuery->whereNull('callrail_details')
                                                ->orWhereRaw("{$landingPageField} = ''")
                                                ->orWhereRaw("{$landingPageField} = '/'")
                                                ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                        });
                                    } else {
                                        $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                    }
                                })
                                    ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                    ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                        $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                        $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                        $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                    });

                                $firstName = '';
                                $lastName = '';
                                $searchTermExp = explode(' ', $searchKeyword);
                                if(count($searchTermExp) > 1){
                                    $firstName = $searchTermExp[0];
                                    $lastName = $searchTermExp[1];
                                }

                                if(!empty($firstName) && !empty($lastName)){
                                    $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                                }
                        });
                    })
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('won_lost_date', [$startDate, $endDate.' 23:59:59'])
                    ->sum('value');

                $chart_data = CrmCustomer::select(\DB::raw('DATE(crm_customers.updated_at) as date'), \DB::raw('COUNT(*) as count'))
                    ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                            ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($source_id > 0, function ($query) use ($source_id) {
                        $query->where('source_id', $source_id);
                    })->when($status_id > 0, function ($query1) use ($status_id) {
                        $query1->where('status_id', $status_id);
                    })
                    ->when($type == 'lifetimevalue', function ($query){
                        $query->whereNotNull("crm_customers.lifetimevalue");
                        $query->where("crm_customers.lifetimevalue","!=","0.00");
                    })
                    ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                        $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                            $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                            if ($originalSearchKeyword === 'home') {
                                $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                    $subQuery->whereNull('callrail_details')
                                        ->orWhereRaw("{$landingPageField} = ''")
                                        ->orWhereRaw("{$landingPageField} = '/'")
                                        ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                });
                            } else {
                                $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                            }
                        })
                            ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                            });

                        $firstName = '';
                        $lastName = '';
                        $searchTermExp = explode(' ', $searchKeyword);
                        if(count($searchTermExp) > 1){
                            $firstName = $searchTermExp[0];
                            $lastName = $searchTermExp[1];
                        }

                        if(!empty($firstName) && !empty($lastName)){
                            $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                        }
                    })
                    ->where('won_lost', "Won")
                     ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->whereBetween('won_lost_date', [$startDate, $endDate.' 23:59:59'])
                    ->orderBy('crm_customers.updated_at',$order)
                    ->groupBy(\DB::raw('DATE(crm_customers.updated_at)'))
                    ->get();
                $chartDataOb['tab'] = 'Treatments Sold';
                if($chart_data) {
                    foreach ($chart_data as $item) {
                        $itemDate = Carbon::createFromFormat('Y-m-d', $item->date)->format('M d, Y');
                        $chartDataOb['x_data'][] = $item->count;
                        $chartDataOb['y_data'][] = $itemDate;
                    }
                }
            }
            else if($request->tabs == 'engaged'){

               
                $crmCustomerReport = ReportResource::collection(
                    CrmCustomer::select(
                        '*',
                        'crm_customers.id as cc_id',
                        'crm_customers.created_at as crm_customers_created_at',
                        'crm_customers.email as cc_email',
                        'crm_statuses.name as crm_status_name',
                        'crm_customers.callrail_details as callrail_details',
                        DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName')
                    )
                    ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->leftJoin("clinics", "clinics.id", "=", "crm_customers.clinic_id")
                    ->leftJoin("sources", "sources.id", "=", "crm_customers.source_id")
                    ->leftJoin("crm_statuses", "crm_statuses.id", "=", "crm_customers.status_id")
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                            ->whereNull("tag_leads_mapping.deleted_at");
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->leftJoin("crm_chats", function ($join) {
                        $join->on("crm_chats.lead_id", "=", "crm_customers.id")
                            ->where("crm_chats.inbound", "=", 1);
                    })
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                        $q = $query->when($source_id, function ($data1) use ($source_id) {
                            $data1->where('source_id', $source_id);
                        })
                        ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                            $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                if ($originalSearchKeyword === 'home') {
                                    $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                        $subQuery->whereNull('callrail_details')
                                            ->orWhereRaw("{$landingPageField} = ''")
                                            ->orWhereRaw("{$landingPageField} = '/'")
                                            ->orWhereRaw("{$landingPageField} = 'home'");
                                    });
                                } else {
                                    $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                }
                            })
                            ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                            });
                        });
                
                        $firstName = '';
                        $lastName = '';
                        $searchTermExp = explode(' ', $searchKeyword);
                        if (count($searchTermExp) > 1) {
                            $firstName = $searchTermExp[0];
                            $lastName = $searchTermExp[1];
                        }
                
                        if (!empty($firstName) && !empty($lastName)) {
                            $q->orWhere([
                                ['first_name', 'like', '%' . $firstName . '%'],
                                ['last_name', 'like', '%' . $lastName . '%']
                            ]);
                        }
                    })
                    ->with(['clinic', 'source', 'status'])
                    ->whereBetween('crm_chats.created_at', [$startDate, $endDate . ' 23:59:59'])
                    ->groupBy('crm_customers.id')
                    ->orderBy($sortBy, $sortOrder)
                    ->offset($offset)
                    ->limit($perPage)
                    ->distinct() // Ensure distinct results
                    ->get()
                );
               
                $totalItems = CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                    ->leftJoin("crm_chats", function ($join) use ($startDate, $endDate) {
                        $join->on("crm_chats.lead_id", "=", "crm_customers.id")
                            ->where("crm_chats.inbound", "=", 1)
                            ->whereBetween("crm_chats.created_at", [$startDate, $endDate . ' 23:59:59']);
                    })
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                                ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                        $query->when($status_id, function ($data) use ($status_id) {
                            $data->where('status_id', $status_id);
                        })
                        ->when($source_id, function ($data1) use ($source_id) {
                            $data1->where('source_id', $source_id);
                        })
                        ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                            $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                if ($originalSearchKeyword === 'home') {
                                    $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                        $subQuery->whereNull('callrail_details')
                                            ->orWhereRaw("{$landingPageField} = ''")
                                            ->orWhereRaw("{$landingPageField} = '/'")
                                            ->orWhereRaw("{$landingPageField} = 'home'");
                                    });
                                } else {
                                    $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                }
                            })
                            ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                            });

                            $firstName = '';
                            $lastName = '';
                            $searchTermExp = explode(' ', $searchKeyword);
                            if (count($searchTermExp) > 1) {
                                $firstName = $searchTermExp[0];
                                $lastName = $searchTermExp[1];
                            }

                            if (!empty($firstName) && !empty($lastName)) {
                                $q->orWhere([
                                    ['first_name', 'like', '%' . $firstName . '%'],
                                    ['last_name', 'like', '%' . $lastName . '%']
                                ]);
                            }
                        });
                    })
                    ->with(['clinic', 'source', 'status'])
                    ->whereBetween('crm_chats.created_at', [$startDate, $endDate.' 23:59:59'])
                    ->orderBy('crm_customers.id') // Ensuring order by ID
                    ->distinct('crm_customers.id') // Count distinct customer IDs
                    ->count('crm_customers.id'); // Counting unique customer IDs             
                   

                    
                    $chart_data = CrmCustomer::select(
                        \DB::raw('DATE(crm_chats.created_at) as date'),
                        \DB::raw('COUNT(DISTINCT crm_customers.id) as count')
                    )
                    ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->leftJoin("crm_chats", function ($join) use ($startDate, $endDate) {
                        $join->on("crm_chats.lead_id", "=", "crm_customers.id")
                            ->where("crm_chats.inbound", "=", 1)
                            ->whereBetween("crm_chats.created_at", [$startDate, $endDate . ' 23:59:59']);
                    })
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                                ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($source_id > 0, function ($query) use ($source_id) {
                        $query->where('source_id', $source_id);
                    })
                    ->when($status_id > 0, function ($query1) use ($status_id) {
                        $query1->where('status_id', $status_id);
                    })
                    ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                        $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                            $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                            if ($originalSearchKeyword === 'home') {
                                $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                    $subQuery->whereNull('callrail_details')
                                        ->orWhereRaw("{$landingPageField} = ''")
                                        ->orWhereRaw("{$landingPageField} = '/'")
                                        ->orWhereRaw("{$landingPageField} = 'home'");
                                });
                            } else {
                                $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                            }
                        })
                        ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                        ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                            $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                        })
                        ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                            $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                        })
                        ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                            $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                        });
                
                        $firstName = '';
                        $lastName = '';
                        $searchTermExp = explode(' ', $searchKeyword);
                        if (count($searchTermExp) > 1) {
                            $firstName = $searchTermExp[0];
                            $lastName = $searchTermExp[1];
                        }
                
                        if (!empty($firstName) && !empty($lastName)) {
                            $q->orWhere([
                                ['first_name', 'like', '%' . $firstName . '%'],
                                ['last_name', 'like', '%' . $lastName . '%']
                            ]);
                        }
                    })
                    ->whereBetween('crm_chats.created_at', [$startDate, $endDate . ' 23:59:59'])
                    ->orderBy('crm_customers.id', $order)
                    ->groupBy(\DB::raw('(crm_customers.id)'))
                    ->get();
                
                $chartDataOb['tab'] = 'Engaged';
                if ($chart_data) {
                    foreach ($chart_data as $item) {
                        $itemDate = Carbon::createFromFormat('Y-m-d', $item->date)->format('M d, Y');
                        $chartDataOb['x_data'][] = $item->count;
                        $chartDataOb['y_data'][] = $itemDate;
                    }
                }

                
                
            }
            else if($request->tabs == "treatments_sold_(pms)") {
                $crmCustomerReport = ReportResource::collection(CrmCustomer::select('*','crm_customers.id as cc_id','crm_customers.created_at as crm_customers_created_at','crm_customers.email as cc_email','crm_statuses.name as crm_status_name','crm_customers.callrail_details as callrail_details',DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName'))
                    ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->leftJoin("clinics", "clinics.id", "=", "crm_customers.clinic_id")
                    ->leftJoin("sources", "sources.id", "=", "crm_customers.source_id")
                    ->leftJoin("crm_statuses", "crm_statuses.id", "=", "crm_customers.status_id")
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull("tag_leads_mapping.deleted_at");
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    //->when($type == 'lifetimevalue', function ($query) use ($tagId) {
                        ->whereNotNull("crm_customers.lifetimevalue")
                        ->where("crm_customers.lifetimevalue","!=","0.00")
                    //})
//                    ->where('won_lost', "Won")
                     ->when($formType != null, function ($query) use ($formType) {
                    $query->where("phone_form", $formType);
                })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                        $query->when($source_id, function ($data1) use ($source_id) {
                            $data1->where('source_id', $source_id);
                        })
                            ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                                $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                    $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                    if ($originalSearchKeyword === 'home') {
                                        $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                            $subQuery->whereNull('callrail_details')
                                                ->orWhereRaw("{$landingPageField} = ''")
                                                ->orWhereRaw("{$landingPageField} = '/'")
                                                ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                        });
                                    } else {
                                        $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                    }
                                })
                                    ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                    ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                        $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                        $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                        $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                    });

                                $firstName = '';
                                $lastName = '';
                                $searchTermExp = explode(' ', $searchKeyword);
                                if(count($searchTermExp) > 1){
                                    $firstName = $searchTermExp[0];
                                    $lastName = $searchTermExp[1];
                                }

                                if(!empty($firstName) && !empty($lastName)){
                                    $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                                }
                            });
                    })
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('crm_customers.created_at', [$startDate, $endDate.' 23:59:59'])
//                    ->orderBy('crm_customers.created_at',$order)
                    ->groupBy('crm_customers.id')
                    ->orderBy($sortBy, $sortOrder)
                    ->offset($offset)
                    ->limit($perPage)
                    ->distinct() // Ensure distinct results
                    ->get());

                $totalItems = CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
//                    ->where('won_lost', "Won")
                     ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                                ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id,$type) {
                        $query->when($status_id, function ($data) use ($status_id) {
                            $data->where('status_id', $status_id);
                        })->when($source_id, function ($data1) use ($source_id) {
                            $data1->where('source_id', $source_id);
                        })
//                            ->when($type == 'lifetimevalue', function ($query) {
//                                $query->whereNotNull("crm_customers.lifetimevalue");
//                                $query->where("crm_customers.lifetimevalue","!=","0.00");
//                            })
                            ->whereNotNull("crm_customers.lifetimevalue")
                            ->where("crm_customers.lifetimevalue","!=","0.00")
                            ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                                $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                    $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                    if ($originalSearchKeyword === 'home') {
                                        $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                            $subQuery->whereNull('callrail_details')
                                                ->orWhereRaw("{$landingPageField} = ''")
                                                ->orWhereRaw("{$landingPageField} = '/'")
                                                ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                        });
                                    } else {
                                        $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                    }
                                })
                                    ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                    ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                        $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                        $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                        $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                    });

                                $firstName = '';
                                $lastName = '';
                                $searchTermExp = explode(' ', $searchKeyword);
                                if(count($searchTermExp) > 1){
                                    $firstName = $searchTermExp[0];
                                    $lastName = $searchTermExp[1];
                                }

                                if(!empty($firstName) && !empty($lastName)){
                                    $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                                }
                            });


                    })
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('crm_customers.created_at', [$startDate, $endDate.' 23:59:59'])
                    ->count();

                $total_lifetimevalue = CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                    // ->where('won_lost', "Won")
                     ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                                ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id, $type) {
                        $query->when($status_id, function ($data) use ($status_id) {
                            $data->where('status_id', $status_id);
                        })->when($source_id, function ($data1) use ($source_id) {
                            $data1->where('source_id', $source_id);
                        })
//                            ->when($type == 'lifetimevalue', function ($query) {
//                                $query->whereNotNull("crm_customers.lifetimevalue");
//                                $query->where("crm_customers.lifetimevalue","!=","0.00");
//                            })
                            ->whereNotNull("crm_customers.lifetimevalue")
                            ->where("crm_customers.lifetimevalue","!=","0.00")
                            ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                                $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                    $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                    if ($originalSearchKeyword === 'home') {
                                        $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                            $subQuery->whereNull('callrail_details')
                                                ->orWhereRaw("{$landingPageField} = ''")
                                                ->orWhereRaw("{$landingPageField} = '/'")
                                                ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                        });
                                    } else {
                                        $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                    }
                                })
                                    ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                    ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                        $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                        $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                        $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                    });

                                $firstName = '';
                                $lastName = '';
                                $searchTermExp = explode(' ', $searchKeyword);
                                if(count($searchTermExp) > 1){
                                    $firstName = $searchTermExp[0];
                                    $lastName = $searchTermExp[1];
                                }

                                if(!empty($firstName) && !empty($lastName)){
                                    $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                                }
                            });
                    })
                    ->with(['clinic','source', 'status'])
                     ->whereBetween('crm_customers.created_at', [$startDate, $endDate.' 23:59:59'])
                    ->sum('lifetimevalue');

                $total_amounts = $total_lifetimevalue;

                $chart_data = CrmCustomer::select(\DB::raw('DATE(crm_customers.updated_at) as date'), \DB::raw('COUNT(*) as count'))
                    ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                                ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($source_id > 0, function ($query) use ($source_id) {
                        $query->where('source_id', $source_id);
                    })->when($status_id > 0, function ($query1) use ($status_id) {
                        $query1->where('status_id', $status_id);
                    })
//                    ->when($type == 'lifetimevalue', function ($query){
//                        $query->whereNotNull("crm_customers.lifetimevalue");
//                        $query->where("crm_customers.lifetimevalue","!=","0.00");
//                    })
                    ->whereNotNull("crm_customers.lifetimevalue")
                    ->where("crm_customers.lifetimevalue","!=","0.00")
                    ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                        $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                            $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                            if ($originalSearchKeyword === 'home') {
                                $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                    $subQuery->whereNull('callrail_details')
                                        ->orWhereRaw("{$landingPageField} = ''")
                                        ->orWhereRaw("{$landingPageField} = '/'")
                                        ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                });
                            } else {
                                $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                            }
                        })
                            ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                            });

                        $firstName = '';
                        $lastName = '';
                        $searchTermExp = explode(' ', $searchKeyword);
                        if(count($searchTermExp) > 1){
                            $firstName = $searchTermExp[0];
                            $lastName = $searchTermExp[1];
                        }

                        if(!empty($firstName) && !empty($lastName)){
                            $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                        }
                    })
//                    ->where('won_lost', "Won")
                     ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->whereBetween('crm_customers.created_at', [$startDate, $endDate.' 23:59:59'])
                    ->orderBy('crm_customers.updated_at',$order)
                    ->groupBy(\DB::raw('DATE(crm_customers.updated_at)'))
                    ->get();
                $chartDataOb['tab'] = 'Treatments Sold (PMS)';
                if($chart_data) {
                    foreach ($chart_data as $item) {
                        $itemDate = Carbon::createFromFormat('Y-m-d', $item->date)->format('M d, Y');
                        $chartDataOb['x_data'][] = $item->count;
                        $chartDataOb['y_data'][] = $itemDate;
                    }
                }
            }
            elseif ($request->tabs == "new_leads") {
              //  DB::enableQueryLog();
              $crmCustomerReport = ReportResource::collection(
                CrmCustomer::select(
                    'crm_customers.*',
                    'crm_customers.id as cc_id',
                    'crm_customers.created_at as crm_customers_created_at',
                    'crm_customers.email as cc_email',
                    'crm_statuses.name as crm_status_name',
                    'crm_customers.callrail_details as callrail_details',
                    DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName') // Concatenate distinct tag names
                )
                ->when($formType != null, function ($query) use ($formType) {
                    $query->where("phone_form", $formType);
                })
                ->when($reason != null, function ($query) use ($reason) {
                    $query->where("reason", $reason);
                })
                ->whereIn('crm_customers.clinic_id', $clinicId)
                ->leftJoin("clinics", "clinics.id", "=", "crm_customers.clinic_id")
                ->leftJoin("sources", "sources.id", "=", "crm_customers.source_id")
                ->leftJoin("crm_statuses", "crm_statuses.id", "=", "crm_customers.status_id")
                ->leftJoin("tag_leads_mapping", function ($join) {
                    $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                    ->whereNull("tag_leads_mapping.deleted_at");
                })
                ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id") // Add join with tags table
                ->when($tagId != 0, function ($query) use ($tagId) {
                    $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                })
                ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                    $query->when($status_id, function ($data) use ($status_id) {
                        if ($status_id === "treatments_sold") {
                            $data->where('won_lost', 'Won');
                        } else {
                            $data->where('status_id', $status_id);
                        }
                    })->when($source_id, function ($data1) use ($source_id) {
                        $data1->where('source_id', $source_id);
                    })
                        ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                            $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                if ($originalSearchKeyword === 'home') {
                                    $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                        $subQuery->whereNull('callrail_details')
                                            ->orWhereRaw("{$landingPageField} = ''")
                                            ->orWhereRaw("{$landingPageField} = '/'")
                                            ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                    });
                                } else {
                                    $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                }
                            })
                                ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                    $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                })
                                ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                    $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                })
                                ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                    $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                });

                            $firstName = '';
                            $lastName = '';
                            $searchTermExp = explode(' ', $searchKeyword);
                            if(count($searchTermExp) > 1){
                                $firstName = $searchTermExp[0];
                                $lastName = $searchTermExp[1];
                            }

                            if(!empty($firstName) && !empty($lastName)){
                                $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                            }
                    });
                })
                ->with(['clinic', 'source', 'status'])
                ->when($status_id == 18, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('crm_customers.updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                })
                ->when($status_id === "treatments_sold", function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('crm_customers.won_lost_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                })
                ->when($status_id == 0 || !in_array($status_id, [18, "treatments_sold"]), function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('crm_customers.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                })  
                ->groupBy('crm_customers.id') // Group by ID to ensure uniqueness
                ->orderBy($sortBy, $sortOrder)
                ->offset($offset)
                ->limit($perPage)
                ->distinct() // Ensure distinct results
                ->get()
            );

                    //  dd(DB::getQueryLog());
                   $totalItems = CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                   ->when($tagId != 0, function ($query) use ($tagId) {
                    $query->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull("tag_leads_mapping.deleted_at");
                    })->where("tag_leads_mapping.tag_id", "=", $tagId);
                })
                       ->when($formType != null, function ($query) use ($formType) {
                           $query->where("phone_form", $formType);
                       })
                       ->when($reason != null, function ($query) use ($reason) {
                           $query->where("reason", $reason);
                       })

                   ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id, $tagId) {
                       $query->when($status_id, function ($data) use ($status_id) {
                        if ($status_id === "treatments_sold") {
                            $data->where('won_lost', 'Won');
                        } else {
                            $data->where('status_id', $status_id);
                        }
                       })->when($source_id, function ($data1) use ($source_id) {
                           $data1->where('source_id', $source_id);
                       })

                           ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                               $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                   $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                   if ($originalSearchKeyword === 'home') {
                                       $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                           $subQuery->whereNull('callrail_details')
                                               ->orWhereRaw("{$landingPageField} = ''")
                                               ->orWhereRaw("{$landingPageField} = '/'")
                                               ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                       });
                                   } else {
                                       $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                   }
                               })
                                   ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                   ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                   ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                   ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                   ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                   ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                   ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                       $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                   })
                                   ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                       $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                   })
                                   ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                       $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                   });

                               $firstName = '';
                               $lastName = '';
                               $searchTermExp = explode(' ', $searchKeyword);
                               if(count($searchTermExp) > 1){
                                   $firstName = $searchTermExp[0];
                                   $lastName = $searchTermExp[1];
                               }

                               if(!empty($firstName) && !empty($lastName)){
                                   $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                               }
                       });
                   })
                   ->when($status_id == 18, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('crm_customers.updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    })
                    ->when($status_id === "treatments_sold", function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('crm_customers.won_lost_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    })
                    ->when($status_id == 0 || !in_array($status_id, [18, "treatments_sold"]), function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('crm_customers.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    })->count();

                    $total_amounts = CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                      ->when($tagId != 0, function ($query) use ($tagId) {
                       $query->leftJoin("tag_leads_mapping", function ($join) {
                           $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                           ->whereNull("tag_leads_mapping.deleted_at");
                       })->where("tag_leads_mapping.tag_id", "=", $tagId);
                   })
                          ->when($formType != null, function ($query) use ($formType) {
                              $query->where("phone_form", $formType);
                          })
                          ->when($reason != null, function ($query) use ($reason) {
                              $query->where("reason", $reason);
                          })
   
                      ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id, $tagId) {
                          $query->when($status_id, function ($data) use ($status_id) {
                            if ($status_id === "treatments_sold") {
                                $data->where('won_lost', 'Won');
                            } else {
                               $data->where('status_id', $status_id);
                           }
                          })->when($source_id, function ($data1) use ($source_id) {
                              $data1->where('source_id', $source_id);
                          })
   
                              ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                                  $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                      $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                      if ($originalSearchKeyword === 'home') {
                                          $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                              $subQuery->whereNull('callrail_details')
                                                  ->orWhereRaw("{$landingPageField} = ''")
                                                  ->orWhereRaw("{$landingPageField} = '/'")
                                                  ->orWhereRaw("{$landingPageField} = 'home'");
   //                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                          });
                                      } else {
                                          $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                      }
                                  })
                                      ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                      ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                      ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                      ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                      ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                      ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                      ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                          $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                      })
                                      ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                          $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                      })
                                      ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                          $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                      });
   
                                  $firstName = '';
                                  $lastName = '';
                                  $searchTermExp = explode(' ', $searchKeyword);
                                  if(count($searchTermExp) > 1){
                                      $firstName = $searchTermExp[0];
                                      $lastName = $searchTermExp[1];
                                  }
   
                                  if(!empty($firstName) && !empty($lastName)){
                                      $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                                  }
                          });
                      })
                      ->when($status_id == 18, function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('crm_customers.updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    })
                    ->when($status_id === "treatments_sold", function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('crm_customers.won_lost_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    })
                    ->when($status_id == 0 || !in_array($status_id, [18, "treatments_sold"]), function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('crm_customers.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    })->sum('value');

                $chart_data = CrmCustomer::select(
                    \DB::raw('DATE(crm_customers.' . ($status_id == 18 
                        ? 'updated_at' 
                        : ($status_id === "treatments_sold" ? 'won_lost_date' : 'created_at')) . ') as date'),
                    \DB::raw('COUNT(*) as count')
                )
                    ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                            ->whereNull("tag_leads_mapping.deleted_at");
                        })->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($source_id, function ($query) use ($source_id) {
                        $query->where('source_id', $source_id);
                    })->when($status_id, function ($query1) use ($status_id) {
                        if ($status_id === "treatments_sold") {
                            $query1->where('won_lost', 'Won');
                        } else {
                            $query1->where('status_id', $status_id);
                        }
                    })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                        $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                            $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                            if ($originalSearchKeyword === 'home') {
                                $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                    $subQuery->whereNull('callrail_details')
                                        ->orWhereRaw("{$landingPageField} = ''")
                                        ->orWhereRaw("{$landingPageField} = '/'")
                                        ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                });
                            } else {
                                $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                            }
                        })
                            ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                            });

                        $firstName = '';
                        $lastName = '';
                        $searchTermExp = explode(' ', $searchKeyword);
                        if(count($searchTermExp) > 1){
                            $firstName = $searchTermExp[0];
                            $lastName = $searchTermExp[1];
                        }

                        if(!empty($firstName) && !empty($lastName)){
                            $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                        }
                    })
                    ->when($status_id == 18, function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('crm_customers.updated_at', [$startDate, $endDate . ' 23:59:59']);
                    })
                    ->when($status_id === "treatments_sold", function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('crm_customers.won_lost_date', [$startDate, $endDate . ' 23:59:59']);
                    })
                    ->when($status_id == 0 || !in_array($status_id, [18, "treatments_sold"]), function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('crm_customers.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    })
                    ->orderBy('crm_customers.' . ($status_id === "18" 
                        ? 'updated_at' 
                        : ($status_id === "treatments_sold" ? 'won_lost_date' : 'created_at')), $order)
                    ->groupBy(\DB::raw('DATE(' . ($status_id === "18" 
                        ? 'updated_at' 
                        : ($status_id === "treatments_sold" ? 'won_lost_date' : 'created_at')) . ')'))
                    ->get();
                $chartDataOb['tab'] = 'New Leads';
                if($chart_data) {
                    foreach ($chart_data as $item) {
                        $itemDate = Carbon::createFromFormat('Y-m-d', $item->date)->format('M d, Y');
                        $chartDataOb['x_data'][] = $item->count;
                        $chartDataOb['y_data'][] = $itemDate;
                    }
                }
            }
            elseif ($request->tabs  == "consultations_booked") {
                //  DB::enableQueryLog();
                $crmCustomerReport = ReportResource::collection(CrmCustomer::select('*', 'crm_customers.id as cc_id', 'crm_customers.created_at as crm_customers_created_at', 'crm_customers.email as cc_email', 'crm_statuses.name as crm_status_name',
                'crm_customers.callrail_details as callrail_details',
               DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName')
                )
                ->whereIn('crm_customers.clinic_id', $clinicId)
                ->leftJoin("clinics", "clinics.id", "=", "crm_customers.clinic_id")
                ->leftJoin("sources", "sources.id", "=", "crm_customers.source_id")
                ->leftJoin("crm_statuses", "crm_statuses.id", "=", "crm_customers.status_id")
                ->leftJoin("tag_leads_mapping", function ($join) {
                    $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                    ->whereNull("tag_leads_mapping.deleted_at");
                })
                ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                ->when($tagId != 0, function ($query) use ($tagId) {
                    $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                })
                ->when($formType != null, function ($query) use ($formType) {
                    $query->where("phone_form", $formType);
                })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                // ->when($tagId != 0, function ($query) use ($tagId) {
                //     $query->leftJoin("tag_leads_mapping", function ($join) {
                //         $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                //         ->whereNull('tag_leads_mapping.deleted_at');
                //     })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                //         ->where("tag_leads_mapping.tag_id", "=", $tagId);
                // })
                ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                    $query->when($status_id, function ($data) use ($status_id) {
                        $data->where('status_id', $status_id);
                    })->when($source_id, function ($data1) use ($source_id) {
                        $data1->where('source_id', $source_id);
                    })
                        ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                            $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                if ($originalSearchKeyword === 'home') {
                                    $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                        $subQuery->whereNull('callrail_details')
                                            ->orWhereRaw("{$landingPageField} = ''")
                                            ->orWhereRaw("{$landingPageField} = '/'")
                                            ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                    });
                                } else {
                                    $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                }
                            })
                                ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                    $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                })
                                ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                    $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                })
                                ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                    $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                });

                            $firstName = '';
                            $lastName = '';
                            $searchTermExp = explode(' ', $searchKeyword);
                            if(count($searchTermExp) > 1){
                                $firstName = $searchTermExp[0];
                                $lastName = $searchTermExp[1];
                            }

                            if(!empty($firstName) && !empty($lastName)){
                                $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                            }
                    });
                })
                ->with(['clinic','source', 'status'])
                ->whereBetween('crm_customers.convert_deal_date', [$startDate, $endDate.' 23:59:59'])
                ->groupBy('crm_customers.id')
                ->orderBy($sortBy, $sortOrder)
                ->offset($offset)
                ->limit($perPage)
                ->distinct()
                ->get());
                 //   dd(DB::getQueryLog());

                $totalItems = CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                ->when($tagId != 0, function ($query) use ($tagId) {
                    $query->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull('tag_leads_mapping.deleted_at');
                    })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                        ->where("tag_leads_mapping.tag_id", "=", $tagId);
                })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                    $query->when($status_id, function ($data) use ($status_id) {
                        $data->where('status_id', $status_id);
                    })->when($source_id, function ($data1) use ($source_id) {
                        $data1->where('source_id', $source_id);
                    })
                        ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                            $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                if ($originalSearchKeyword === 'home') {
                                    $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                        $subQuery->whereNull('callrail_details')
                                            ->orWhereRaw("{$landingPageField} = ''")
                                            ->orWhereRaw("{$landingPageField} = '/'")
                                            ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                    });
                                } else {
                                    $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                }
                            })
                          ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                          ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                          ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                          ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                           ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                            });

                            $firstName = '';
                            $lastName = '';
                            $searchTermExp = explode(' ', $searchKeyword);
                            if(count($searchTermExp) > 1){
                                $firstName = $searchTermExp[0];
                                $lastName = $searchTermExp[1];
                            }

                            if(!empty($firstName) && !empty($lastName)){
                                $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                            }
                    });
                })
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('crm_customers.convert_deal_date', [$startDate, $endDate.' 23:59:59'])
                    ->count();

                $chart_data = CrmCustomer::select(\DB::raw('DATE(crm_customers.convert_deal_date) as date'), \DB::raw('COUNT(*) as count'))
                    ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                            ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($source_id, function ($query) use ($source_id) {
                        $query->where('source_id', $source_id);
                    })->when($status_id, function ($query1) use ($status_id) {
                        $query1->where('status_id', $status_id);
                    })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                        $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                            $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                            if ($originalSearchKeyword === 'home') {
                                $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                    $subQuery->whereNull('callrail_details')
                                        ->orWhereRaw("{$landingPageField} = ''")
                                        ->orWhereRaw("{$landingPageField} = '/'")
                                        ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                });
                            } else {
                                $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                            }
                        })
                            ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                            });

                        $firstName = '';
                        $lastName = '';
                        $searchTermExp = explode(' ', $searchKeyword);
                        if(count($searchTermExp) > 1){
                            $firstName = $searchTermExp[0];
                            $lastName = $searchTermExp[1];
                        }

                        if(!empty($firstName) && !empty($lastName)){
                            $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                        }
                    })
                    ->whereBetween('crm_customers.convert_deal_date', [$startDate, $endDate.' 23:59:59'])
                    ->orderBy('crm_customers.convert_deal_date',$order)
                    ->groupBy('date')
                    ->get();
                $chartDataOb['tab'] = 'Consultations Booked';
                if($chart_data) {
                    foreach ($chart_data as $item) {
                        $itemDate = Carbon::createFromFormat('Y-m-d', $item->date)->format('M d, Y');
                        $chartDataOb['x_data'][] = $item->count;
                        $chartDataOb['y_data'][] = $itemDate;
                    }
                }
            }
            elseif ($request->tabs == "consultations_showed") {
                //Results
                $crmCustomerReport =  ReportResource::collection(CrmCustomer::select('*','crm_customers.id as cc_id','crm_customers.created_at as crm_customers_created_at','crm_customers.email as cc_email','crm_statuses.name as crm_status_name',
                'crm_customers.callrail_details as callrail_details',
                DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName')
                )
                    ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->leftJoin("clinics", "clinics.id", "=", "crm_customers.clinic_id")
                    ->leftJoin("sources", "sources.id", "=", "crm_customers.source_id")
                    ->leftJoin("crm_statuses", "crm_statuses.id", "=", "crm_customers.status_id")
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull("tag_leads_mapping.deleted_at");
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                    $query->when($source_id, function ($data1) use ($source_id) {
                        $data1->where('source_id', $source_id);
                    })
                    ->where('status_id',15)
                        ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                            $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                if ($originalSearchKeyword === 'home') {
                                    $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                        $subQuery->whereNull('callrail_details')
                                            ->orWhereRaw("{$landingPageField} = ''")
                                            ->orWhereRaw("{$landingPageField} = '/'")
                                            ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                    });
                                } else {
                                    $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                }
                            })
                                ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                    $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                })
                                ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                    $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                })
                                ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                    $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                });

                            $firstName = '';
                            $lastName = '';
                            $searchTermExp = explode(' ', $searchKeyword);
                            if(count($searchTermExp) > 1){
                                $firstName = $searchTermExp[0];
                                $lastName = $searchTermExp[1];
                            }

                            if(!empty($firstName) && !empty($lastName)){
                                $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                            }
                    });
                })
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('crm_customers.consultation_booked_date', [$startDate, $endDate.' 23:59:59'])
                    ->groupBy('crm_customers.id')
                    ->orderBy($sortBy, $sortOrder)
                    ->offset($offset)
                    ->limit($perPage)
                    ->distinct() // Ensure distinct results
                    ->get());

                //Results counts
                $totalItems = CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                ->when($tagId != 0, function ($query) use ($tagId) {
                    $query->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull('tag_leads_mapping.deleted_at');
                    })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                        ->where("tag_leads_mapping.tag_id", "=", $tagId);
                })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                    $query->when($source_id, function ($data1) use ($source_id) {
                        $data1->where('source_id', $source_id);
                    })
                    ->where('status_id',15)
                        ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                            $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                if ($originalSearchKeyword === 'home') {
                                    $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                        $subQuery->whereNull('callrail_details')
                                            ->orWhereRaw("{$landingPageField} = ''")
                                            ->orWhereRaw("{$landingPageField} = '/'")
                                            ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                    });
                                } else {
                                    $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                }
                            })
                                ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                    $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                })
                                ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                    $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                })
                                ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                    $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                });

                            $firstName = '';
                            $lastName = '';
                            $searchTermExp = explode(' ', $searchKeyword);
                            if(count($searchTermExp) > 1){
                                $firstName = $searchTermExp[0];
                                $lastName = $searchTermExp[1];
                            }

                            if(!empty($firstName) && !empty($lastName)){
                                $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                            }
                    });
                })
                ->with(['clinic','source', 'status'])
                ->whereBetween('crm_customers.consultation_booked_date', [$startDate, $endDate.' 23:59:59'])
                ->count();

                //Results Charts
                $chart_data = CrmCustomer::select(\DB::raw('DATE(crm_customers.consultation_booked_date) as date'), \DB::raw('COUNT(*) as count'))
                            ->whereIn('crm_customers.clinic_id', $clinicId)
                            ->when($tagId != 0, function ($query) use ($tagId) {
                                $query->leftJoin("tag_leads_mapping", function ($join) {
                                    $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                                    ->whereNull('tag_leads_mapping.deleted_at');
                                })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                                    ->where("tag_leads_mapping.tag_id", "=", $tagId);
                            })
                            ->when($formType != null, function ($query) use ($formType) {
                                $query->where("phone_form", $formType);
                            })
                            ->when($reason != null, function ($query) use ($reason) {
                                $query->where("reason", $reason);
                            })
                            ->when($source_id, function ($query) use ($source_id) {
                                $query->where('source_id', $source_id);
                            })
                    ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                        $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                            $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                            if ($originalSearchKeyword === 'home') {
                                $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                    $subQuery->whereNull('callrail_details')
                                        ->orWhereRaw("{$landingPageField} = ''")
                                        ->orWhereRaw("{$landingPageField} = '/'")
                                        ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                });
                            } else {
                                $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                            }
                        })
                            ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                            });

                            $firstName = '';
                            $lastName = '';
                            $searchTermExp = explode(' ', $searchKeyword);
                            if(count($searchTermExp) > 1){
                                $firstName = $searchTermExp[0];
                                $lastName = $searchTermExp[1];
                            }

                            if(!empty($firstName) && !empty($lastName)){
                                $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                            }

                        })
                            ->where('status_id',15)
                            ->whereBetween('crm_customers.consultation_booked_date', [$startDate, $endDate.' 23:59:59'])
                            ->groupBy(\DB::raw('DATE(crm_customers.consultation_booked_date)'))
                            ->orderBy('crm_customers.consultation_booked_date', 'asc')
                            ->get();

                $chartDataOb['tab'] = 'Consultations Showed';
                if($chart_data) {
                    foreach ($chart_data as $item) {
                        $itemDate = Carbon::createFromFormat('Y-m-d', $item->date)->format('M d, Y');
                        $chartDataOb['x_data'][] = $item->count;
                        $chartDataOb['y_data'][] = $itemDate;
                    }
                }

            }
            elseif ($request->tabs == "treatments_presented") {
                //Results
                $crmCustomerReport =  ReportResource::collection(CrmCustomer::select('*','crm_customers.id as cc_id','crm_customers.created_at as crm_customers_created_at','crm_customers.email as cc_email','crm_statuses.name as crm_status_name',
                'crm_customers.callrail_details as callrail_details',
                 DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName'))
                ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->leftJoin("clinics", "clinics.id", "=", "crm_customers.clinic_id")
                    ->leftJoin("sources", "sources.id", "=", "crm_customers.source_id")
                    ->leftJoin("crm_statuses", "crm_statuses.id", "=", "crm_customers.status_id")
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull("tag_leads_mapping.deleted_at");
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->whereNull('won_lost')
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                        $query->when($source_id, function ($data1) use ($source_id) {
                            $data1->where('source_id', $source_id);
                        })
                            ->where('status_id',15)
                            ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                                $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                    $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                    if ($originalSearchKeyword === 'home') {
                                        $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                            $subQuery->whereNull('callrail_details')
                                                ->orWhereRaw("{$landingPageField} = ''")
                                                ->orWhereRaw("{$landingPageField} = '/'")
                                                ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                        });
                                    } else {
                                        $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                    }
                                })
                                    ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                    ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                        $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                        $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                        $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                    });

                                $firstName = '';
                                $lastName = '';
                                $searchTermExp = explode(' ', $searchKeyword);
                                if(count($searchTermExp) > 1){
                                    $firstName = $searchTermExp[0];
                                    $lastName = $searchTermExp[1];
                                }

                                if(!empty($firstName) && !empty($lastName)){
                                    $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                                }
                            });
                    })
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('crm_customers.updated_at', [$startDate, $endDate.' 23:59:59'])
                    ->groupBy('crm_customers.id')
                    ->orderBy($sortBy, $sortOrder)
                    ->offset($offset)
                    ->limit($perPage)
                    ->distinct() // Ensure distinct results
                    ->get());

                //Results counts
                $totalItems = CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                    ->whereNull('won_lost')
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                            ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                        $query->when($source_id, function ($data1) use ($source_id) {
                            $data1->where('source_id', $source_id);
                        })
                        ->where('status_id',15)
                            ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                                $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                    $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                    if ($originalSearchKeyword === 'home') {
                                        $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                            $subQuery->whereNull('callrail_details')
                                                ->orWhereRaw("{$landingPageField} = ''")
                                                ->orWhereRaw("{$landingPageField} = '/'")
                                                ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                        });
                                    } else {
                                        $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                    }
                                })
                                    ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('email', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                    ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                        $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                        $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                        $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                    });

                                $firstName = '';
                                $lastName = '';
                                $searchTermExp = explode(' ', $searchKeyword);
                                if(count($searchTermExp) > 1){
                                    $firstName = $searchTermExp[0];
                                    $lastName = $searchTermExp[1];
                                }

                                if(!empty($firstName) && !empty($lastName)){
                                    $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                                }
                        });
                })->with(['clinic','source', 'status'])
                    ->whereBetween('crm_customers.updated_at', [$startDate, $endDate.' 23:59:59'])->count();

                $total_amounts = CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                    ->whereNull('won_lost')
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                            ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                        $query->when($source_id, function ($data1) use ($source_id) {
                            $data1->where('source_id', $source_id);
                        })
                            ->where('status_id',15)
                            ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                                $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                    $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                    if ($originalSearchKeyword === 'home') {
                                        $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                            $subQuery->whereNull('callrail_details')
                                                ->orWhereRaw("{$landingPageField} = ''")
                                                ->orWhereRaw("{$landingPageField} = '/'")
                                                ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                        });
                                    } else {
                                        $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                    }
                                })
                                    ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('email', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                    ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                        $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                        $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                        $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                    });

                                $firstName = '';
                                $lastName = '';
                                $searchTermExp = explode(' ', $searchKeyword);
                                if(count($searchTermExp) > 1){
                                    $firstName = $searchTermExp[0];
                                    $lastName = $searchTermExp[1];
                                }

                                if(!empty($firstName) && !empty($lastName)){
                                    $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                                }
                            });
                    })->with(['clinic','source', 'status'])
                    ->whereBetween('crm_customers.updated_at', [$startDate, $endDate.' 23:59:59'])->sum('value');

                //Results Charts
                $chart_data = CrmCustomer::select(\DB::raw('DATE(crm_customers.updated_at) as date'), \DB::raw('COUNT(*) as count'))
                    ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->whereNull('won_lost')
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                            ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->when($source_id, function ($query) use ($source_id) {
                        $query->where('source_id', $source_id);
                    })
                    ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                        $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                            $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                            if ($originalSearchKeyword === 'home') {
                                $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                    $subQuery->whereNull('callrail_details')
                                        ->orWhereRaw("{$landingPageField} = ''")
                                        ->orWhereRaw("{$landingPageField} = '/'")
                                        ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                });
                            } else {
                                $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                            }
                        })
                            ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('email', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                            });

                        $firstName = '';
                        $lastName = '';
                        $searchTermExp = explode(' ', $searchKeyword);
                        if(count($searchTermExp) > 1){
                            $firstName = $searchTermExp[0];
                            $lastName = $searchTermExp[1];
                        }

                        if(!empty($firstName) && !empty($lastName)){
                            $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                        }
                    })
                    ->where('status_id',15)
                    ->whereBetween('crm_customers.updated_at', [$startDate, $endDate.' 23:59:59'])
                    ->groupBy(\DB::raw('DATE(crm_customers.updated_at)'))
                    ->orderBy('crm_customers.updated_at', 'asc')
                    ->get();

                $chartDataOb['tab'] = 'Treatments Presented';
                if($chart_data) {
                    foreach ($chart_data as $item) {
                        $itemDate = Carbon::createFromFormat('Y-m-d', $item->date)->format('M d, Y');
                        $chartDataOb['x_data'][] = $item->count;
                        $chartDataOb['y_data'][] = $itemDate;
                    }
                }

            }
            elseif ($request->tabs == "consultations_follow_up") {
                //Results
                $crmCustomerReport =  ReportResource::collection(CrmCustomer::select('*','crm_customers.id as cc_id','crm_customers.created_at as crm_customers_created_at','crm_customers.email as cc_email','crm_statuses.name as crm_status_name',
                'crm_customers.callrail_details as callrail_details',
                DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName'))
                ->
                whereIn('crm_customers.clinic_id', $clinicId)
                    ->leftJoin("clinics", "clinics.id", "=", "crm_customers.clinic_id")
                    ->leftJoin("sources", "sources.id", "=", "crm_customers.source_id")
                    ->leftJoin("crm_statuses", "crm_statuses.id", "=", "crm_customers.status_id")
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull("tag_leads_mapping.deleted_at");
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->whereNull('won_lost')
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where('crm_customers.status_id', '6')
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                        $query->when($source_id, function ($data1) use ($source_id) {
                            $data1->where('source_id', $source_id);
                        })
                            ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                                $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                    $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                    if ($originalSearchKeyword === 'home') {
                                        $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                            $subQuery->whereNull('callrail_details')
                                                ->orWhereRaw("{$landingPageField} = ''")
                                                ->orWhereRaw("{$landingPageField} = '/'")
                                                ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                        });
                                    } else {
                                        $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                    }
                                })
                                    ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                    ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                        $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                        $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                        $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                    });

                                $firstName = '';
                                $lastName = '';
                                $searchTermExp = explode(' ', $searchKeyword);
                                if(count($searchTermExp) > 1){
                                    $firstName = $searchTermExp[0];
                                    $lastName = $searchTermExp[1];
                                }

                                if(!empty($firstName) && !empty($lastName)){
                                    $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                                }
                        });
                    })
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('crm_customers.updated_at', [$startDate, $endDate.' 23:59:59'])
                    ->groupBy('crm_customers.id')
                    ->orderBy($sortBy, $sortOrder)
                    ->offset($offset)
                    ->limit($perPage)
                    ->distinct() // Ensure distinct results
                    ->get());

                //Results counts
                $totalItems = CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                    ->whereNull('won_lost')
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                            ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                   ->where('crm_customers.status_id', '6')
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                    $query->when($source_id, function ($data1) use ($source_id) {
                        $data1->where('source_id', $source_id);
                    })
                        ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                            $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                if ($originalSearchKeyword === 'home') {
                                    $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                        $subQuery->whereNull('callrail_details')
                                            ->orWhereRaw("{$landingPageField} = ''")
                                            ->orWhereRaw("{$landingPageField} = '/'")
                                            ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                    });
                                } else {
                                    $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                }
                            })
                                ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                    $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                })
                                ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                    $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                })
                                ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                    $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                });

                            $firstName = '';
                            $lastName = '';
                            $searchTermExp = explode(' ', $searchKeyword);
                            if(count($searchTermExp) > 1){
                                $firstName = $searchTermExp[0];
                                $lastName = $searchTermExp[1];
                            }

                            if(!empty($firstName) && !empty($lastName)){
                                $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                            }
                    });
                })
                ->with(['clinic','source', 'status'])
                ->whereBetween('crm_customers.updated_at', [$startDate, $endDate.' 23:59:59'])
                ->count();

                //Results Charts
                $chart_data = CrmCustomer::select(\DB::raw('DATE(crm_customers.updated_at) as date'), \DB::raw('COUNT(*) as count'))
                    ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                            ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->whereNull('won_lost')
                    ->where('crm_customers.status_id', '6')
                    ->when($source_id, function ($query) use ($source_id) {
                        $query->where('source_id', $source_id);
                    })
                    ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                        $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                            $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                            if ($originalSearchKeyword === 'home') {
                                $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                    $subQuery->whereNull('callrail_details')
                                        ->orWhereRaw("{$landingPageField} = ''")
                                        ->orWhereRaw("{$landingPageField} = '/'")
                                        ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                });
                            } else {
                                $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                            }
                        })
                            ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                            });

                        $firstName = '';
                        $lastName = '';
                        $searchTermExp = explode(' ', $searchKeyword);
                        if(count($searchTermExp) > 1){
                            $firstName = $searchTermExp[0];
                            $lastName = $searchTermExp[1];
                        }

                        if(!empty($firstName) && !empty($lastName)){
                            $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                        }
                    })
                    ->whereBetween('crm_customers.updated_at', [$startDate, $endDate.' 23:59:59'])
                    ->groupBy(\DB::raw('DATE(crm_customers.updated_at)'))
                    ->orderBy('crm_customers.updated_at', 'asc')
                    ->get();

                $chartDataOb['tab'] = 'Consultations Follow Up';
                if($chart_data) {
                    foreach ($chart_data as $item) {
                        $itemDate = Carbon::createFromFormat('Y-m-d', $item->date)->format('M d, Y');
                        $chartDataOb['x_data'][] = $item->count;
                        $chartDataOb['y_data'][] = $itemDate;
                    }
                }
            }
            elseif ($request->tabs  == "nurturing") {
                $crmCustomerReport = ReportResource::collection(CrmCustomer::select('*', 'crm_customers.id as cc_id', 'crm_customers.created_at as crm_customers_created_at', 'crm_customers.email as cc_email', 'crm_statuses.name as crm_status_name',
                    'crm_customers.callrail_details as callrail_details',
                    DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName')
                )
                    ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->where('automation_logs.status_id', 17)
                    ->where('automation_logs.dayinterval', '1')
                    ->leftJoin("automation_logs", "automation_logs.lead_id", "=", "crm_customers.id")
                    ->leftJoin("clinics", "clinics.id", "=", "crm_customers.clinic_id")
                    ->leftJoin("sources", "sources.id", "=", "crm_customers.source_id")
                    ->leftJoin("crm_statuses", "crm_statuses.id", "=", "crm_customers.status_id")
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull("tag_leads_mapping.deleted_at");
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                        $query->when($status_id, function ($data) use ($status_id) {
                            $data->where('status_id', $status_id);
                        })->when($source_id, function ($data1) use ($source_id) {
                            $data1->where('source_id', $source_id);
                        })
                            ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                                $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                    $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                    if ($originalSearchKeyword === 'home') {
                                        $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                            $subQuery->whereNull('callrail_details')
                                                ->orWhereRaw("{$landingPageField} = ''")
                                                ->orWhereRaw("{$landingPageField} = '/'")
                                                ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                        });
                                    } else {
                                        $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                    }
                                })
                                    ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                    ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                        $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                        $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                        $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                    });

                                $firstName = '';
                                $lastName = '';
                                $searchTermExp = explode(' ', $searchKeyword);
                                if(count($searchTermExp) > 1){
                                    $firstName = $searchTermExp[0];
                                    $lastName = $searchTermExp[1];
                                }

                                if(!empty($firstName) && !empty($lastName)){
                                    $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                                }
                            });
                    })
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('automation_logs.created_at', [$startDate, $endDate.' 23:59:59'])
                    ->groupBy('crm_customers.id')
                    ->orderBy($sortBy, $sortOrder)
                    ->offset($offset)
                    ->limit($perPage)
                    ->distinct()
                    ->get());
                    
                 $totalItems = CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                 ->where('automation_logs.status_id', 17)
                 ->where('automation_logs.dayinterval', '1')
                ->leftJoin("automation_logs", "automation_logs.lead_id", "=", "crm_customers.id") // New join
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                                ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query) use ($searchKeyword, $originalSearchKeyword, $status_id, $source_id) {
                        $query->when($status_id, function ($data) use ($status_id) {
                            $data->where('status_id', $status_id);
                        })->when($source_id, function ($data1) use ($source_id) {
                            $data1->where('source_id', $source_id);
                        })
                            ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                                $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                                    $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                                    if ($originalSearchKeyword === 'home') {
                                        $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                            $subQuery->whereNull('callrail_details')
                                                ->orWhereRaw("{$landingPageField} = ''")
                                                ->orWhereRaw("{$landingPageField} = '/'")
                                                ->orWhereRaw("{$landingPageField} = 'home'");
//                                            ->orWhereRaw("{$landingPageField} LIKE ?", ['%/']);
                                        });
                                    } else {
                                        $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                                    }
                                })
                                    ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                                    ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                        $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                        $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                                    })
                                    ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                        $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                                    });

                                $firstName = '';
                                $lastName = '';
                                $searchTermExp = explode(' ', $searchKeyword);
                                if(count($searchTermExp) > 1){
                                    $firstName = $searchTermExp[0];
                                    $lastName = $searchTermExp[1];
                                }

                                if(!empty($firstName) && !empty($lastName)){
                                    $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                                }
                            });
                    })
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('automation_logs.created_at', [$startDate, $endDate.' 23:59:59']) // New condition
                    ->distinct('crm_customers.id') // Ensure unique records
                    ->count();

                $chart_data = CrmCustomer::select(\DB::raw('DATE(automation_logs.created_at) as date'), \DB::raw('COUNT(DISTINCT crm_customers.id) as count')) // Use COUNT with DISTINCT here
                    ->whereIn('crm_customers.clinic_id', $clinicId)
                    ->where('automation_logs.status_id', 17)
                    ->where('automation_logs.dayinterval', '1')
                    ->leftJoin("automation_logs", "automation_logs.lead_id", "=", "crm_customers.id") // New join
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                                ->whereNull('tag_leads_mapping.deleted_at');
                        })->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                            ->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->when($source_id, function ($query) use ($source_id) {
                        $query->where('source_id', $source_id);
                    })->when($status_id, function ($query1) use ($status_id) {
                        $query1->where('status_id', $status_id);
                    })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query1) use ($searchKeyword, $originalSearchKeyword) {
                        $q = $query1->where(function ($landingPageQuery) use ($originalSearchKeyword) {
                            $landingPageField = "JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url'))";
                            if ($originalSearchKeyword === 'home') {
                                $landingPageQuery->where(function ($subQuery) use ($landingPageField) {
                                    $subQuery->whereNull('callrail_details')
                                        ->orWhereRaw("{$landingPageField} = ''")
                                        ->orWhereRaw("{$landingPageField} = '/'")
                                        ->orWhereRaw("{$landingPageField} = 'home'");
                                });
                            } else {
                                $landingPageQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(callrail_details, '$.landing_page_url')) LIKE ?", ['%' . $originalSearchKeyword . '%']);
                            }
                        })
                            ->orWhere('first_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('crm_customers.email', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('dob', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone_form', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('status', function ($statusQuery) use ($searchKeyword) {
                                $statusQuery->where('name', 'like', '%' . $searchKeyword . '%');
                            })
                            ->orWhereHas('source', function ($sourceQuery) use ($searchKeyword) {
                                $sourceQuery->where('source_name', 'like', '%' . $searchKeyword . '%');
                            });

                        $firstName = '';
                        $lastName = '';
                        $searchTermExp = explode(' ', $searchKeyword);
                        if (count($searchTermExp) > 1) {
                            $firstName = $searchTermExp[0];
                            $lastName = $searchTermExp[1];
                        }

                        if (!empty($firstName) && !empty($lastName)) {
                            $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                        }
                    })
                    ->whereBetween('automation_logs.created_at', [$startDate, $endDate.' 23:59:59']) // New condition
                    ->orderBy('automation_logs.created_at', $order)
                    ->groupBy('date')
                    ->get();

               // dd($chart_data);
                $chartDataOb['tab'] = 'Nurturing';
                if($chart_data) {
                    foreach ($chart_data as $item) {
                        $itemDate = Carbon::createFromFormat('Y-m-d', $item->date)->format('M d, Y');
                        $chartDataOb['x_data'][] = $item->count;
                        $chartDataOb['y_data'][] = $itemDate;
                    }
                }
            }
            else {
                return response()->json(['success' => false,'message' => 'Data Not Found Please Recheck']);
            }
            $totalPages = ceil($totalItems / $perPage);

            return response()->json([
                'success' => true,
                'getReport' => $crmCustomerReport,
                'total_item' => $totalItems,
                'total_lifetimevalue' => number_format($total_lifetimevalue,2,'.',','),
                'total_pages' => $totalPages,
                'current_page' => $page,
                'per_page' => $perPage,
                'chart' => $chartDataOb,
                'total_amounts' => number_format($total_amounts,2,'.',','),
            ]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex, 'GetReportList');
            dd($ex);
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage], 500);
        }
    }

    public function downloadReport(Request $request)
    {
        $user = Auth::user();
        if($request->input('clinic_id')){
            $clinicId = $request->input('clinic_id');
            $clinicId = explode(",",$clinicId);
        }else {
            $clinicId = $user->managerClinics()->pluck('id');
        }
        //$startDate = Carbon::createFromFormat('d/m/Y', $request->input('start_date'))->format('Y-m-d');
        //$endDate = Carbon::createFromFormat('d/m/Y', $request->input('end_date'))->format('Y-m-d');

        $timezones = Clinic::whereIn('id', $clinicId)->pluck('timezone');

        $timezone = $timezones->first();

        if (!$timezone) {
            $timezone = 'America/New_York'; 
        }


        // Parse and convert dates from the user's timezone to UTC
        $startDate = Carbon::createFromFormat('d/m/Y', $request->input('start_date'))
            ->startOfDay() // Set the time to the start of the day
            ->setTimezone('UTC') // Convert to UTC
            ->format('Y-m-d');

            
        $ReportEnddate = $request->input('end_date').' 23:59:59';

        $endDate = Carbon::createFromFormat('d/m/Y H:i:s', $ReportEnddate,$timezone)
            ->setTimezone('UTC') // Convert to UTC
            ->format('Y-m-d');

        $searchKeyword = $request->input('search');
        $statusId = $request->input('status_id');
        $sourceId = $request->input('source_id');
        $tabs = $request->input('tabs');
        $fileFormat = $request->input('format');
        $tagId = $request->input('tagId');
        $formType = $request->input('formType');
        $reason = $request->input('reason');


        try {
            if ($tabs == "treatments_sold") {
                $crmCustomerReport = ReportResource::collection(CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                    ->where('won_lost', "Won")
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query) use ($searchKeyword, $sourceId) {
                        $query->when($sourceId, function ($data1) use ($sourceId) {
                            $data1->where('source_id', $sourceId);
                        })
                    ->when($searchKeyword, function ($query1) use ($searchKeyword) {
                        $q = $query1->where('first_name', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('email', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                        ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                            $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                        });

                        $firstName = '';
                        $lastName = '';
                        $searchTermExp = explode(' ', $searchKeyword);
                        if(count($searchTermExp) > 1){
                            $firstName = $searchTermExp[0];
                            $lastName = $searchTermExp[1];
                        }

                        if(!empty($firstName) && !empty($lastName)){
                            $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                        }
                    });
                })
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull('tag_leads_mapping.deleted_at');
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->select('*',
                    'crm_customers.callrail_details as langing_page_details',
                    DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName'),
                    DB::raw('DATE_FORMAT(crm_customers.created_at, "%m/%d/%Y %H:%i:%s") as createdDate')
                    )
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('won_lost_date', [$startDate, $endDate.' 23:59:59'])
                    ->groupBy('crm_customers.id')
                    ->get());
            } elseif ($tabs == "new_leads") {
              //  DB::enableQueryLog();
                $crmCustomerReport =  ReportResource::collection(CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                    ->where(function ($query) use ($searchKeyword, $statusId, $sourceId) {
                        $query->when($statusId, function ($data) use ($statusId) {
                            $data->where('status_id', $statusId);
                        })->when($sourceId, function ($data1) use ($sourceId) {
                            $data1->where('source_id', $sourceId);
                        })
                        ->when($searchKeyword, function ($query1) use ($searchKeyword) {
                            $q = $query1->where('first_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('email', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            });

                            $firstName = '';
                            $lastName = '';
                            $searchTermExp = explode(' ', $searchKeyword);
                            if(count($searchTermExp) > 1){
                                $firstName = $searchTermExp[0];
                                $lastName = $searchTermExp[1];
                            }

                            if(!empty($firstName) && !empty($lastName)){
                                $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                            }
                        });


                })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull('tag_leads_mapping.deleted_at');
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->select('*',
                    'crm_customers.callrail_details as langing_page_details',
                    DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName'),
                    DB::raw('DATE_FORMAT(crm_customers.created_at, "%m/%d/%Y %H:%i:%s") as createdDate')
                    )
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('crm_customers.created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
                    ->groupBy('crm_customers.id')
                    ->get());
                   // dd(DB::getQueryLog());
            } elseif ($tabs  == "consultations_booked") {
                $crmCustomerReport =  ReportResource::collection(CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query) use ($searchKeyword, $statusId, $sourceId) {
                    $query->when($statusId, function ($data) use ($statusId) {
                        $data->where('status_id', $statusId);
                    })->when($sourceId, function ($data1) use ($sourceId) {
                        $data1->where('source_id', $sourceId);
                    })

                    ->when($searchKeyword, function ($query1) use ($searchKeyword) {
                        $q = $query1->where('first_name', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('email', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                        ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                            $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                        });

                        $firstName = '';
                        $lastName = '';
                        $searchTermExp = explode(' ', $searchKeyword);
                        if(count($searchTermExp) > 1){
                            $firstName = $searchTermExp[0];
                            $lastName = $searchTermExp[1];
                        }

                        if(!empty($firstName) && !empty($lastName)){
                            $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                        }
                    });
                })
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id");
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->select('*',
                    'crm_customers.callrail_details as langing_page_details',
                    DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName'),
                    DB::raw('DATE_FORMAT(crm_customers.created_at, "%m/%d/%Y %H:%i:%s") as createdDate')
                    )
                    ->with(['clinic', 'source', 'status'])
                    ->whereBetween('crm_customers.convert_deal_date', [$startDate, $endDate.' 23:59:59'])
                    ->groupBy('crm_customers.id')
                    ->get());
            } 
            else if($tabs == "engaged"){
               // DB::enableQueryLog();
                $crmCustomerReport = ReportResource::collection(
                    CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                        ->leftJoin("crm_chats", function ($join) use ($startDate, $endDate) {
                            $join->on("crm_chats.lead_id", "=", "crm_customers.id")
                                ->where("crm_chats.inbound", "=", 1)
                                ->whereBetween("crm_chats.created_at", [$startDate, $endDate . ' 23:59:59']);
                        })
                        ->where(function ($query) use ($searchKeyword, $sourceId) {
                            $query->when($sourceId, function ($data1) use ($sourceId) {
                                $data1->where('source_id', $sourceId);
                            })
                            ->when($searchKeyword, function ($query1) use ($searchKeyword) {
                                $q = $query1->where('first_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('email', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                    ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                        $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                    });
                
                                // Handling search by first and last name separately
                                $searchTermExp = explode(' ', $searchKeyword);
                                if (count($searchTermExp) > 1) {
                                    $firstName = $searchTermExp[0];
                                    $lastName = $searchTermExp[1];
                
                                    if (!empty($firstName) && !empty($lastName)) {
                                        $q->orWhere([
                                            ['first_name', 'like', '%' . $firstName . '%'],
                                            ['last_name', 'like', '%' . $lastName . '%']
                                        ]);
                                    }
                                }
                            });
                        })
                        ->leftJoin("tag_leads_mapping", function ($join) {
                            $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                                ->whereNull('tag_leads_mapping.deleted_at');
                        })
                        ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                        ->when($tagId != 0, function ($query) use ($tagId) {
                            $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                        })
                        ->select(
                            '*',
                            'crm_customers.callrail_details as langing_page_details',
                            DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName'),
                            DB::raw('DATE_FORMAT(crm_customers.created_at, "%m/%d/%Y %H:%i:%s") as createdDate')
                        )
                        ->with(['clinic', 'source', 'status'])
                        ->whereBetween('crm_chats.created_at', [$startDate, $endDate . ' 23:59:59'])
                        ->groupBy('crm_customers.id')
                        ->get()
                );
               
                
            }
            elseif ($tabs == "consultations_showed") {
                $crmCustomerReport =  ReportResource::collection(CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->where(function ($query) use ($searchKeyword, $statusId, $sourceId) {
                        $query->when($sourceId, function ($data1) use ($sourceId) {
                            $data1->where('source_id', $sourceId);
                        })
                            ->where('status_id',15)
                    ->when($searchKeyword, function ($query1) use ($searchKeyword) {
                        $q = $query1->where('first_name', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('email', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                        ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                            $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                        });

                        $firstName = '';
                        $lastName = '';
                        $searchTermExp = explode(' ', $searchKeyword);
                        if(count($searchTermExp) > 1){
                            $firstName = $searchTermExp[0];
                            $lastName = $searchTermExp[1];
                        }

                        if(!empty($firstName) && !empty($lastName)){
                            $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                        }
                    });
                })
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull('tag_leads_mapping.deleted_at');
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->select('*',
                    'crm_customers.callrail_details as langing_page_details',
                    DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName'),
                    DB::raw('DATE_FORMAT(crm_customers.created_at, "%m/%d/%Y %H:%i:%s") as createdDate')
                    )
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('crm_customers.consultation_booked_date', [$startDate, $endDate.' 23:59:59'])
                    ->groupBy('crm_customers.id')
                    ->get());
            } elseif ($tabs == "treatments_presented") {
                $crmCustomerReport =  ReportResource::collection(CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->whereNull('won_lost')
                    ->where(function ($query) use ($searchKeyword, $statusId, $sourceId) {
                        $query->when($sourceId, function ($data1) use ($sourceId) {
                            $data1->where('source_id', $sourceId);
                        })
                            ->where('status_id',15)
                            ->when($searchKeyword, function ($query1) use ($searchKeyword) {
                                $q = $query1->where('first_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('email', 'like', '%' . $searchKeyword . '%')
                                    ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                    ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                        $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                    });

                                $firstName = '';
                                $lastName = '';
                                $searchTermExp = explode(' ', $searchKeyword);
                                if(count($searchTermExp) > 1){
                                    $firstName = $searchTermExp[0];
                                    $lastName = $searchTermExp[1];
                                }

                                if(!empty($firstName) && !empty($lastName)){
                                    $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                                }
                            });
                    })
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull('tag_leads_mapping.deleted_at');
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->select('*',
                    'crm_customers.callrail_details as langing_page_details',
                    DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName'),
                    DB::raw('DATE_FORMAT(crm_customers.created_at, "%m/%d/%Y %H:%i:%s") as createdDate')
                    )
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('crm_customers.updated_at', [$startDate, $endDate.' 23:59:59'])
                    ->groupBy('crm_customers.id')
                    ->get());
            }elseif ($tabs == "consultations_follow_up") {
                $crmCustomerReport =  ReportResource::collection(CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->whereNull('won_lost')
                    ->where('crm_customers.status_id', '6')
                    ->where(function ($query) use ($searchKeyword, $statusId, $sourceId) {
                        $query->when($sourceId, function ($data1) use ($sourceId) {
                            $data1->where('source_id', $sourceId);
                        })
                        ->when($searchKeyword, function ($query1) use ($searchKeyword) {
                            $q = $query1->where('first_name', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('email', 'like', '%' . $searchKeyword . '%')
                                ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                                ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                    $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                                });

                            $firstName = '';
                            $lastName = '';
                            $searchTermExp = explode(' ', $searchKeyword);
                            if(count($searchTermExp) > 1){
                                $firstName = $searchTermExp[0];
                                $lastName = $searchTermExp[1];
                            }

                            if(!empty($firstName) && !empty($lastName)){
                                $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                            }
                        });
                    })
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull('tag_leads_mapping.deleted_at');
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->select('*',
                    'crm_customers.callrail_details as langing_page_details',
                    DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName'),
                    DB::raw('DATE_FORMAT(crm_customers.created_at, "%m/%d/%Y %H:%i:%s") as createdDate')
                    )
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('crm_customers.updated_at', [$startDate, $endDate.' 23:59:59'])
                    ->groupBy('crm_customers.id')
                    ->get());
            }elseif($tabs == "nurturing"){
                $crmCustomerReport =  ReportResource::collection(CrmCustomer::whereIn('crm_customers.clinic_id', $clinicId)
                ->where('automation_logs.status_id', 17)
                ->where('automation_logs.dayinterval', '1')
                    ->leftJoin("automation_logs", "automation_logs.lead_id", "=", "crm_customers.id")
                    ->where(function ($query) use ($searchKeyword, $statusId, $sourceId) {
                        $query->when($statusId, function ($data) use ($statusId) {
                            $data->where('status_id', $statusId);
                        })->when($sourceId, function ($data1) use ($sourceId) {
                            $data1->where('source_id', $sourceId);
                        })
                        ->when($searchKeyword, function ($query1) use ($searchKeyword) {
                            $q = $query1->where('first_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('email', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('phone', 'like', '%' . $searchKeyword . '%')
                            ->orWhereHas('clinic', function ($query2) use ($searchKeyword) {
                                $query2->where('clinic_name', 'like', '%' . $searchKeyword . '%');
                            });

                            $firstName = '';
                            $lastName = '';
                            $searchTermExp = explode(' ', $searchKeyword);
                            if(count($searchTermExp) > 1){
                                $firstName = $searchTermExp[0];
                                $lastName = $searchTermExp[1];
                            }

                            if(!empty($firstName) && !empty($lastName)){
                                $q->orWhere([['first_name', 'like', '%' . $firstName . '%'], ['last_name', 'like', '%' . $lastName . '%']]);
                            }
                        });


                })
                    ->when($formType != null, function ($query) use ($formType) {
                        $query->where("phone_form", $formType);
                    })
                    ->when($reason != null, function ($query) use ($reason) {
                        $query->where("reason", $reason);
                    })
                    ->leftJoin("tag_leads_mapping", function ($join) {
                        $join->on("tag_leads_mapping.lead_id", "=", "crm_customers.id")
                        ->whereNull('tag_leads_mapping.deleted_at');
                    })
                    ->leftJoin("tags", "tags.id", "=", "tag_leads_mapping.tag_id")
                    ->when($tagId != 0, function ($query) use ($tagId) {
                        $query->where("tag_leads_mapping.tag_id", "=", $tagId);
                    })
                    ->select('*',
                    'crm_customers.callrail_details as langing_page_details',
                    DB::raw('GROUP_CONCAT(DISTINCT tags.tagName SEPARATOR ", ") as tagName'),
                    DB::raw('DATE_FORMAT(crm_customers.created_at, "%m/%d/%Y %H:%i:%s") as createdDate')
                    )
                    ->with(['clinic','source', 'status'])
                    ->whereBetween('automation_logs.created_at', [$startDate, $endDate.' 23:59:59'])
                    ->groupBy('crm_customers.id')
                    ->get());
            }else {
                return response()->json(['success' => false, 'message' => 'Data Not Found Please Recheck']);
            }
            $returnData = [];

            if ($fileFormat == 'pdf') {
                $htmlData = [];
                $htmlData[0]['first_name']    = 'First Name';
                $htmlData[0]['last_name']  = 'Last Name';
                $htmlData[0]['phone_form'] = 'Phone/ Form Lead';
                $htmlData[0]['source'] = 'Source';
                $htmlData[0]['stage'] = 'Stage';
                $htmlData[0]['email']  = 'Email';
                $htmlData[0]['phone']  = 'Phone';
                $htmlData[0]['value']  = 'Value';
                $htmlData[0]['reason']  = 'Reason';
                $htmlData[0]['city'] = 'City';
                $htmlData[0]['state'] = 'State';
                $htmlData[0]['zipcode'] = 'Zipcode';
                $htmlData[0]['lifetimevalue'] = 'Lifetime Value';
                $htmlData[0]['lead_score'] = 'Lead Score';
                $htmlData[0]['consultation_booked_date'] = 'Consultation Booked Date';
                $htmlData[0]['no_showed_date'] = 'No Showed Date';
                $htmlData[0]['convert_deal_date'] = 'Convert Deal Date';
                $htmlData[0]['created_at'] = 'Created At';
                $htmlData[0]['Landing Page'] = 'Landing Page';
                $htmlData[0]['Tags'] = 'Tags';

                foreach ($crmCustomerReport as $key => $crm) {
                    $getUrldata  = $crm->langing_page_details;
                    $landingPageUrlWithoutQueryString = '';
                    if($getUrldata)
                    {
                        $landingPageUrl  = json_decode($getUrldata, true);
                        $urlParts = explode('?', $landingPageUrl['landing_page_url']);
                        $parsedUrl = parse_url($urlParts[0]);
                        $landingPageUrlWithoutQueryString = pathinfo($parsedUrl['path'], PATHINFO_BASENAME);
                    }


                    $htmlData[$key+1]['first_name']    = $crm->first_name;
                    $htmlData[$key+1]['last_name']  = $crm->last_name;
                    $htmlData[$key+1]['phone_form'] = $crm->phone_form;
                    $htmlData[$key+1]['source'] = !empty($crm->source) ? $crm->source->source_name : '';
                    $htmlData[$key+1]['stage'] = !empty($crm->status) ? $crm->status->name : '';
                    $htmlData[$key+1]['email']  = $crm->email;
                    $htmlData[$key+1]['phone']  = $crm->phone;
                    $htmlData[$key+1]['value']  = $crm->value;
                    $htmlData[$key+1]['reason']  = $crm->reason;
                    $htmlData[$key+1]['city'] = $crm->city;
                    $htmlData[$key+1]['state'] = $crm->state;
                    $htmlData[$key+1]['zipcode'] = $crm->zipcode;
                    $htmlData[$key+1]['lifetimevalue'] = $crm->lifetimevalue ? '$'.number_format($crm->lifetimevalue,0,'',',') : '';
                    $htmlData[$key+1]['lead_score'] = $crm->lead_score;
                    $htmlData[$key+1]['consultation_booked_date'] = $crm->consultation_booked_date;
                    $htmlData[$key+1]['no_showed_date'] = $crm->no_showed_date;
                    $htmlData[$key+1]['convert_deal_date'] = $crm->convert_deal_date;
                    $htmlData[$key+1]['created_at'] = $crm->createdDate;
                    $htmlData[$key+1]['Landing Page'] =$landingPageUrlWithoutQueryString;
                    $htmlData[$key+1]['Tags'] = $crm->tagName;

                }


                $tableHtml = '
                <!DOCTYPE html>
                <html lang="en" class="no-js">
                <head>
                <title>Generated Pdf</title>
                <style>
                    th, td {
                        padding: 10px; /* Add padding to the cells */
                        border: 0.5px solid #000; /* Add border to the cells */
                        text-align: left; /* Align text to the left */
                    }

                    th {
                        font-weight: bold; /* Make table headers bold */
                    }
                     td:nth-child(6) {
                       white-space: normal;
                       width: 3px;
                    }
                </style>
                </head>
                <body style="width: 10vw;">
                <table style=" border-collapse: collapse; /* Collapse borders */
                        font-size:1.3px;
                        ">';
                foreach ($htmlData as $index=>$row) {

                    $tableHtml .= '<tr>';
                    foreach ($row as $index2=>$cell) {
                        if($index == 0){
                            $tableHtml .= '<td style="width: 3px;"><b>' . htmlspecialchars($cell) . '</b></td>';
                        }else if($index2 == 'email'){
                            $cell = str_replace(" ","",$cell);
                            if(strlen($cell) > 30){
                                $part1 = substr($cell, 0, 15);
                                $part2 = substr($cell, 16);
                                $email_print = $part1."<br/>".$part2;
                                $tableHtml .= '<td style="width: 3px;"><b>' . $email_print . '</b></td>';
                            }else{
                                $tableHtml .= '<td style="width: 3px;"><b>' . htmlspecialchars($cell) . '</b></td>';
                            }

                        }else{
                            $tableHtml .= '<td style="width: 3px;">' . htmlspecialchars($cell) . '</td>';
                        }
                    }
                    $tableHtml .= '<tr>';
                }
                $tableHtml .= '</table>
                </body>
                </html>
                ';

                return response()->json([
                    'success' => true,
                    'data' => $tableHtml
                ], 200);
            }
            else if($fileFormat == 'csv'){
                $returnData[0]['first_name']    = 'First Name';
                $returnData[0]['last_name']  = 'Last Name';
                $returnData[0]['phone_form'] = 'Phone/ Form Lead';
                $returnData[0]['source'] = 'Source';
                $returnData[0]['stage'] = 'Stage';
                $returnData[0]['email']  = 'Email';
                $returnData[0]['phone']  = 'Phone';
                $returnData[0]['value']  = 'Value';
                $returnData[0]['reason']  = 'Reason';
                $returnData[0]['city'] = 'City';
                $returnData[0]['state'] = 'State';
                $returnData[0]['zipcode'] = 'Zipcode';
                $returnData[0]['lifetimevalue'] = 'Lifetime Value';
                $returnData[0]['lead_score'] = 'Lead Score';
                $returnData[0]['consultation_booked_date'] = 'Consultation Booked Date';
                $returnData[0]['no_showed_date'] = 'No Showed Date';
                $returnData[0]['convert_deal_date'] = 'Convert Deal Date';
                $returnData[0]['created_at'] = 'Created At';
                $returnData[0]['Landing Page'] = 'Landing Page';
                $returnData[0]['Tags'] = 'Tags';


                foreach ($crmCustomerReport as $key => $crm) {

                    $getUrldata  = $crm->langing_page_details;
                    $landingPageUrlWithoutQueryString = '';
                    if($getUrldata)
                    {
                        $landingPageUrl  = json_decode($getUrldata, true);
                        $urlParts = explode('?', $landingPageUrl['landing_page_url']);
                        $parsedUrl = parse_url($urlParts[0]);
                        $landingPageUrlWithoutQueryString = pathinfo($parsedUrl['path'], PATHINFO_BASENAME);
                    }

                    $returnData[$key+1]['First Name']    = str_replace(","," ",$crm->first_name);
                    $returnData[$key+1]['Last Name']  = str_replace(","," ",$crm->last_name);
                    $returnData[$key+1]['Phone/ Form Lead'] = $crm->phone_form;
                    $returnData[$key+1]['Source'] = !empty($crm->source) ? $crm->source->source_name : '';
                    $returnData[$key+1]['Stage'] = !empty($crm->status) ? $crm->status->name : '';
                    $returnData[$key+1]['Email']  = $crm->email;
                    $returnData[$key+1]['Phone']  = $crm->phone;
                    $returnData[$key+1]['Value']  = $crm->value;
                    $returnData[$key+1]['Reason']  = $crm->reason;
                    $returnData[$key+1]['City'] = $crm->city;
                    $returnData[$key+1]['State'] = $crm->state;
                    $returnData[$key+1]['Zipcode'] = $crm->zipcode;
                   // $returnData[$key+1]['Lifetime Value'] = $crm->lifetimevalue ? '$'.number_format($crm->lifetimevalue,0,'',',') : '';
                   $returnData[$key + 1]['Lifetime Value'] = $crm->lifetimevalue ? '$' . number_format($crm->lifetimevalue, 0, '', '') : '';
                    $returnData[$key+1]['Lead Score'] = $crm->lead_score;
                    $returnData[$key+1]['Consultation Booked Date'] = $crm->consultation_booked_date;
                    $returnData[$key+1]['No Showed Date'] = $crm->no_showed_date;
                    $returnData[$key+1]['Convert Deal Date'] = $crm->convert_deal_date;
                    $returnData[$key+1]['Created At'] = $crm->createdDate;
                    $returnData[$key+1]['Landing Page'] = $landingPageUrlWithoutQueryString;
                    $returnData[$key+1]['Tags'] = $crm->tagName;

                }
            }
            if($fileFormat == 'xlsx'){
                foreach ($crmCustomerReport as $key => $crm) {
                    $getUrldata  = $crm->langing_page_details;
                    $landingPageUrlWithoutQueryString = '';
                    if($getUrldata)
                    {
                        $landingPageUrl  = json_decode($getUrldata, true);
                        $urlParts = explode('?', $landingPageUrl['landing_page_url']);
                        $parsedUrl = parse_url($urlParts[0]);
                        $landingPageUrlWithoutQueryString = pathinfo($parsedUrl['path'], PATHINFO_BASENAME);
                    }

                    $returnData[$key]['First Name']    = $crm->first_name;
                    $returnData[$key]['Last Name']  = $crm->last_name;
                    $returnData[$key]['Phone/ Form Lead'] = $crm->phone_form;
                    $returnData[$key]['Source'] = !empty($crm->source) ? $crm->source->source_name : '';
                    $returnData[$key]['Stage'] = !empty($crm->status) ? $crm->status->name : '';
                    $returnData[$key]['Email']  = $crm->email;
                    $returnData[$key]['Phone']  = $crm->phone;
                    $returnData[$key]['Value']  = $crm->value;
                    $returnData[$key]['Reason'] = $crm->reason;
                    $returnData[$key]['City'] = $crm->city;
                    $returnData[$key]['State'] = $crm->state;
                    $returnData[$key]['Zipcode'] = $crm->zipcode;
                    $returnData[$key]['Lifetime Value'] = $crm->lifetimevalue ? '$'.number_format($crm->lifetimevalue,0,'',',') : '';
                    $returnData[$key]['Lead Score'] = $crm->lead_score;
                    $returnData[$key]['Consultation Booked Date'] = $crm->consultation_booked_date;
                    $returnData[$key]['No Showed Date'] = $crm->no_showed_date;
                    $returnData[$key]['Convert Deal Date'] = $crm->convert_deal_date;
                    $returnData[$key]['Created At'] = $crm->createdDate;
                    $returnData[$key]['Landing Page'] = $landingPageUrlWithoutQueryString;
                    $returnData[$key]['Tags'] = $crm->tagName;
                }
            }
            return response()->json([
                'success' => true,
                'data' => $returnData
            ]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex, 'GetReportList');
           return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage], 500);
        }
    }

    public function getEmailStatistics(Request $request)
    {
        if($request->has('clinic_id')){

            $clinic = Clinic::find($request->input('clinic_id'));


            if(!empty($clinic->elastic_email_api_key)){
                $client = new Client();
                $response = $client->request('get', 'https://api.smtprelay.co/v3/reports/summary', ['query'=>['apikey'=>$clinic->elastic_email_api_key, 'from'=>$request->from, 'to'=>$request->to], 'verify'=>false]);

                $statistics = json_decode($response->getBody());

                $logStatusSummary = $statistics->LogStatusSummary;

                $dailyLogStatusSummary = $statistics->DailyLogStatusSummary;

                $xChartData = $yChartData = [];

                foreach ($dailyLogStatusSummary as $summary){
                    $xChartData[] = Carbon::parse($summary->Date)->format('m/d/Y');
                    $yChartData['Submitted'][] = $summary->Email;
                    $yChartData['Delivered'][] = $summary->Delivered;
                    $yChartData['Opened'][] = $summary->Opened;
                    $yChartData['Clicked'][] = $summary->Clicked;
                    $yChartData['Unsubscribed'][] = $summary->Unsubscribed;
                    $yChartData['Bounced'][] = $summary->Bounced;
                    $yChartData['Complaints'][] = $summary->Complaint;
                    $yChartData['Suppressed'][] = $summary->Suppressed;

                }

                $data = ['logStatusSummary' => $logStatusSummary, 'dailyLogStatusSummary' => $dailyLogStatusSummary, 'chartData' => ['Dates'=>$xChartData, 'Summary' => $yChartData]];

                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No api key setup for selected clinic.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No clinic id provided.'
        ]);
    }

}
