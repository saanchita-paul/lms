<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use Carbon\Carbon;
use Session;

class HomeController
{
    public function index()
    {
        $userid = Auth()->user()->id;
        $isadmin =  Auth()->user()->getIsAdminAttribute();
        $clinics = array();
        if(!$isadmin){
            $clinics = Clinic::whereHas('managers', function ($query ) use($userid) {
             return   $query->where('user_id', '=', $userid );
            })->orderBy('clinic_name', 'ASC')->get();
        }else{
            $clinics      = Clinic::orderBy('clinic_name', 'ASC')->get();
        }

        $selectedclinic = array();
        $clinicurl = array();
        if(session('selectedclinic')){
            $selectedclinic = session('selectedclinic');
            $clinicurl = Clinic::select('marketingdashboardurl','schedulemeetingurl','salestrainingurl','custom_message')->where('id','=',$selectedclinic[0])->first();
        }else{
            if($clinics->isNotEmpty()){
                $clinicurl = Clinic::select('marketingdashboardurl','schedulemeetingurl','salestrainingurl','custom_message')->where('id','=',$clinics[0]->id)->first();
            }
        }
        return view('home', compact('clinics','selectedclinic','clinicurl'));
    }

    public function dashboard(){
        $userid = Auth()->user()->id;
        $isadmin =  Auth()->user()->getIsAdminAttribute();

        if(!$isadmin){
            $query = CrmCustomer::select('id','value','lifetimevalue')->with(['clinic', 'status'])->whereHas('clinic.managers', function ($query ) use($userid) {
                         $query->where('user_id', '=', $userid );
                    });
            if(session('selectedclinic')){
                    $selectedclinic = session('selectedclinic');
                    $query = $query->whereIn('clinic_id',$selectedclinic);
            }
        }else{
            $query = CrmCustomer::select('id','value','lifetimevalue')->with(['clinic', 'status']);
            if(session('selectedclinic')){
                    $selectedclinic = session('selectedclinic');
                    $query = $query->whereIn('clinic_id',$selectedclinic);
            }
        }

        $newleads = clone  $query;
        $booked = clone  $query;
        $showed = clone  $query;
        $nurturing = clone  $query;
        $presented = clone  $query;
        $won = clone  $query;
        $followup = clone  $query;
        $wonalltime = clone  $query;
        $wonlifetime = clone  $query;
        $notshoweddata = clone  $query;

        $dashboardData = array(
          "newleads" => $newleads->whereBetween('created_at', [now()->subdays(30),now()])->get()->count(),
          "booked" => $booked->where('convert_to_deal',"1")->whereBetween('convert_deal_date', [now()->subdays(30),now()])->get()->count(),
          "showed" => $showed->where('status_id',"15")->whereBetween('updated_at', [now()->subdays(30),now()])->get()->count(),
          "presented" => $presented->where('status_id',"15")->whereNull('won_lost')->get()->sum('value'),
          'followup' =>  $followup->whereNull('won_lost')->whereNotNull('deal_status')->get()->count(),
          'nurturing' => $nurturing->where('status_id',"9")->get()->count(),
          'won' => $won->where('won_lost',"Won")->whereBetween('won_lost_date', [now()->subdays(30),now()])->get()->sum('value'),
          'wonalltime' => $wonalltime->where('won_lost',"Won")->get()->sum('value'),
          'wonlifetime' => $wonlifetime->where('lifetimevalue',">",0)->get()->sum('lifetimevalue'),
          "notshoweddata" => $notshoweddata->where('status_id',"13")->whereBetween('no_showed_date', [now()->subdays(30),now()])->get()->count(),
        );
        return $dashboardData;
    }
}