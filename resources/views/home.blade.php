@extends('layouts.admin')
{{-- extend layout --}}

{{-- page title --}}
@section('title','Blank Page')

{{-- page content --}}
@section('content')
<style type="text/css">
    .dashboard .clinic-filter input[type="checkbox"]{
        opacity: 1 !important;
        top: 11px;
    }
    .dashboard .clinic-filter .ms-options-wrap{
        float: left;
        width: 80%;
    }
    .ms-options-wrap > button:focus, .ms-options-wrap > button{
        height: 35px;
    }
    .custom-links .border-radius-6{
        border:2px solid #f15ab7;
        padding: 15px;
    }
    .custom-links .border-radius-6 h5{
        color: #f15ab7;
    }

    .custom-links .border-radius-6 i{
        color: #f15ab7;
        font-size: 80px;
        padding-bottom: 15px;
    }
    .custom-links{
        padding-bottom: 155px;
    }

    .loading-area {
    display: grid;
    place-items: center;
    height: 100vh;
    }
    .loader div {
        height: 30px;
        width: 30px;
        border-radius: 50%;
        transform: scale(0);
        animation: animate 1.5s ease-in-out infinite;
        display: inline-block;
        margin: .5rem;
    }
    .loader div:nth-child(0) {
        animation-delay: 0s;
    }
    .loader div:nth-child(1) {
        animation-delay: 0.2s;
    }
    .loader div:nth-child(2) {
        animation-delay: 0.4s;
    }
    .loader div:nth-child(3) {
        animation-delay: 0.6s;
    }
    .loader div:nth-child(4) {
        animation-delay: 0.8s;
    }
    .loader div:nth-child(5) {
        animation-delay: 1s;
    }
    .loader div:nth-child(6) {
        animation-delay: 1.2s;
    }
    .loader div:nth-child(7) {
        animation-delay: 1.4s;
    }
    @keyframes animate {
        0%, 100% {
            transform: scale(0.2);
            background-color: #43A047;
        }
        40% {
            transform: scale(1);
            background-color: #1E88E5;
        }
        50% {
            transform: scale(1);
            background-color: #5d5390;
        }
    }
    
      
      
</style>
<?php
$user = auth()->user()->roles;
$userrole = $user[0]['title'];

