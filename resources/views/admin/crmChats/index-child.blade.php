<!-- Content Area -->
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
<div class="chat-content-area animate fadeUp">
  <!-- Chat header -->
  <div class="chat-header">
    <div class="row valign-wrapper">
      <a href= "crm-customers/{{$chats[0]->customer->id}}/edit#sms" >
        <div class="col media-image online pr-0">
          <img src="{{asset('images/user/user.png')}}" alt="" class="circle z-depth-2 responsive-img">
        </div>
        <div class="col">
          <p class="m-0 blue-grey-text text-darken-4 font-weight-700" style="color:#ff4081 !important">{{$chats[0]->customer->first_name}} {{$chats[0]->customer->last_name}}<i class="material-icons">edit</i></p>
          <p class="m-0 chat-text truncate">{{$crmcustomer[0]->clinic->dr_name}}</p>
          <p class="m-0 chat-text">{{$crmcustomer[0]->clinic->clinic_name}}</p>
        </div>
      </a>
    </div>
  </div>
  <!--/ Chat header -->



  <!-- Chat content area -->
   <div class="chat-area">
    <div class="chats">
      <div class="chats">
        <div class="chat">
            <div class="chat-avatar">
            </div>    
        </div>         
        @foreach($chats as $id => $customer)        

        @if($customer->inbound)
        
        <div class="chat">
          <div class="chat-avatar">
            <a class="avatar">
              <img src="{{asset('images/user/user.png')}}" class="circle" alt="avatar" />
            </a>
          </div>
          <div class="chat-body">
            
            
            <div class="chat-text">
              <p>{{$customer->chat}}</p>
              
            </div>
            @if(!$customer->read)
              <span class="badge badge pill red">New</span>
            @endif
            {{ $customer->created_at->format('m/d/Y g:i A') }}
            
          </div>
        </div>
        @else
        <div class="chat chat-right">
          <div class="chat-avatar">
            <a class="avatar">
              <img src="{{asset('images/user/microsite-logo.png')}}" class="circle" alt="avatar" />
            </a>
          </div>
          <div class="chat-body">
            
            <div class="chat-text">
              <p>{{$customer->chat}}</p> 
            </div>
            <span class="m-l-lg">
                @if($customer->delivered)
                    <i class="fas fa-check-double" data-rel="tooltip" title="Delivered"></i>
                @else
                    <i class="fas fa-exclamation-circle" data-rel="tooltip" title="Not Delivered"></i>
                @endif

                @if($customer->user_id==1 && $customer->inbound==0)
                {{ $customer->created_at->format('m/d/Y g:i A') }}                                        
                @endif
            </span>
            
            
          </div>
        </div>
        @endif

        

        @endforeach

      </div>
    </div>
  </div>
  <!--/ Chat content area -->

</div>
<!--/ Content Area -->