<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCrmCustomerRequest;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use App\Models\Source;
use App\Models\CrmCall;
use App\Models\CrmChat;
use App\Models\CrmNote;
Use Carbon\Carbon;


class ImportController extends Controller
{	
    
    public function importscript(Request $request)
    {
       
      $db_ext = \DB::connection('mysql_external');
      $leads = $db_ext->table('fx_leads')->select('fx_leads.*','c.name as stagename', 'd.name as soucename')->leftJoin('fx_categories as c', 'c.id', '=', 'fx_leads.stage_id')->leftJoin('fx_categories as d', 'd.id', '=', 'fx_leads.source')->whereNull('fx_leads.deleted_at')->where('fx_leads.stage_id','!=' ,63)->orderBy('id', 'asc')->get();
      //echo "<pre>";
      //print_r($leads);

      //SELECT fx_leads.*,c.name,d.name FROM `fx_leads` left join fx_categories as c on c.id=fx_leads.stage_id left join fx_categories as d on d.id=fx_leads.source where fx_leads.deleted_at is null and fx_leads.stage_id !=63
      foreach ($leads as $key => $lead) {
        $customer_name = $lead->name;

        $customer_name = preg_replace('!\s+!', ' ', $customer_name);

        $name = explode(" ", $customer_name);

        $first_name = $name[0];
        $last_name  =  '';
        if(isset($name[1])){
          $last_name = $name[1];
        }
       

        $customer_email = $lead->email;
        $customer_phone = $lead->mobile;

        if (strpos($lead->email, '@noemail.com') !== false){
          $phone_form = 'Phone Call';
        }else{
          $phone_form = 'Web Form';
        }
        $city = $lead->city;
        $state = $lead->state;
        $no_showed_date = $lead->no_showed_date;

        $value = NULL;
        if($lead->lead_value > 0){
          $value = $lead->lead_value;
        }

        $form_data = $lead->message;

        $created_at = $lead->created_at;
        $updated_at = $lead->updated_at;
//clinic id           
        $clinic_id = 2;
//clinic id   
        $convert_deal_date = $lead->consult_booked_date;

        $consultation_booked_date = NULL;
        $convert_to_deal = NULL;
         
        $source_id = ''; 
        if($lead->soucename == 'Facebook'){
           $source_id = 3;
        }elseif($lead->soucename == 'Google Organic'){
           $source_id = 4;
        }elseif($lead->soucename == 'Web'){
           $source_id = 5;
        }elseif($lead->soucename == 'Twitter'){
           $source_id = 15;
        }elseif($lead->soucename == 'Client Referral'){
           $source_id = 16;
        }elseif($lead->soucename == 'Youtube'){
           $source_id = 6;
        }elseif($lead->soucename == 'Mailchimp'){
           $source_id = 17;
        }elseif($lead->soucename == 'Previous Client'){
           $source_id = 18;
        }elseif($lead->soucename == 'Email List'){
           $source_id = 7;
        }elseif($lead->soucename == 'Google Ads'){
           $source_id = 2; 
        }elseif($lead->soucename == 'TV'){
           $source_id = 12;
        }elseif($lead->soucename == 'Infomercial'){
           $source_id = 13;
        }elseif($lead->soucename == 'Radio'){
           $source_id = 14;
        }elseif($lead->soucename == 'Direct'){
           $source_id = 1;
        }elseif($lead->soucename == 'Yelp'){
           $source_id = 19;
        }elseif($lead->soucename == 'LinkedIn'){
           $source_id = 20;
        }elseif($lead->soucename == 'Duckduckgo'){
           $source_id = 21;
        }elseif($lead->soucename == 'Yahoo'){
           $source_id = 8;
        }elseif($lead->soucename == 'Msn'){
           $source_id = 9;
        }elseif($lead->soucename == 'Bing'){
           $source_id = 10;
        }elseif($lead->soucename == 'Baidu'){
           $source_id = 22;
        }elseif($lead->soucename == 'Other'){
           $source_id = 11;
        }else{
           $source_id = 11;
        }

        
    
        $status_id = '';
        if($lead->stagename == 'In Discussion'){
          $status_id = '5';
        }elseif($lead->stagename == 'Consultation Booked'){
          $consultation_booked_date = '1999-01-01 01:00:00';
          $convert_to_deal = 1;
          $status_id = '12';
        }elseif($lead->stagename == 'New Lead'){
          $status_id = '1';
        }elseif($lead->stagename == 'No Showed'){
          $status_id = '13';
          $convert_to_deal = 1;
          $consultation_booked_date = '1999-01-01 01:00:00';
        }elseif($lead->stagename == 'Attempt Two'){
          $status_id = '3';
        }elseif($lead->stagename == 'Attempt Three Plus'){
          $status_id = '4';
        }elseif($lead->stagename == 'Not Interested Or Did not schedule'){
          $status_id = '9';
        }elseif($lead->stagename == 'Attempt One'){
          $status_id = '2';
        }elseif($lead->stagename == 'Doctor follow up'){
          $status_id = '6';
        }elseif($lead->stagename == 'Cancellation'){
          $status_id = '14';
          $convert_to_deal = 1;
          $consultation_booked_date = '1999-01-01 01:00:00';
        }elseif($lead->stagename == 'IN OFFICE TREATMENT PRESENTED'){
          $status_id = '15';
        }elseif($lead->stagename == 'Virtual Treatment Presented'){
          $status_id = '15';
        }elseif($lead->stagename == 'TREATMENT ACCEPTED'){
          $status_id = '15';
        }else{
          $status_id = '0';
        }
        
        $won_lost = NULL;
        $won_lost_date = NULL; 
        $value = NULL;
        if($lead->converted_at != NULL){
            $convert_to_deal = 1;
            $consultation_booked_date = '1999-01-01 01:00:00';
            $status_id = '15';
      
            $deals = $db_ext->table('fx_deals')->select('fx_deals.*')->whereNull('fx_deals.deleted_at')->where('fx_deals.title','=' ,$customer_name)->first();
            if($deals){
                if($deals->status == 'won'){
                  $won_lost = 'Won';
                  $won_lost_date = $deals->won_time;
                }elseif($deals->status == 'lost'){
                  $won_lost = 'Lost';
                  $won_lost_date = $deals->lost_time;
                }
                if($deals->deal_value > 0){
                  $value = $deals->deal_value;
                }
              
            }
        }
        
        $CrmCustomer = new CrmCustomer;

        $CrmCustomer->clinic_id               = $clinic_id;
        $CrmCustomer->first_name              = $first_name;
        $CrmCustomer->last_name               = $last_name;
        $CrmCustomer->phone_form              = $phone_form;
        $CrmCustomer->source_id               = $source_id;
        $CrmCustomer->status_id               = $status_id;
        $CrmCustomer->email                   = $customer_email;
        $CrmCustomer->phone                   = $customer_phone;
        $CrmCustomer->city                    = $city;
        $CrmCustomer->state                   = $state;
        $CrmCustomer->form_data               = $form_data;
        $CrmCustomer->consultation_booked_date= $consultation_booked_date ? Carbon::createFromFormat('Y-m-d H:i:s', $consultation_booked_date)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null; 
        $CrmCustomer->convert_to_deal         = $convert_to_deal;
        $CrmCustomer->convert_deal_date       = $convert_deal_date ? Carbon::createFromFormat('Y-m-d H:i:s', $convert_deal_date)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null; 
        $CrmCustomer->value                   = $value;
        $CrmCustomer->won_lost                = $won_lost;
        $CrmCustomer->won_lost_date           = $won_lost_date ? Carbon::createFromFormat('Y-m-d H:i:s', $won_lost_date)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
       
        $CrmCustomer->created_at              = $lead->created_at ? Carbon::createFromFormat('Y-m-d H:i:s', $lead->created_at)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
        $CrmCustomer->updated_at              = $lead->updated_at ? Carbon::createFromFormat('Y-m-d H:i:s', $lead->updated_at)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null; 

        
        preg_match('/^([^ ]+ +[^ ]+) +(.*)$/', $customer_name, $matches);
        

        if(isset($matches[2]) && $matches[2] != ''){
          $CrmCustomer->badge                 = $matches[2];
        }
          
        
        $CrmCustomer->save();
        echo $customer_id = $CrmCustomer->id;
        //Notes code
        if(!isset($deals->id)){
          $dealid = '0';
        }else{
          $dealid = $deals->id;
        }
        $leadid = $lead->id;
            
        $notes = $db_ext->table('fx_notes')->select('fx_notes.*')->whereNull('fx_notes.deleted_at')->where(function ($query) use($leadid) {$query->where('noteable_type', 'Modules\Leads\Entities\Lead')
                                                                                                                                    ->where('noteable_id', '=', $leadid);
                                                                                                                            })->orWhere(function($query) use($dealid) {
                                                                                                                                $query->where('noteable_type', 'Modules\Deals\Entities\Deal')
                                                                                                                                    ->where('noteable_id', '=', $dealid); 
                                                                                                                            })->get();

                                                                                                                
          
        if(count($notes)>0){
            foreach($notes as $note){
              $note_desc        = $note->description;
              $note_created_at  = $note->created_at;
              $note_updated_at  = $note->updated_at;

              $CrmNote = new CrmNote;
              $CrmNote->note = $note_desc;
              $CrmNote->user_id = 1;
              $CrmNote->customer_id = $customer_id;
              $CrmNote->created_at = $note_created_at ? Carbon::createFromFormat('Y-m-d H:i:s', $note_created_at)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
              $CrmNote->updated_at = $note_updated_at ? Carbon::createFromFormat('Y-m-d H:i:s', $note_updated_at)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null; 
              $CrmNote->save();

            }
        }

        $CrmNote = new CrmNote;
        $CrmNote->note = "This was imported from CRM V.1.0 on ".Carbon::now()->format(config('panel.date_format'));
        $CrmNote->user_id = 1;
        $CrmNote->customer_id = $customer_id;
        $CrmNote->save();


        //SMS code
        $chats = $db_ext->table('fx_chats')->select('fx_chats.*')->whereNull('fx_chats.deleted_at')->where(function ($query) use($leadid) {$query->where('chatable_type', 'Modules\Leads\Entities\Lead')
                                                                                                                                    ->where('chatable_id', '=', $leadid);
                                                                                                                            })->orWhere(function($query) use($dealid) {
                                                                                                                                $query->where('chatable_type', 'Modules\Deals\Entities\Deal')
                                                                                                                                    ->where('chatable_id', '=', $dealid); 
                                                                                                                            })->get();

                                                                                                                
          
        if(count($notes)>0){
            foreach($chats as $chat){
              $message        = $chat->message;
              $chat_created_at  = $chat->created_at;
              $chat_updated_at  = $chat->updated_at;

              $CrmChat = new CrmChat;
              $CrmChat->lead_id = $customer_id;
              $CrmChat->user_id = 1;
              $CrmChat->inbound = $chat->inbound;
              $CrmChat->platform = 'sms';
              $CrmChat->chat = $message;
              $CrmChat->from             = $chat->from;
              $CrmChat->to               = $chat->to;
              $CrmChat->delivered        = $chat->delivered;
              $CrmChat->read             = $chat->read;
              $CrmChat->is_sms           = $chat->is_sms; 
              $CrmChat->created_at = $chat_created_at ? Carbon::createFromFormat('Y-m-d H:i:s', $chat_created_at)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
              $CrmChat->updated_at = $chat_updated_at ? Carbon::createFromFormat('Y-m-d H:i:s', $chat_updated_at)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null; 
              
              $CrmChat->save();

            }
        }
        $array = array();
        $deals =  (object) $array;
        $deals->id = '';
        echo " Record Imported: ".$dealid."<br>";
      }

      //echo "hiiiii";exit; 
      
        
    }


    
}