?>
<div class="section">
    <?php
    if($userrole != 'Onboarding'){
    ?>    
    <div class="card card-alert green darken-1 white-text">
        <div class="card-content">
          <p>
            <span class="font-weight-600">Treatment Sold (CRM - All-time) :<i class="material-icons">attach_money</i><span class=" font-weight-600 wonalltime"></span></span>
            
          </p>
        </div>
    </div>
       
    <div class="card card-alert blue darken-1 white-text wonlifetimeshow" style="display:none">
        <div class="card-content">
          <p>
            <span class="font-weight-600">Treatment Sold (CSV - Lifetime) :<i class="material-icons">attach_money</i><span class=" font-weight-600 wonlifetime"></span>
          </p>
        </div>
    </div>
        
    <div class="card">
        
        <div class="loading-area center">
            <div class="loader">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="card-content dashboard dashboardDiv" style="display: none;">
            <div id="card-stats" class="pt-0">
                <div class="row">
                    <div class="col s12 m6 l3">
                        <div class="card animate fadeLeft">
                          <div class="card-content dbox1 white-text">
                            <a class="white-text" href="{{ route('admin.reports.index') }}">
                                <p class="no-margin medium-small">{{ Carbon\Carbon::now()->subDays(30)->format('M/d') }} - {{ Carbon\Carbon::now()->format('M/d') }}</p>
                                <p class="card-stats-title"><i class="fa fa-user-plus"></i> New Leads</p>
                                <h4 class="card-stats-number white-text newsleads"></h4>
                            </a>  
                           </div>
                       
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="card animate fadeLeft">
                            <div class="card-content dbox2 white-text">
                                <a class="white-text" href="{{ route('admin.reports.index', ['report_type'=>'2']) }}">    
                                    <p class="no-margin medium-small">{{ Carbon\Carbon::now()->subDays(30)->format('M/d') }} - {{ Carbon\Carbon::now()->format('M/d') }}</p>
                                    <p class="card-stats-title"><i class="fa fa-handshake"></i> Consultations Booked</p>
                                    <h4 class="card-stats-number white-text booked"></h4>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="card animate fadeRight">
                            <div class="card-content dbox3 white-text">
                                <a class="white-text" href="{{ route('admin.reports.index', ['report_type'=>'3']) }}">
                                    <p class="no-margin medium-small">{{ Carbon\Carbon::now()->subDays(30)->format('M/d') }} - {{ Carbon\Carbon::now()->format('M/d') }}</p>
                                    <p class="card-stats-title"><i class="fa fa-calendar-check"></i> Consultations Showed</p>
                                    <h4 class="card-stats-number white-text showed"></h4>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="card animate fadeRight">
                           <div class="card-content dbox4 white-text">
                                <a class="white-text" href="{{ route('admin.reports.index', ['report_type'=>'4']) }}">
                                    <p class="no-margin medium-small">{{ Carbon\Carbon::now()->subDays(30)->format('M/d') }} - {{ Carbon\Carbon::now()->format('M/d') }}</p>
                                    <p class="card-stats-title"><i class="fa fa-calendar-times"></i> Consultations Not Showed</p>
                                    <h4 class="card-stats-number white-text notshowed"></h4>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l3">
                        <div class="card animate fadeLeft">
                           <div class="card-content dbox5 white-text">
                                <a class="white-text" href="{{ route('admin.reports.index', ['report_type'=>'5']) }}">
                                    <p class="no-margin medium-small">All Time</p>
                                    <p class="card-stats-title"><i class="fas fa-user-md"></i> Pending Acceptance</p>
                                    <h4 class="card-stats-number white-text"><i class="material-icons">attach_money</i><span class="presented"></span>
                                    </h4>
                                </a>
                           </div>
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="card animate fadeLeft">
                            <div class="card-content dbox6 white-text">
                                <a class="white-text" href="{{ route('admin.reports.index', ['report_type'=>'6']) }}">
                                    <p class="no-margin medium-small">{{ Carbon\Carbon::now()->subDays(30)->format('M/d') }} - {{ Carbon\Carbon::now()->format('M/d') }}</p>
                                    <p class="card-stats-title"><i class="fas fa-user-lock"></i> Treatment Sold</p>
                                    <h4 class="card-stats-number white-text"><i class="material-icons">attach_money</i><span class="won"></span>
                                    </h4>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="card animate fadeRight">
                            <div class="card-content dbox7 white-text">
                                <a class="white-text" href="{{ route('admin.crm-customers.index',['view'=>'table','show'=>'followup']) }}">  
                                    <p class="card-stats-title">All Time</p>
                                    <p class="card-stats-title"><i class="fas fa-hand-holding-heart"></i> Consultations Follow-Up</p>
                                    <h4 class="card-stats-number white-text followup"></h4>
                                </a>
                          
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="card animate fadeRight">
                           <div class="card-content dbox8 white-text">
                                <a class="white-text" href="{{ route('admin.reports.index', ['report_type'=>'7']) }}"> 
                                    <p class="no-margin medium-small">All Time</p>  
                                    <p class="card-stats-title"><i class="fas fa-user-slash"></i> Leads Nurturing</p>
                                    <h4 class="card-stats-number white-text nurturing"></h4>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
            <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    
            </div>

            <?php
  
            if($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager'){
            ?> 
            <div class="row">
                <div class="card card-alert darken-1 white-text col s12 m6 l3 p-0">
                    <div class="card-content dbox2">
                      <p>
                        <span class="font-weight-600 bookingpercentage"></span>
                      </p>
                    </div>
                </div>

                <div class="card card-alert darken-1 white-text col s12 m6 l3 right p-0">
                    <div class="card-content dbox3">
                      <p>
                        <span class="font-weight-600 showedpercentage"></span>
                      </p>
                    </div>
                </div>
            </div>    
            <?php }
            ?>
        </div>
    </div>
    <?php } ?>
    @if(!empty($clinicurl->custom_message))
    <div class="card">
        <div class="card-content dashboard">
            <div id="card-stats" class="pt-0">
                <div class="row">
                    <?php
                    if($userrole == 'Onboarding'){
                    ?> 
                    <h4 style="font-size: 2.0rem;"><b>Welcome to Microsite</b></h4>
                    <?php }else{ ?>
                    <h4 style="font-size: 2.0rem;"><b>Marketing & Sales Highlights</b></h4>   
                    <?php } ?>    
                    <p>@if(!empty($clinicurl->custom_message)){!! $clinicurl->custom_message !!}@endif</p>   
                </div>
            </div>
        </div>
    </div>     
    @endif
    <?php
    if(count($clinics) > 1){
    //if($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager'){
    ?>                        
    <div class="card">
        <div class="card-content dashboard">
            <div id="card-stats" class="pt-0">
                <div class="row">
                    
                    <div class="clinic-filter col s12"> 
                    <form method="POST" id="" action="{{ route('admin.crm-customers.settings') }}" enctype="multipart/form-data" class="">
                       @csrf
                            <select id="filter_clinic" class="select2 browser-default select2-hidden-accessible" name="filter_clinic[]" multiple>
                                
                                @foreach($clinics as $id => $clinic)
                                    <option value="{{ $clinic->id }}" @foreach($selectedclinic as $selected) @if($selected == $clinic->id) selected @endif @endforeach >{{ $clinic->clinic_name }} - {{ $clinic->dr_name }}</option>
                                @endforeach
                                
                            </select>
                            <button type="submit" name="submit" class="btn btn-success cyan"><i class='material-icons'>send</i> Apply</button>
                              
                           
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <?php } ?>  
    <h4 style="padding-top: 35px;font-size: 2.0rem;"><b>Quick Links</b></h4>
    <div class="row custom-links">
        @if(!empty($clinicurl->marketingdashboardurl))
        <a href="{{$clinicurl->marketingdashboardurl}}" target="_blank" class="hide">  
            <div class="col s12 m4 14 card-width">
                <div class="border-radius-6">
                    <div class="card-content center-align">
                        <i class="fas fa-chart-bar"></i>
                        <h5 class="m-0"><b>Marketing Dashboard</b></h5>
            
                    </div>
                </div>
            </div>
        </a>
        @endif
        @if(!empty($clinicurl->schedulemeetingurl) && $clinicurl->schedulemeetingurl != "https://app.copper.com/public/meeting-scheduler/Microsite%20Health/kowens/262837:18d6317f-f20c-4354-917f-a0f71c3add9b")
        <a href="{{$clinicurl->schedulemeetingurl}}" target="_blank"> 
            <div class="col s12 m4 l4 card-width">
                <div class="border-radius-6">
                    <div class="card-content center-align">
                        <i class="fas fa-calendar-check"></i>
                        <h5 class="m-0"><b>Schedule Strategy Meeting</b></h5>
                
                    </div>
                </div>
            </div>
        </a>
        @endif    
        @if(!empty($clinicurl->salestrainingurl))
        <a href="{{$clinicurl->salestrainingurl}}" target="_blank">
            <div class="col s12 m4 l4 card-width">
                <div class="border-radius-6">
                  <div class="card-content center-align">
                    <i class="fas fa-graduation-cap"></i>
                    <h5 class="m-0"><b>Marketing & Sales Training</b></h5>
                    
                  </div>
                </div>
            </div>
        </a>
        @endif     
    </div>

