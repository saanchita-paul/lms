{{-- extend layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','App Kanban')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/jkanban/jkanban.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/quill/quill.snow.css')}}">
@endsection

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-kanban.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery.mCustomScrollbar.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery.datetimepicker.css')}}">

@endsection 
{{-- page content --}}
@section('content')
<?php 
$user = auth()->user()->roles;
$userrole = $user[0]['title'];
?>

<div class="row">
      
    <div class="clinic-filter col s6 offset-s6">
        <form method="POST" id="" action="{{ route('admin.crm-customers.settings') }}" enctype="multipart/form-data" class="">
           @csrf
             <?php
                if(count($clinics) > 1){
                //if($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager'){?>

                <select id="boot-multiselect-demo" class="select2 browser-default select2-hidden-accessible" name="filter_clinic[]" data-placeholder="Select a clinic..." multiple="multiple">
                    
                    @foreach($clinics as $id => $clinic)
                        <option value="{{ $clinic->id }}" @foreach($selectedclinic as $selected) @if($selected == $clinic->id) selected @endif @endforeach >{{ $clinic->clinic_name }} - {{ $clinic->dr_name }}</option>
                    @endforeach
                    
                </select>
                <button type="submit" name="submit" class="btn btn-success cyan"><i class='material-icons'>send</i> Apply</button>
            <?php } ?>        
           <a class="btn btn-success" href="{{ route('admin.crm-customers.index','view=table') }}">
            <i class="material-icons">grid_on</i> Table View
            </a>     
        </form>

        
    </div>
</div>


<!-- Basic Kanban App -->

