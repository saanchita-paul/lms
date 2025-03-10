<!-- Sidebar Search -->
  <!-- <div class="sidebar-search animate fadeUp">
     <div class="search-area">
        <i class="material-icons search-icon">search</i>
              <input type="text" placeholder="Search Chat" class="app-filter" id="chat_filter">
      </div>                                        
   </div> -->
 <!--/ Sidebar Search -->
<div class="chat-list">
        @foreach($crmCustomer as $id => $customer)
        <div class="chat-user animate fadeUp delay-{{$id}} " data-id="{{$customer->id}}">
             <div class="user-section">
          <div class="row valign-wrapper">
            <div class="col s2 media-image online pr-0">
              <img src="{{asset('images/user/user.png')}}" alt="" class="circle z-depth-2 responsive-img">
            </div>
            <div class="col s10">
              <p class="m-0 blue-grey-text text-darken-4 font-weight-700">{{$customer->first_name}} {{$customer->last_name}} </p>
              <p class="m-0 info-text">{{$customer->dr_name}}</p>  
              <p class="m-0 clinic-text">{{$customer->clinic_name}}</p>        
            </div>
          </div>
        </div>          
       </div>         
        @endforeach
          
</div>