</div>

@endsection
@section('scripts')
@parent
 

<!-- JS & CSS library of MultiSelect plugin -->
<script src="js/jquery.multiselect.js"></script>
<link rel="stylesheet" href="css/jquery.multiselect.css">
<script>
$('#filter_clinic').multiselect({
    columns: 1,
    placeholder: 'Select Clinic',
    search: true,
    selectAll: true
});

$(document).ready(function(){
    dashboardAjax(); 
});
function dashboardAjax(){
    $.ajax({
     url: 'admin/dashboardData',
     type: 'get',
     beforeSend: function(){
           //$('.preloader-wrapper').show();
           $('.loading-area').show();
           $('.dashboardDiv').hide();
    },
     success: function(response){        
       $('.newsleads').html(response.newleads);        
        if(response.presented)
        {             
            var presented = number_format(response.presented,2,'.',',');
            $('.presented').html(presented);
        }
        else
        {
        var presented = number_format(response.presented,2,'.',',');
            $('.presented').html(presented);
        }

        if(response.won)
        {             
            var won = number_format(response.won,2,'.',',');
            
            $('.won').html(won);
        }
        else
        {
        var won = number_format(response.won,2,'.',',');
            
            $('.won').html(won);
        }    
        if(response.wonalltime)
        {
            var wonalltime = number_format(response.wonalltime,2,'.',',');
            $('.wonalltime').html(wonalltime);
        }
        else
        {
            var wonalltime = number_format(response.wonalltime,2,'.',',');
            $('.wonalltime').html(wonalltime);
        }
        $('.newsleads').html(response.newleads);
        if(response.booked > 0)
        {
          var bookingpercentage = parseFloat((response.booked*100)/response.newleads,2).toFixed(2)+'%';           
        }
        else
        {
          var bookingpercentage = 'NA';  
        }
        if(response.showed > 0)
        {
            var showedpercentage = parseFloat((response.showed*100)/response.booked,2).toFixed(2)+'%';
        }
        else
        {
            var showedpercentage = "NA";
        }
        $('.bookingpercentage').html('Consultations Booked : '+bookingpercentage);
        $('.showedpercentage').html('Consultations Showed : '+showedpercentage);        
        $('.booked').html(response.booked);
        $('.showed').html(response.showed);
        $('.notshowed').html(response.notshoweddata);
        $('.nurturing').html(response.nurturing);
        $('.followup').html(response.followup);        
        
        if(response.wonlifetime > 0)
        {
            var wonlifetime = number_format(response.wonlifetime,2,'.',',');
            $('.wonlifetime').html(wonlifetime);
            $('.wonlifetimeshow').show();
        }
        else
        {
            $('.wonlifetimeshow').hide();
        }
     },
     complete: function(){
           // $('.preloader-wrapper').hide(); 
            $('.loading-area').hide(); 
            $('.dashboardDiv').show();          
        }
    });
}

function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

</script>
@endsection