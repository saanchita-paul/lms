<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use Carbon\Carbon;

class DailyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Respectively send an exclusive reports to everyone daily via email.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function leadsyesterday($clinicid){    
        $query = CrmCustomer::with(['clinic']);        
        return $leadsyesterday = $query->where('clinic_id',$clinicid)->whereBetween('created_at', [Carbon::yesterday(),Carbon::yesterday()->endOfDay()])->get()->count();
    }

    public function leadssevendays($clinicid){            
        $query = CrmCustomer::with(['clinic']); 
        return $leadssevendays = $query->where('clinic_id',$clinicid)->whereBetween('created_at', [now()->subDays(7),now()])->get()->count();
    }

    public function consultsbooked($clinicid){            
        $query = CrmCustomer::with(['clinic']); 
        return $consultsbooked = $query->where('clinic_id',$clinicid)->where('convert_to_deal',"1")->whereBetween('convert_deal_date', [now()->subDays(30),now()])->get()->count();
    }

    public function treatpresented($clinicid){            
        $query = CrmCustomer::with(['clinic', 'status']); 
        return $treatpresented = $query->where('clinic_id',$clinicid)->where('status_id',"15")->whereNull('won_lost')->get()->sum('value');
    }

    public function treatsold($clinicid){            
        $query = CrmCustomer::with(['clinic', 'status']); 
        return $treatsold = $query->where('clinic_id',$clinicid)->where('won_lost',"Won")->whereBetween('won_lost_date', [now()->subdays(30),now()])->get()->sum('value'); 
    }

    public function treatsoldall($clinicid){            
        $query = CrmCustomer::with(['clinic', 'status']); 
        return $treatsoldall = $query->where('clinic_id',$clinicid)->where('won_lost',"Won")->get()->sum('value');
    }               
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
                
        $clinics      = Clinic::get();

        foreach ($clinics as $key => $value) {
            if($value->reportrecipients != null){
                if($value->reportsending == 'Daily'){
                    
                    $value->clinic_name;
                    
                    $leadsyesterday = $this->leadsyesterday($value->id);
                    
                    $leadssevendays = $this->leadssevendays($value->id);
                    
                    $consultsbooked = $this->consultsbooked($value->id);
                    
                    $treatpresented = number_format($this->treatpresented($value->id), 0, '.', ','); 
                    
                    $treatsold     = number_format($this->treatsold($value->id), 0, '.', ','); 
                    
                    $treatsoldall  = number_format($this->treatsoldall($value->id), 0, '.', ','); 


                    $html = '<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<!--[if !mso]><!-->
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!--<![endif]-->
<meta name="format-detection" content="telephone=no" />
<title>Daily Report</title>
<!--[if !mso]><!-->
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700,700i,900" rel="stylesheet" />  
<!--<![endif]--> 
<style type="text/css">
body {-webkit-text-size-adjust: 100% !important;-ms-text-size-adjust: 100% !important;-webkit-font-smoothing: antialiased !important;width: 100%;height: 100%;
    background-color: #ffffff;  margin: 0;  padding: 0; -webkit-font-smoothing: antialiased;}
img { border: 0 !important; outline: none !important; }
p { Margin: 0px !important; Padding: 0px !important; }
table { border-collapse: collapse; mso-table-lspace: 0px; mso-table-rspace: 0px; }
td, a, span { border-collapse: collapse; mso-line-height-rule: exactly; }
.ExternalClass * { line-height: 100%; }
span.MsoHyperlink { mso-style-priority: 99; color: inherit; }
span.MsoHyperlinkFollowed { mso-style-priority: 99; color: inherit; }
.em_defaultlink a { color: inherit !important; text-decoration: none !important; }
.rw_phone_layout .em_full_img{width:100%; height:auto!important;}
.rw_tablet_layout .em_full_img{width:100%; height:auto!important;} 
.mcenter{margin-top: 20px;}
</style>
<!-- @media only screen and (max-width: 640px) 
           {
           -->
<style type="text/css">
@media only screen and (max-width: 640px) {
td[class=em_h1] { height:60px !important;  font-size:1px !important;  line-height:1px !important;}
table[class=myfull] {width:100% !important; max-width:300px!important; text-align:center!important;}
table[class=notify-5-wrap] {width:100% !important; max-width:400px;}
table[class=full] { width:100% !important;}
td[class=fullCenter] { width:100% !important; text-align:center!important}
td[class=em_hide] {display:none !important;}
table[class=em_hide] {display:none !important;}
span[class=em_hide] {display:none !important;}
br[class=em_hide] {display:none !important;}
img[class=em_full_img] {width:100% !important; height:auto !important;}
img[class="em_logo"] {text-align:center;}
td[class=em_center] {text-align:center !important;}
table[class=em_center] {text-align:center !important;}
td[class=em_h20] {height:20px !important;} 
td[class=em_h30] { height:30px !important;}
td[class=em_h40] { height:40px !important;}
td[class=em_h50] { height:50px !important;} 
td[class=em_pad] { padding-left:15px !important; padding-right:15px !important;} 
td[class=em_pad2] { padding-left:25px !important; padding-right:25px !important;} 
img[class=img125] { max-width:125px;}
table[class=small-center] { max-width:350px!important; text-align:center!important;}
td[class=em_autoHeight] {height:auto!important;}
td[class=winebg] { background:#b92547; -webkit-border-top-right-radius:5px!important; -moz-border-radius-topright:5px!important; border-top-right-radius:5px!important; -webkit-border-bottom-left-radius:0!important;-moz-border-radius-bottomleft:0!important; border-bottom-left-radius:0!important;}
td[class=myHeading]{font-size:24px!important; text-align:center!important; }
td[class=heading]{font-size:28px!important; text-align:center!important;line-height:35px; }
}
</style>
<!-- @media only screen and (max-width: 479px) 
           {
           -->
<style type="text/css">
@media only screen and (max-width: 479px)  {
table[class=full] {width:100% !important; max-width:100%!important;}
table[class=myfull] { width:100% !important;}
table[class=notify-5-wrap] {width:100% !important;}
table[class=em_wrapper] {width:100% !important;}
td[class=fullCenter] { width:100% !important; text-align:center!important}
td[class=em_aside] {width:10px !important;}
td[class=em_hide] { display:none !important;}
table[class=em_hide] {display:none !important;}
span[class=em_hide] {display:none !important;}
br[class=em_hide] {display:none !important;}
img[class=em_full_img] {width:100% !important;height:auto !important;}
img[class="em_logo"] {text-align:center;}
td[class=em_center] {text-align:center !important;}
table[class=em_center] {text-align:center !important;}
td[class=em_h20] {height:20px !important;}  
td[class=em_h30] {height:30px !important;}
td[class=em_h40] {height:40px !important;}
td[class=em_h50] {height:50px !important;} 
td[class=em_pad] {padding-left:10px !important;padding-right:10px !important;} 
td[class=em_pad2] {padding-left:20px !important;padding-right:20px !important;} 
table[class=em_btn] {width:130px !important;}
td[class=em_btn_text] {font-size:10px !important;height:26px !important;}
a[class=em_btn_text] {line-height:26px !important;}
td[class=em_h1] {height:60px !important;font-size:1px !important;line-height:1px !important;}
td[class=em_bg] {background:none !important;}
img[class=img125] {max-width:110px;height:auto!important;}
table[class=small-center] {max-width:100%!important;text-align:center!important;}
td[class=em_autoHeight] {height:auto!important;}
td[class=myHeading]{font-size:24px!important; text-align:center!important; color:#ff0000}
td[class=heading]{font-size:26px!important; text-align:center!important;line-height:35px; }
td[class=winebg] {background:#b92547; -webkit-border-top-right-radius:5px!important; -moz-border-radius-topright:5px!important; border-top-right-radius:5px!important; -webkit-border-bottom-left-radius:0!important;-moz-border-radius-bottomleft:0!important; border-bottom-left-radius:0!important;}
 
}
</style>
 
<!--[if mso]>
<style type="text/css">
body {
   font-family:arial, helvetica, sans-serif !important;
}

table {
   font-family:arial, helvetica, sans-serif !important;
}

td {
   font-family:arial, helvetica, sans-serif !important;
}

</style>
<![endif]-->
 

</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!--Full width table start-->
    <div id="sort_them"> 

        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" object="drag-module">
            <tr mc:repeatable>
                <td bgcolor="#f6f5f5" align="center">           
                    <!-- Mobile Wrapper -->
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full"   >
                        <tr>
                            <td width="100%" align="center">                
                                
                                <div class="sortable_inner">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
                                    <tr>
                                         <td height="60" class="em_h1"></td>
                                    </tr>               
                                </table>  
                                
                                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" object="drag-module-small">
                                                            <tr>
                                                                <td align="center" width="100%" valign="middle" height="51">
                                                                             <img src="https://microsite.com/wp-content/uploads/2021/09/microsite-health.png" alt="logo" height="51" style="height:51px;"  />
                                                                                                
                                                                </td>
                                                            </tr>
                                                        </table>
                                                     
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
                                                            <tr>
                                                                 <td height="40" class="em_h40"></td>
                                                            </tr>               
                                </table> 
                                
                                </div>
                            </td>
                        </tr>
                    </table>
                    
                    <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
                        <tr>
                         
                            <td align="center" width="100%" valign="middle" class="em_pad2">    
                            
                                <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full" style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
                                    <tr>
                                        <td align="center" width="100%" valign="middle" bgcolor="#ffffff" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;" class="em_pad" >
                                        
                                    <div class="sortable_inner">
                        
                                        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
                                             <tr>
                                                 <td height="45" class="em_h40"></td>
                                            </tr>
                                        </table>
                                    
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="em_wrapper" object="drag-module-small">
                                            <tr>
                                                <td align="center" width="100%" valign="middle" >
                                                
                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                                                        <tr>
                                                            <td width="100%" align="center" style="font-size:16px; color:#222; font-family:Lato,Arial, sans-serif; font-weight:400;" >'.$value->clinic_name.'</td>
                                                        </tr>
                                                    </table>                            
                                                </td>
                                            </tr>
                                        </table>
                                     
                                        
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" object="drag-module-small">
                                            <tr>
                                                <td align="center" width="100%" valign="middle">
                                                
                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                                                        <tr>
                                                            <td width="100%" align="center" style="font-size:16px; color:#000000; font-family:Lato,Arial, sans-serif; font-weight:400; line-height:26px;"  ><b>Your Daily Sales and Marketing Summary<b></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100%" align="center" style="font-size:14px; color:#000000; font-family:Lato,Arial, sans-serif; font-weight:400; line-height:26px;"  >Check out how many leads we&apos;re generating, who scheduled appointments, and how much business was presented and closed.</td>
                                                        </tr>
                                                    </table>                            
                                                </td>
                                            </tr>
                                        </table>
                                        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
                                            <tr>
                                              <td height="35"></td>
                                            </tr>
                                        </table>
                                        <table align="" width="100%" border="0" cellspacing="0" cellpadding="0" class="full" object="drag-module-small">
                                            <tr>
                                              <td height="15" bgcolor="#fafafa"></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" align="" bgcolor="#fafafa" class="em_pad">
                                                 
                                             <table align="center" width="500" border="0" cellspacing="0" cellpadding="0" class="full" >
                                            <tr>
                                              <td valign="middle" align="left" width="250px" style="font-size:13px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:400;"  >
                                                 
                                            <span style="font-size:15px; color:#ff4081; font-family:Lato,Arial, sans-serif; font-weight:700;"> Leads Yesterday </span>
                                                
                                                 
                                              </td>
                                              <td width="150px" align="right" style="padding-right:10px;font-size:13px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:400;" > <br />
                                            <span style="font-size:15px; color:#222; font-family:Lato,Arial, sans-serif; font-weight:700;"> '.$leadsyesterday.'</span></td>
                                            
                                            </tr>
                                            </table>
                                                 
                                              </td>
                                            </tr>
                                            <tr>
                                              <td height="15" bgcolor="#fafafa"></td>
                                            </tr>
                                            <tr>
                                              <td height="3" bgcolor="#ffffff"></td>
                                            </tr>
                                               <tr>
                                              <td height="15" bgcolor="#fafafa"></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" align="center" bgcolor="#fafafa" class="em_pad">
                                                 
                                             <table align="center" width="500" border="0" cellspacing="0" cellpadding="0" class="full" >
                                            <tr>
                                              <td valign="middle" align="left" width="250px" style="font-size:13px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:400;"  >
                                                 
                                            <span style="font-size:15px; color:#ff4081; font-family:Lato,Arial, sans-serif; font-weight:700;"> Leads Last 7 Days </span>
                                                
                                                 
                                              </td>
                                              <td width="150px" align="right" style="padding-right:10px;font-size:13px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:400;"  > <br />
                                            <span style="font-size:15px; color:#222; font-family:Lato,Arial, sans-serif; font-weight:700;">'.$leadssevendays.'</span></td>
                                            
                                            </tr>
                                            </table>
                                                 
                                              </td>
                                            </tr>
                                            <tr>
                                              <td height="15" bgcolor="#fafafa"></td>
                                            </tr>
                                            <tr>
                                              <td height="3" bgcolor="#ffffff"></td>
                                            </tr>
                                               <tr>
                                              <td height="15" bgcolor="#fafafa"></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" align="center" bgcolor="#fafafa" class="em_pad">
                                                 
                                             <table align="center" width="500" border="0" cellspacing="0" cellpadding="0" class="full" >
                                            <tr>
                                              <td valign="middle" align="left" width="250px" style="font-size:13px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:400;">
                                                
                                            <span style="font-size:15px; color:#ff4081; font-family:Lato,Arial, sans-serif; font-weight:700;"> Consult Booked (30 days) </span>
                                                
                                                 
                                              </td>
                                              <td width="150px" align="right" style="padding-right:10px;font-size:13px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:400;"  > <br />
                                            <span style="font-size:15px; color:#222; font-family:Lato,Arial, sans-serif; font-weight:700;"> '.$consultsbooked.' </span></td>
                                            
                                            </tr>
                                            </table>                                         
                                              </td>
                                            </tr>
                                            <tr>
                                              <td height="15" bgcolor="#fafafa"></td>
                                            </tr>
                                            <tr>
                                              <td height="3" bgcolor="#ffffff"></td>
                                            </tr>
                                               <tr>
                                              <td height="15" bgcolor="#fafafa"></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" align="center" bgcolor="#fafafa" class="em_pad">
                                                 
                                             <table align="center" width="500" border="0" cellspacing="0" cellpadding="0" class="full" >
                                            <tr>
                                              <td valign="middle" align="left" width="250px" style="font-size:13px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:400;">
                                                 
                                            <span style="font-size:15px; color:#ff4081; font-family:Lato,Arial, sans-serif; font-weight:700;"> Treatments Open </span>
                                                
                                                 
                                              </td>
                                              <td width="150px" align="right" style="padding-right:10px;font-size:13px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:400;"  > <br />
                                            <span style="font-size:15px; color:#222; font-family:Lato,Arial, sans-serif; font-weight:700;"> $'.$treatpresented.' </span></td>
                                            
                                            </tr>
                                            </table>                                         
                                              </td>
                                            </tr>
                                            <tr>

                                            <tr>
                                              <td height="15" bgcolor="#fafafa"></td>
                                            </tr>
                                            <tr>
                                              <td height="3" bgcolor="#ffffff"></td>
                                            </tr>
                                               <tr>
                                              <td height="15" bgcolor="#fafafa"></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" align="center" bgcolor="#fafafa" class="em_pad">
                                                 
                                             <table align="center" width="500" border="0" cellspacing="0" cellpadding="0" class="full" >
                                            <tr>
                                              <td valign="middle" align="left" width="250px" style="font-size:13px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:400;">
                                                 
                                            <span style="font-size:15px; color:#ff4081; font-family:Lato,Arial, sans-serif; font-weight:700;"> Treatments Sold (30 days) </span>
                                                
                                                 
                                              </td>
                                              <td width="150px" align="right" style="padding-right:10px;font-size:13px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:400;"  > <br />
                                            <span style="font-size:15px; color:#222; font-family:Lato,Arial, sans-serif; font-weight:700;"> $'.$treatsold.' </span></td>
                                            
                                            </tr>
                                            </table>                                         
                                              </td>
                                            </tr>
                                            <tr>
                                            
                                            <tr>
                                              <td height="15" bgcolor="#fafafa"></td>
                                            </tr>
                                            <tr>
                                              <td height="3" bgcolor="#ffffff"></td>
                                            </tr>
                                               <tr>
                                              <td height="15" bgcolor="#fafafa"></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" align="center" bgcolor="#fafafa" class="em_pad">
                                                 
                                             <table align="center" width="500" border="0" cellspacing="0" cellpadding="0" class="full" >
                                            <tr>
                                              <td valign="middle" align="left" width="250px" style="font-size:13px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:400;">
                                                 
                                            <span style="font-size:15px; color:#ff4081; font-family:Lato,Arial, sans-serif; font-weight:700;"> Treatment Sold (All time) </span>
                                                
                                                 
                                              </td>
                                              <td width="150px" align="right" style="padding-right:10px;font-size:13px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:400;"  > <br />
                                            <span style="font-size:15px; color:#222; font-family:Lato,Arial, sans-serif; font-weight:700;"> $'.$treatsoldall.' </span></td>
                                            
                                            </tr>
                                            </table>                                         
                                              </td>
                                            </tr>
                                            <tr>    
                                              <td height="15" bgcolor="#fafafa"></td>
                                            </tr>
                                            <tr>
                                              <td height="3" bgcolor="#ffffff"></td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Centered Button -->
                                    
                                        <table border="0" cellpadding="0" cellspacing="0" align="center" class="em_wrapper" object="drag-module-small">
                                          <tr>
                                            <td width="100%" align="center">
                                            <table border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="mcenter" >
                                                <tr>
                                                  <td width="100%" align="center"><!-- SORTABLE -->
                                                    
                                                    <div class="sortable_inner">
                                                      
                                                      <table border="0" cellpadding="0" cellspacing="0" class="mcenter" width="100%" >
                                                        <tr>
                                                          <td align="center" valign="middle" height="40" bgcolor="#ff4081" style="font-size:14px;  font-family:Lato,Arial, sans-serif; font-weight:400; color:#ffffff; border-radius:5px; background:#ff4081; padding-left:15px; padding-right:15px;" >
                                                      <a href="https://lms.microsite.com/admin/crm-customers" target="_blank" style="text-decoration:none; color:#ffffff; line-height:18px; display:block;" object="link-editable" > View Your New Leads </a></td>
                                                        </tr>
                                                      </table>
                                                      <table border="0" cellpadding="0" cellspacing="0" class="mcenter" width="100%" >
                                                        <tr>
                                                          <td align="center" valign="middle" height="40" bgcolor="#ff4081" style="font-size:14px;  font-family:Lato,Arial, sans-serif; font-weight:400; color:#ffffff; border-radius:5px; background:#ff4081; padding-left:15px; padding-right:15px;" >
                                                      <a href="https://lms.microsite.com/admin/crm-customers?view=consults" target="_blank" style="text-decoration:none; color:#ffffff; line-height:18px; display:block;" object="link-editable" > View Upcoming Consults </a></td>
                                                        </tr>
                                                      </table>
                                                      <table border="0" cellpadding="0" cellspacing="0" class="mcenter" width="100%" >
                                                        <tr>
                                                          <td align="center" valign="middle" height="40" bgcolor="#ff4081" style="font-size:14px;  font-family:Lato,Arial, sans-serif; font-weight:400; color:#ffffff; border-radius:5px; background:#ff4081; padding-left:15px; padding-right:15px;" >
                                                      <a href="https://lms.microsite.com/" target="_blank" style="text-decoration:none; color:#ffffff; line-height:18px; display:block;" object="link-editable" > View Your Dashboard </a></td>
                                                        </tr>
                                                      </table>
                                                    </div></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                        </table>
                                    <!-- End Button -->
                                         
                                        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
                                            <tr>
                                              <td height="38" class="em_h30"></td>
                                            </tr>
                                        </table>    </div>  
                                            
                                        </td>
                                    </tr>
                                </table>
                                
                            </td>
                         
                        </tr>
                    </table>
                  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
                            <tr>
                              <td height="40" class="em_h40"></td>
                            </tr>
                    </table>
                    
                 
           
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" object="drag-module">
                      <tr mc:repetable>
                        <td align="center" valign="top" >
                          <!-- SORTABLE -->
                            
                                <table align="center" class="full" width="400" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;" >
                                 <tr>
                                  <td>
                                  <div class="sortable_inner">
                                                 
                                    <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
                                        <tr>
                                          <td valign="top" align="center" style="font-family:Lato, Arial, sans-serif; font-weight:400; font-size:11px; color:#aaaaaa; line-height:22px;" >
                                          &copy; '. date("Y") .' The Executive Whisper, LLC. Microsite and Microsite Health are trademarks of The Executive Whisper. All Rights Reserved.
You are receiving this email because you are a client of Microsite Health. You may <a href="https://lms.microsite.com/unsubcribe/'.$value->callrail_company.'?uemail=[emailaddress]" target="_blank">unsubscribe by clicking here</a>
                                          </td>
                                        </tr>
                                    </table>
                                    
                                    <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
                                        <tr>
                                          <td height="73" class="em_h1"></td>
                                        </tr>
                                    </table>
                                    </div>
                                        </td>
                                    </tr>
                                </table>
                            
                        </td>
                     </tr>
                    </table>   
                </td>
            </tr>
        </table>


    </div>
</body>
</html> ';

                   // $tolist = ['ashesh@microsite.com', 'lgalanis@microsite.com','bhanekamp@microsite.com'];
                    $tolist = $value->reportrecipients;
                    $tolist = explode(",", $tolist);
                    //echo "<pre>";print_r($tolist);exit;
                    //$tolist = ['ashesh@microsite.com'];
                    foreach($tolist as $key=>$to){
                        $newhtml = str_replace("[emailaddress]", $to, $html);
                        Mail::send(array(), array(), function ($message) use ($newhtml,$value,$to) {
                          $message->to($to)
                            ->subject($value->clinic_name.': Your New Patient Opportunities Daily Summary')
                            ->from('noreply@microsite.com', 'Microsite Health')
                            ->setBody($newhtml, 'text/html');
                        });
                    }
                                         
                    $this->info('Successfully sent daily report to everyone.');
                }
                
            }
        }
        
    }
}