<section id="kanban-wrapper" class="section">
  <div class="kanban-overlay"></div>
  <div class="row">
    <div class="s12">
      
      <div id="kanban-app" class="custom-scrollbar-xcss"></div>
    </div>
  </div>

  <!-- User new mail right area -->
  <div class="kanban-sidebar">
    <div class="card quill-wrapper">
      <div class="card-content pt-0">
        <div class="card-header display-flex pb-2">
          <h3 class="card-title">More Details</h3>
          <div>
                <a href="crm-customers/leadid/edit" target="_blank" id="dynamiclink" class="btn btn-floating btn-medium pulse"><i class="material-icons icon-demo">mode_edit</i></a>
          </div>
          <div class="close close-icon">
            <i class="material-icons">close</i>
          </div>
        </div>
        <div class="divider"></div>
        <!-- form start -->
        <form class="edit-kanban-item mt-10 mb-10">
            
          <div class="input-field">
            <input type="text" class="edit-kanban-item-title validate" id="edit-item-title" placeholder="Full Name" readonly>
            <label for="edit-item-title">Full Name</label>
          </div>
          
          <div class="input-field">
            <label for="edit-item-date">Consult Booked Date</label><br>
            <input type="text" class="edit-kanban-item-datetime" value="" id="datetimepicker_mask" name="consultation_booked_date"  autocomplete="off">
            
          </div>
          <div class="row">
            <div class="col s6">
              <div class="input-field mt-0">
                <span>Stage</span>
                <select class="browser-default" name="status_id" id="status_id">
                    <option class="red-text" value=""></option>
                    <?php
                    $view = app('request')->input('view');
                    if($view !="consults"){
                    ?>
                    <option class="green-text" value="1">New Lead</option>
                    <option class="orange-text" value="5">In Discussion</option>
                    <option class="blue-text" value="2">Attempt One</option>
                    <option class="blue-text" value="3">Attempt Two</option>
                    <option class="blue-text" value="4">Attempt Three Plus</option>
                    <?php if($userrole == 'Lead Center Associate' || $clinics->contains('nurture_automation', "Yes") ){ ?>
                    <option class="blue-text" value="17">Nurturing (Only FORMS)</option>
                    <?php } ?>
                    <option class="purple-text" value="6">Practice Follow-Up</option>
                    <option class="red-text" value="9">Not Interested</option>
                    <option class="greendark-text" value="12">Consultation Booked</option>
                    <option class="pink-text" value="16">Existing Patient</option>
                    <?php } 
                    if($view =="consults"){
                    ?>
                    <option class="greendark-text" value="12">Consultation Booked</option>
                    <option class="teal-text" value="13">No Showed</option>
                    <option class="red-text" value="14">Cancellation</option>
                    <option class="darkgreen-text" value="15">Pending Acceptance</option>
                    <option class="pink-text" value="16">Existing Patient</option>
                    <?php } 
                    ?>
                </select>
              </div>
            </div>
            
          </div>


          <?php 
                if($view =="consults"){
          ?>
          <div class="row">
            <div class="col s6">
              <div class="input-field">
                <span>{{ trans('cruds.crmCustomer.fields.value') }}</span>
                <div class="input-container">
                    <i class="material-icons dp48 icon">attach_money</i>
                    <input class="edit-kanban-item-value" type="text" name="value" id="value" value="" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="col s6"> 
              <div class="form-group input-field">
                <span>{{ trans('cruds.crmCustomer.fields.won_lost') }}</span>
                <select class="browser-default" name="won_lost" id="won_lost">
                    <option value="" >{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\CrmCustomer::WON_LOST_SELECT as $key => $label)
                        <option value="{{ $key }}" }}>{{ $label }}</option>
                    @endforeach
                </select>
                
              </div>
            </div>
          </div>  


          <div class="row">
            <div class="col s6">
              <div class="input-field mt-0">
                <span>{{ trans('cruds.crmCustomer.fields.deal_status') }}</span>
                <select class="browser-default" name="deal_status" id="deal_status">
                    <option value="" >{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\CrmCustomer::DEAL_STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" >{{ $label }}</option>
                    @endforeach
                </select>
              </div>
            </div>
          </div>      
          <?php }else{
          ?>
          <div class="row" id="reasondropdown">
            <div class="col s12">
              <div class="input-field mt-0">
                <span>{{ trans('cruds.crmCustomer.fields.reason') }}</span>
                <select class="form-control {{ $errors->has('reason') ? 'is-invalid' : '' }}" name="reason" id="reason" >
                    <option value  {{ old('reason', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\CrmCustomer::REASON_SELECT as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
              </div>
            </div>
          </div>
          <?php  
          } 
          ?>
          <!-- Compose mail Quill editor -->
          <div class="input-field">
            <span>Note</span>
            <div class="snow-container mt-2">
              <div class="compose-editor" id="identifier"></div>
              
              <div class="compose-quill-toolbar">
                <span class="ql-formats mr-0">
                  <button class="ql-bold"></button>
                  <button class="ql-italic"></button>
                  <button class="ql-underline"></button>
                  <button class="ql-link"></button>
                  <button class="btn btn-small cyan btn-comment waves-effect waves-light ml-25 hide" id="docomment">Save Note</button>
                </span>
              </div>
            </div>
            <div>
                <span id="hiddenArea"></span>
            </div>    
          </div>
          <input type="hidden" class="edit-kanban-item-id validate" id="edit-item-id" value="">
        </form>
        <div class="card-action pl-0 pr-0 center">
            <button class="btn-small blue waves-effect waves-light" id="savelead">
            <span>Save</span>
          </button>
        </div>
        <!-- form start end-->
      </div>
    </div>
  </div>
</section>
<!--/ Sample Project kanban -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/jkanban/jkanban.min.js')}}"></script>
<script src="{{asset('vendors/quill/quill.min.js')}}"></script>
<script src="{{asset('vendors/sweetalert/sweetalert.min.js')}}"></script>

@endsection

{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/scripts/app-kanban.js')}}"></script>
<script src="{{asset('js/jquery.datetimepicker.js')}}"></script>

<script type="text/javascript">
var priorDate = moment().subtract(30, "days").format('YYYY/MM/DD');
    $('#datetimepicker_mask').datetimepicker({
        format: 'm/d/Y h:i A',
        hours12:false,
        ampm: true, // FOR AM/PM FORMAT
        formatTime: 'A g:i',
        minDate: priorDate,
        allowTimes:[
          'AM 7:00',  
          'AM 8:00',
          'AM 9:00',
          'AM 10:00',
          'AM 11:00',
          'PM 12:00',
          'PM 13:00',
          'PM 14:00',
          'PM 15:00',
          'PM 16:00',
          'PM 17:00',
          'PM 18:00',
          'PM 19:00',
          'PM 20:00'
        ]
    });
    
    $(document).ready(function () {
        M.AutoInit();
        //var DateField = MaterialDateTimePicker.create($('#datetime'));
        $('.tooltipped').tooltip();
        $("#docomment").click(function(e){
            e.preventDefault();
            //$("#hiddenArea").html($(".ql-editor").html());
            if($(".ql-editor").html() == '<p><br></p>'){
               swal({
                    title: 'Please add a note',
                    icon: 'warning'
                })
                return false;
            }


            
        });    
        $("#savelead").click(function(e){
            if($('#status_id').val() == 12 && $('#datetimepicker_mask').val() == ''){
                
                swal({
                    title: 'Please add Consultation Booked Date',
                    icon: 'warning'
                })
                return false;
            }

            if($('#status_id').val() == 9 && $('#reason').val() == ''){
                
                $('select[name="reason"]').siblings('input').first().css( "border-bottom", "2px solid red" );
                $('select[name="reason"]').siblings('input').first().css( "box-shadow", "none" );

                swal({
                        title: 'Please select Lost Reason (Why patient did not schedule)',
                        icon: 'warning'
                    })
                
                return false;
            }


            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'crm-customers_move',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'status_id': $('#status_id').val(),
                    'leadid': $('#edit-item-id').val(),
                    'consultation_booked_date': $('#datetimepicker_mask').val(),
                    'leadvalue': $('#value').val(),
                    'won_lost': $('#won_lost').val(),
                    'deal_status': $('#deal_status').val(),
                    'reason': $('#reason').val(),
                },
                cache: false,
                success: function (result) {
                    console.log("It's done.");
                },
                error: function() {
                    console.log("Uh oh! There's an error!");
                }
            });
            if($(".ql-editor").html() != '<p><br></p>'){
                var id = $('#edit-item-id').val();
                var arrid = id.split("_");
                $.ajax({
                    type: 'POST',
                    url: 'crm-notes',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        'customer_id': arrid[1],
                        'note': $(".ql-editor").html(),
                    },
                    cache: false,
                    success: function (result) {
                        //$("#hiddenArea").append("<div class='card-content green-text'>SUCCESS : Note Added</div>");
                        console.log("It's done.");
                    },
                    error: function() {
                        //$("#hiddenArea").append("<div class='card-content red-text'>Failed</div>")
                        console.log("Uh oh! There's an error!");
                    }
                });

            }

            $(".kanban-overlay").removeClass("show");
            $(".kanban-sidebar").removeClass("show");
            
          });

    });

   $(document).ready(function() {
        // auto refresh page after 1 second = 1000 milliseconds
        setInterval('refreshkanbanPage()', 60000);
        $('#reasondropdown').hide();
        $('#status_id').on('change', function () {
            $.each($(this).find('option:selected'), function (index, item) {
                var selected = $(item).val();
                if (selected == '9') {
                    $('#reasondropdown').show();
                    return true;
                }else{
                    $('#reasondropdown').hide();
                    return true;
                }
            });
        });
       
    });
 
    function refreshkanbanPage() {
        
        var kanban_board_data = '';
        $.ajax({
        type: 'get',
        url: 'crm-customers_updated?view=<?php echo app('request')->input('view');?>',
        success: function(data) {
            $('#kanban-app').html('');
            
            var kanban_board_data = JSON.stringify(data);
            var kanban_board_data = data;
            console.log(typeof data);
            console.log(kanban_board_data);
        
            var kanban_curr_el, kanban_curr_item_id, kanban_item_title, kanban_data, kanban_item, kanban_users, kanban_curr_item_date;
            // Kanban Board and Item Data passed by json
        
            // Kanban Board
            var KanbanExample = new jKanban({
                element: "#kanban-app", // selector of the kanban container
                buttonContent: "+ Add New Item", // text or html content of the board button
                widthBoard: '300px',
                // click on current kanban-item
                click: function (el) {
                    // kanban-overlay and sidebar display block on click of kanban-item
                    $(".kanban-overlay").addClass("show");
                    $(".kanban-sidebar").addClass("show");
                    $('#reasondropdown').hide();
                    // Set el to var kanban_curr_el, use this variable when updating title
                    kanban_curr_el = el;

                    // Extract  the kan ban item & id and set it to respective vars
                    kanban_item_title = $(el).contents()[0].data;
                    kanban_curr_item_id = $(el).attr("data-eid");
                    kanban_curr_deal_status = $(el).attr("data-badgecolor");

                    kanban_curr_item_duedate = $(el).attr("data-duedate");

                    if (kanban_curr_item_duedate.indexOf('Consult Booked: ') > -1) {
                        myArr = kanban_curr_item_duedate.split("Consult Booked: ");
                        ///alert( myArr[1]);
                        $(".edit-kanban-item .edit-kanban-item-datetime").val(myArr[1].slice(0, -1));

                    }else{
                        $(".edit-kanban-item .edit-kanban-item-datetime").val('');
                    }

                    if (kanban_curr_item_duedate.indexOf("item-value'>$") > -1) {
                        valArr = kanban_curr_item_duedate.split("item-value'>$")[1].split('</div>Consult Booked:')[0];
                        $(".edit-kanban-item .edit-kanban-item-value").val(valArr);

                    }else{
                        $(".edit-kanban-item .edit-kanban-item-value").val('');
                    }

            
                    // set edit title
                    $(".edit-kanban-item .edit-kanban-item-title").val(kanban_item_title);
                    $(".edit-kanban-item .edit-kanban-item-id").val(kanban_curr_item_id);
                    $(".edit-kanban-item #deal_status").val(kanban_curr_deal_status);
                    
                    $(".edit-kanban-item #status_id").val('');
                    var id = $('#edit-item-id').val();
                    var arrid = id.split("_");
                    document.getElementById("dynamiclink").href = "crm-customers/"+arrid[1]+"/edit";
                    $(".edit-kanban-item #status_id").val(arrid[0]);
                    $(".ql-editor").html('');

                },
                dropEl: function(el, target, source, sibling){
                 var tagetstatus = target.parentElement.getAttribute('data-id');
                 var leadid = el.getAttribute('data-eid');
                 $.ajax({
                        type: 'POST',
                        url: 'crm-customers_move',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {
                            'status_id': tagetstatus,
                            'leadid': leadid,
                        },
                        cache: false,
                        success: function (result) {
                            console.log("It's done.");
                        },
                        error: function() {
                            console.log("Uh oh! There's an error!");
                        }
                    });
                },

                buttonClick: function (el, boardId) {
                    // create a form to add add new element
                    var formItem = document.createElement("form");
                    formItem.setAttribute("class", "itemform");
                    formItem.innerHTML =
                        '<div class="input-field">' +
                        '<textarea class="materialize-textarea add-new-item" rows="2" autofocus required></textarea>' +
                        "</div>" +
                        '<div class="input-field display-flex">' +
                        '<button type="submit" class="btn-floating btn-small mr-2"><i class="material-icons">add</i></button>' +
                        '<button type="button" id="CancelBtn" class="btn-floating btn-small"><i class="material-icons">clear</i></button>' +
                        "</div>";

                    // add new item on submit click
                    KanbanExample.addForm(boardId, formItem);
                    formItem.addEventListener("submit", function (e) {
                        e.preventDefault();
                        var text = e.target[0].value;
                        KanbanExample.addElement(boardId, {
                            title: text
                        });
                        formItem.parentNode.removeChild(formItem);
                    });
                    $(document).on("click", "#CancelBtn", function () {
                        $(this).closest(formItem).remove();
                    })
                },
                addItemButton: false, // add a button to board for easy item creation
                boards: kanban_board_data // data passed from defined variable
            });

        
        // Add html for Custom Data-attribute to Kanban item
        var board_item_id, board_item_el;
        // Kanban board loop

        for (kanban_data in kanban_board_data) {
            // Kanban board items loop
            for (kanban_item in kanban_board_data[kanban_data].item) {

                var board_item_details = kanban_board_data[kanban_data].item[kanban_item]; // set item details
                board_item_id = $(board_item_details).attr("id"); // set 'id' attribute of kanban-item

                (board_item_el = KanbanExample.findElement(board_item_id)), // find element of kanban-item by ID
                    (board_item_users = board_item_dueDate = board_item_comment = board_item_attachment = board_item_image = board_item_badge =
                        " ");

                // check if users are defined or not and loop it for getting value from user's array
                if (typeof $(board_item_el).attr("data-users") !== "undefined") {
                    for (kanban_users in kanban_board_data[kanban_data].item[kanban_item].users) {
                        board_item_users +=
                            '<img class="circle" src=" ' +
                            kanban_board_data[kanban_data].item[kanban_item].users[kanban_users] +
                            '" alt="Avatar" height="24" width="24">';
                    }
                }
                // check if dueDate is defined or not
                if (typeof $(board_item_el).attr("data-dueDate") !== "undefined") {
                    board_item_dueDate =
                        '<div class="kanban-due-date center lighten-5 ' + $(board_item_el).attr("data-border") + '"><span class="' + $(board_item_el).attr("data-border") + '-text center"> ' +
                        $(board_item_el).attr("data-dueDate") +
                        "</span>" +
                        "</div>";
                }
                // check if comment is defined or not
                if (typeof $(board_item_el).attr("data-comment") !== "undefined") {
                    board_item_comment =
                        '<div class="kanban-comment display-flex">' +
                        '<span class="font-size-small">' +
                        $(board_item_el).attr("data-comment") +
                        "</span>" +
                        "</div>";
                }
                // check if attachment is defined or not
                if (typeof $(board_item_el).attr("data-attachment") !== "undefined") {
                    board_item_attachment =
                        '<div class="kanban-attachment display-flex">' +
                        '<span class="font-size-small">' +
                        $(board_item_el).attr("data-attachment") +
                        "</span>" +
                        "</div>";
                }
                // check if Image is defined or not
                if (typeof $(board_item_el).attr("data-image") !== "undefined") {
                    board_item_image =
                        '<div class="kanban-image mb-1">' +
                        '<img class="responsive-img border-radius-4" src=" ' +
                        kanban_board_data[kanban_data].item[kanban_item].image +
                        '" alt="kanban-image">';
                    ("</div>");
                }
                // check if Badge is defined or not
                if (typeof $(board_item_el).attr("data-badgeContent") !== "undefined") {
                    board_item_badge =
                        '<div class="kanban-badge circle lighten-4 ' +
                        kanban_board_data[kanban_data].item[kanban_item].badgeColor +
                        '">' +
                        '<span class="' + kanban_board_data[kanban_data].item[kanban_item].badgeColor + '-text">' +
                        kanban_board_data[kanban_data].item[kanban_item].badgeContent +
                        "</span>";
                    ("</div>");
                }
                // add custom 'kanban-footer'
                if (
                    typeof (
                        $(board_item_el).attr("data-dueDate") ||
                        $(board_item_el).attr("data-comment") ||
                        $(board_item_el).attr("data-users") ||
                        $(board_item_el).attr("data-attachment")
                    ) !== "undefined"
                ) {
                    $(board_item_el).append(
                        '<div class="kanban-footer mt-3">' +
                        board_item_dueDate +
                        '<div class="kanban-footer-left left display-flex pt-1">' +
                        board_item_comment +
                        board_item_attachment +
                        "</div>" +
                        '<div class="kanban-footer-right right">' +
                        '<div class="kanban-users">' +
                        board_item_badge +
                        board_item_users +
                        "</div>" +
                        "</div>" +
                        "</div>"
                    );
                }
                // add Image prepend to 'kanban-Item'
                if (typeof $(board_item_el).attr("data-image") !== "undefined") {
                    $(board_item_el).prepend(board_item_image);
                }
            }
        }
       
        /*kanban_board_data.map(function (obj) {
            $(".kanban-board[data-id='" + obj.id + "']").find(".kanban-board-header").addClass(obj.headerBg)
        })*/

        }

        });

        // Kanban-overlay and sidebar hide
        // --------------------------------------------
        $(
            ".kanban-sidebar .delete-kanban-item, .kanban-sidebar .close-icon, .kanban-sidebar .update-kanban-item, .kanban-overlay"
        ).on("click", function () {
            $(".kanban-overlay").removeClass("show");
            $(".kanban-sidebar").removeClass("show");
        });

        // Delete Kanban Item
        // -------------------
        $(document).on("click", ".delete-kanban-item", function () {
            $delete_item = kanban_curr_item_id;
            // console.log($delete_item);
            addEventListener("click", function () {
                KanbanExample.removeElement($delete_item);
            });
        });

        // Kanban Quill Editor
        // -------------------
        var composeMailEditor = new Quill(".snow-container .compose-editor", {
            modules: {
                toolbar: ".compose-quill-toolbar"
            },
            placeholder: "Write a note... ",
            theme: "snow"
        });

        
        // Perfect Scrollbar - card-content on kanban-sidebar
        if ($(".kanban-sidebar").length > 0) {
            var ps_sidebar = new PerfectScrollbar(".kanban-sidebar", {
                theme: "dark",
                wheelPropagation: false
            });
        }
        // set unique id on all dropdown trigger
        for (var i = 1; i <= $(".kanban-board").length; i++) {
            $(".kanban-board[data-id='" + i + "']").find(".kanban-board-header .dropdown-trigger").attr("data-target", i);
            $(".kanban-board[data-id='" + i + "']").find("ul").attr("id", i);

        }
        // materialise dropdown initialize
        $('.dropdown-trigger').dropdown({
            constrainWidth: false
        }); 
    }
     
  var kanban_board_data = [

  <?php foreach($board_statuses as $key => $item) {

                     
        $user = auth()->user();
        $userid = $user->id;
        $isadmin =  $user->getIsAdminAttribute();

        $bordercolor = "blue";
        $view = app('request')->input('view');
        if($view =="consults"){
            $bordercolor = "blue";
        }

        

        if(!$isadmin && $userrole != 'Manager'){

            if($userrole == 'Lead Center Associate'){
                if($view =="consults"){
                    $leads = App\Models\CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('won_lost', NULL)->orderByRaw("(case when consultation_booked_date <= now() - interval 7 day then 1 else 0 end),  
             (case when consultation_booked_date <= now() - interval 7 day then consultation_booked_date end) desc,  
             consultation_booked_date asc  ")->whereHas('clinic.managers', function ($query ) use($userid) { 
                        return   $query->where('user_id', '=', $userid );
                    })->get();
                }else{
                     
                    if($item->id != 1){
                        $selectedclinic = array();
                        if(session('selectedclinic')){
                            $leads = App\Models\CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('created_at', '>', now()->subDays(90)->endOfDay())->where('won_lost', NULL)->orderBy('id', 'desc')->whereHas('clinic.managers', function ($query ) use($userid) { 
                                    return   $query->where('user_id', '=', $userid );
                                })->get(); 
                            $selectedclinic = session('selectedclinic');
                            $leads = $leads->whereIn('clinic_id',$selectedclinic);
                        }else{
                            $leads = array();
                        }
                    }else{
                        $leads = App\Models\CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('created_at', '>', now()->subDays(90)->endOfDay())->where('won_lost', NULL)->orderBy('id', 'desc')->whereHas('clinic.managers', function ($query ) use($userid) { 
                                return   $query->where('user_id', '=', $userid );
                            })->get(); 
                    }
                }
            }else{
                if($view =="consults"){
                    $leads = App\Models\CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('won_lost', NULL)->orderByRaw("(case when consultation_booked_date <= now() - interval 7 day then 1 else 0 end),  
             (case when consultation_booked_date <= now() - interval 7 day then consultation_booked_date end) desc,  
             consultation_booked_date asc  ")->whereHas('clinic.managers', function ($query ) use($userid) { 
                        return   $query->where('user_id', '=', $userid );
                    })->get();
                }else{
                    $leads = App\Models\CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('created_at', '>', now()->subDays(90)->endOfDay())->where('won_lost', NULL)->orderBy('id', 'desc')->whereHas('clinic.managers', function ($query ) use($userid) { 
                        return   $query->where('user_id', '=', $userid );
                    })->get();  
                }
            }
                
            
            $selectedclinic = array();
            if(session('selectedclinic')){
                $selectedclinic = session('selectedclinic');
                $leads = $leads->whereIn('clinic_id',$selectedclinic);
            }    
         
        }else{
            $selectedclinic = array();
            if(session('selectedclinic')){
                $selectedclinic = session('selectedclinic');
                if($view =="consults"){

                    $leads = App\Models\CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('won_lost', NULL)->orderByRaw("(case when consultation_booked_date <= now() - interval 7 day then 1 else 0 end),  
         (case when consultation_booked_date <= now() - interval 7 day then consultation_booked_date end) desc,  
         consultation_booked_date asc  ")->get();
                }else{
                    $leads = App\Models\CrmCustomer::select('id','first_name','last_name','email','phone','badge','phone_form','three_plus_attempts','consultation_booked_date','convert_to_deal','value','ccapture','deal_status','has_sms','created_at','updated_at','status_id','source_id','clinic_id')->with(['clinic', 'source', 'status', 'assignees'])->whereNull('deleted_at')->where('status_id', $item->id)->where('created_at', '>', now()->subDays(90)->endOfDay())->where('won_lost', NULL)->orderBy('updated_at', 'desc')->get();
                }
               
                $leads = $leads->whereIn('clinic_id',$selectedclinic);
            }else{
                $leads = array();
            }
            
        }

        

  ?>
        {
            id: "<?php echo $item->id ?>",
            title: "<?php echo $item->name; echo " (".count($leads).")"; ?>",
            headerBg: "",
            item: [
                <?php 

                      foreach ($leads as $data)
                      {
                      
                      //dd($data);
                ?> 
                {
                    id: "<?php echo $item->id.'_'.$data->id ?>",
                    title: "<?php 
                              //echo "<a href='crm-customers/".$data->id."/edit' target='_blank'>".$data->first_name. " " .$data->last_name."</a>";
                              echo str_replace('"', '', trim($data->first_name, '"')). " " . str_replace('"', '', trim($data->last_name, '"')); 
                              
                              if(($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager') && $data->ccapture == 1 ){
                                echo "<span class='right'><i class='material-icons tiny icon-demo'>credit_card</i></span>";
                              }  
                              if($data->three_plus_attempts){
                                echo "<span class='right task-cat teal accent-4'>".$data->three_plus_attempts."</span>";
                              }
                              if($data->badge){
                              echo "<span class='badge gradient-45deg-deep-orange-orange gradient-shadow mt-2 mr-2'>".str_replace('"', '', trim($data->badge, '"'))."</span>";
                              }
                              if($data->phone){
                                echo "<div><i class='material-icons tiny icon-demo'>phone_iphone</i>";
                                if(  preg_match( '/^\+\d(\d{3})(\d{3})(\d{4})$/', $data->phone,  $matches ) )
                                {
                                      $result = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
                                      echo $result;
                                } 
                                echo "</div>"; 
                              }
                             
                          ?>",
                    class: "<?php if($data->has_sms == 1)echo "has_sms"; ?>",      
                    border:  "<?php echo $bordercolor; ?>",
                    dueDate: "<?php 
                                    //echo "<i class='material-icons'>info</i> : ".$data->updated_at->format('m/d/Y');
                                    
                                    echo "<div class='item-createddate tooltip'>".$data->created_at->format('m/d/Y')."<span class='tooltiptext'>Created On</span></div>"; 
                                    if($data->value > 0){
                                    echo "<div class='item-value'>$".number_format($data->value)."</div>";
                                    } 
                                    if($view =="consults"){
                                        if($data->convert_to_deal){
                                            echo "Consult Booked: ". (new \Carbon\Carbon($data->consultation_booked_date))->format('m/d/Y g:i A');
                                        }
                                    }
                                    else{
                                        echo "Last Activity: ".$data->updated_at->format('m/d/Y');
                                    }
                              ?> ",
                    comment: "<?php 
                              
                              echo "<div style='float:left;padding-right:5px;'>";  
                              if($data->phone_form == 'Phone Call'){
                                //echo "<i class='material-icons small  icon-demo'>phone</i>";
                                echo "<div class='timeline-badge light-blue'><i class='material-icons small white-text'>call</i></div>";
                              }
                              else {
                                //echo "<i class='material-icons small  icon-demo'>web</i>";
                                echo "<div class='timeline-badge light-blue'><i class='material-icons small white-text'>laptop_mac</i></div>";
                              }
                              echo "</div>";
                              ?>",
                    attachment: "<?php if($data->source)echo " ".$data->source->source_name?>",
                    badgeContent: "<?php echo $data->clinic->clinic_name; echo "<br>".$data->clinic->dr_name;?>",
                    badgeColor: "<?php if($data->deal_status)echo $data->deal_status?>"
  
                    
                },

                <?php } ?>   
                
            ]
        },

<?php }    ?>    
        
    ];

     console.log(kanban_board_data);
     console.log(typeof kanban_board_data);
     
</script>


@endsection