@extends('layouts.admin')

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-chat.css')}}">
@endsection
@section('content')
<style type="text/css">
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
    .highlight{
        color: #ff4081 !important;
    }
   
      
</style>
<div class="chat-application">
  <div class="chat-content-head">
    <div class="header-details">
      <h5 class="m-0 sidebar-title"><i class="material-icons app-header-icon text-top">chat</i> Inbox</h5>
    </div>
  </div>
  <div class="app-chat">
        <div class="content-area content-right">
            <div class="app-wrapper">
                <div class="card card card-default scrollspy border-radius-6 fixed-width">
                    <div class="card-content chat-content p-0">
                        <div class="sidebar-left sidebar-fixed animate fadeUp animation-fast">
                            <div class="sidebar animate fadeUp">
                                <div class="sidebar-content">
                                    <div id="sidebar-list" class="sidebar-menu chat-sidebar list-group position-relative" >
                                        <div class="sidebar-list-padding app-sidebar sidenav" id="chat-sidenav" style="display:none;">
                                        <!-- Sidebar Search -->
                                      <div class="sidebar-search animate fadeUp">
                                        <div class="search-area">
                                          <i class="material-icons search-icon">search</i>
                                          <input type="text" placeholder="Search Doctor/Practice/Lead" class="app-filter" id="chat_filter">
                                        </div>
                                        <span class="badge badge pill red">{{ $crmCustomer->count() }}</span>                                        
                                      </div>
                                      <!--/ Sidebar Search -->
                                        <!-- Sidebar Content List -->
                                         <div class="sidebar-content sidebar-chat" id="leftinbox">
                                            <div class="loading-area center">
                                                <div class="loader">
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>              
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <!-- Sidebar Content End List -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="chatDiv" style="width:100%">
                            
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

                        </div>
                    </div>
                </div>
            </div>
        </div>
  </div>
</div>
@endsection


{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/scripts/app-chat.js')}}"></script>
<script>
$(document).ready(function (e) {
    $('.sidebar-search').hide();
    $.ajax({
        url: 'leftinbox',
        type: "POST",
        headers: {
            'X-CSRF-Token': '{{ csrf_token() }}',
        },
        beforeSend: function(){
           //$('.preloader-wrapper').show();
           $('.loading-area').show();
           $('.chat-sidebar').hide();
        },
        success: function(data){  
        if(data)
            {
                $('.sidebar-search').show();
                $('#chat-sidenav').show();
                $('#leftinbox').html(data); 
                $('.delay-0').trigger('click');      
                $('.delay-0').addClass('select');      
            }
        },
        complete: function(){
            $('.loading-area').hide(); 
            $('.chat-sidebar').show();          
        }, 
        error: function(){
              alert("failure From php side!!! leftinbox ");
        }
        });
});

$(document).on('click', '.chat-user', function (e) {
    console.log($(this));
      var leadid = $(this).data('id');
      $('.select').removeClass('select')
      $(this).addClass('select');
      $('.loading-area').show();
      e.preventDefault(); 
      $.ajax({
            url: 'inbox',
            data: {"leadId": leadid},
            type: "POST",
            cache: false,
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            beforeSend: function(){  
                $('.loading-area').show();                 
            },
            success: function(data){ 
                if(data)
                { 
                 $('#chatDiv').html(data);
                 $('.loading-area').hide();
                 }      
            }, 
            complete: function(){
                $('.loading-area').hide();
            }, 
            error: function(){
                  alert("failure From php side!!! click call ");
             }
            }); 
    });

function autoRefreshChat(){
    $.ajax({
        url: 'leftinbox',
        type: "POST",
        headers: {
            'X-CSRF-Token': '{{ csrf_token() }}',
        },
        success: function(data){  
        if(data)
            {
                $('#leftinbox').html(data);  
                $('.delay-0').trigger('click'); 
                $('.delay-0').addClass('select');      
            }
        }, 
        error: function(){
              alert("failure From php side!!! leftinbox ");
        }
        });    
}
setInterval(function(){
    autoRefreshChat();
},60000);
</script>
@endsection