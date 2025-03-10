@extends('layouts.admin')
@section('content')
@can('reports_management_access')
    
    <div class="row hide">
        
        <div class="clinic-filter col s6">            
        <!-- -->
        <form method="POST" id="" action="{{ route('admin.crm-customers.settings') }}" enctype="multipart/form-data" class="">
           @csrf
             <?php
               
$user = auth()->user()->roles;
$userrole = $user[0]['title'];

                if($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager'){?>

                <select id="boot-multiselect-demo" class="select2 browser-default select2-hidden-accessible" name="filter_clinic[]" data-placeholder="Select a clinic..." multiple="multiple">
                    
                    @foreach($clinics as $id => $clinic)
                        <option value="{{ $clinic->id }}" @foreach($selectedclinic as $selected) @if($selected == $clinic->id) selected @endif @endforeach >{{ $clinic->clinic_name }} - {{ $clinic->dr_name }}</option>
                    @endforeach
                    
                </select>
                <button type="submit" name="submit" class="btn btn-success cyan"><i class='material-icons'>send</i> Apply</button>
            <?php } ?>        
                
        </form>
        </div>
        <!-- -->


    </div>
@endcan
<div class="card">
  <div class="card-content dashboard">
    <div class="card-header">
        {{ trans('cruds.reports.title') }}
    </div>
  


    <div class="row">
        <div class="col s12 m6">

        </div>
    </div>
<?php

$sourcefilerid = request()->input('source_id', "");

$icontype = "fa fa-user-plus";

if($report_type == 2){
    $icontype = "fa fa-handshake";   
}elseif($report_type == 3){
    $icontype = "fa fa-calendar-check";    
}elseif($report_type == 4){
    $icontype = "fa fa-calendar-times";    
}elseif($report_type == 5){
    $icontype = "fas fa-user-md";    
}elseif($report_type == 6){
    $icontype = "fas fa-user-lock";    
}elseif($report_type == 7){
    $icontype = "fas fa-user-slash";    
}

?>
    <div class="row d-flex justify-content-center text-center" id="chart">
        <div class="col s12 m4" id="card-stats">
            <form action="" id="filtersForm">
                <div class="input-field">
                    <input type="text" name="from-to" class="" id="date_filter">
                    
                </div>
                <div class="input-field">
                    <select class="form-control" name="report_type" id="report_type">
                        
                                <option value="1" {{ (old('report_type') ? old('report_type') : $report_type ?? '') == 1 ? 'selected' : '' }}>New Leads</option>
                                <option value="2" {{ (old('report_type') ? old('report_type') : $report_type ?? '') == 2 ? 'selected' : '' }}>Consultations Booked</option>
                                <option value="3" {{ (old('report_type') ? old('report_type') : $report_type ?? '') == 3 ? 'selected' : '' }}>Consultations Showed</option>
                                <option value="4" {{ (old('report_type') ? old('report_type') : $report_type ?? '') == 4 ? 'selected' : '' }}>Consultations Not Showed</option>
                                <option value="5" {{ (old('report_type') ? old('report_type') : $report_type ?? '') == 5 ? 'selected' : '' }}>Pending Acceptance</option>
                                <option value="6" {{ (old('report_type') ? old('report_type') : $report_type ?? '') == 6 ? 'selected' : '' }}>Treatment Sold</option>
                                <option value="7" {{ (old('report_type') ? old('report_type') : $report_type ?? '') == 7 ? 'selected' : '' }}>Leads Nurturing</option>
                                                
                    </select>
                </div>

                <div class="input-field" id="wondatefilter" style="display: none;">
                    <select class="form-control" name="won_date_type" id="won_date_type">
                        
                                <option value="1" {{ (old('won_date_type') ? old('won_date_type') : $won_date_type ?? '') == 1 ? 'selected' : '' }}>By Sold Date</option>
                                <option value="2" {{ (old('won_date_type') ? old('won_date_type') : $won_date_type ?? '') == 2 ? 'selected' : '' }}>By Lead Came In</option>
                                
                                                
                    </select>
                </div>


                <div class="input-field">
                    <select class="form-control" name="source_id" id="source_id">
                        @foreach($sourcesfilter as $id => $source)
                                <option value="{{ $id }}" {{ (old('source_id') ? old('source_id') : $sourcefilerid ?? '') == $id ? 'selected' : '' }}>{{ $source }}</option>
                            @endforeach
                    </select>
                </div>  
                <div class="input-field">
                    <input type="submit" class="btn btn-primary" value="Filter">    
                </div>  
            </form>
            <div class="row dbox1 white-text padding-4 mt-5 hide">
                <div class="col s7 m7 card-stats-title">
                  <i class="fa fa-user-plus mt-5 mb-5"></i>
                  <p>New Leads</p>
                </div>
                <div class="col s5 m5 right-align">
                  <h5 class="mb-0 white-text" ></h5>
                </div>
            </div>
            <div class="">
                <div class="card">
                   <div class="card-content cyan white-text">
                      <p class="card-stats-title"><i class="<?php echo $icontype;?> mt-5 mb-5"></i> {{ $title }}</p>

                      <h4 class="card-stats-number white-text" id="totalcount"></h4>
                      
                   </div>
                   
                </div>
            </div>    
          
        </div>
        <div class="{{ $chart->options['column_class'] }}">
            <!-- <h6>{!! $chart->options['chart_title'] !!}</h6> -->
            {!! $chart->renderHtml() !!}
        </div>
    </div>
    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Reports">    
            <thead>
                <tr>
                    
                    <th>
                        {{ trans('cruds.crmCustomer.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.clinic') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.first_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.last_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.phone_form') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.source') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.email') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.phone') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.won_lost') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.value') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.lifetimevalue') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.created_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.convert_deal_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.consultation_booked_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.won_lost_date') }}
                    </th>

                    <?php
                    if($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager'){?>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.ccapture') }}
                    </th>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.reason') }}
                    </th>        
                    <?php }
                    ?>
                    <?php
                    if($userrole == 'Admin' || $userrole == "Super Admin"){
                    ?>
                    
                    <th>
                        utm_source
                    </th>
                    <th>
                        utm_medium
                    </th>
                    <th>
                        utm_term
                    </th>
                    <th>
                        utm_content
                    </th>
                    <th>
                        utm_campaign
                    </th>
                    <?php } ?>
                </tr>
                <tr>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search browser-default">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($clinics as $key => $item)
                                <option value="{{ $item->clinic_name }}">{{ $item->clinic_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search browser-default" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\CrmCustomer::PHONE_FORM_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search browser-default">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($sources as $key => $item)
                                <option value="{{ $item->source_name }}">{{ $item->source_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search browser-default">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($crm_statuses as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search browser-default">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\CrmCustomer::WON_LOST_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <?php
                    if($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager'){?>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>        
                    <?php }
                    ?>
                    <?php
                    if($userrole == 'Admin' || $userrole == "Super Admin"){
                    ?>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <?php } ?>
                    
                </tr>
            </thead>
        </table>
    </div>
   
  </div>  
</div>



@endsection

{{-- page script --}}
@section('page-script')


@endsection
@section('scripts')
@parent
<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.25/api/sum().js"></script>    
<script>
$(document).ready(function () {
    $(function() {    

        if($('#report_type').val() == 6){
            $('#wondatefilter').show();
        }

        $('#report_type').on('change', function () {

            $.each($(this).find('option:selected'), function (index, item) {
                var selected = $(item).val();
                
                if (selected == '6') {
                    $('#wondatefilter').show();
                    return true;
                }else{
                    $('#wondatefilter').hide();
                    return true;
                }
            });
        });

    });
});
$(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.reports.index') }}?from-to="+searchParams.get('from-to')+"&source_id="+searchParams.get('source_id')+"&report_type="+searchParams.get('report_type')+"&won_date_type="+searchParams.get('won_date_type'),
    columns: [
     { data: 'id', name: 'id' },
{ data: 'clinic_clinic_name', name: 'clinic.clinic_name' },
{ data: 'first_name', name: 'first_name', render: function(data, type, row, meta){
            if(type === 'display'){
                data = '<a href="crm-customers/' + row.id + '/edit" target="_blank">' + data + '</a>';
            }

            return data;
         } },
{ data: 'last_name', name: 'last_name' },
{ data: 'phone_form', name: 'phone_form' },
{ data: 'source_source_name', name: 'source.source_name' },
{ data: 'status_name', name: 'status.name' },
{ data: 'email', name: 'email' },
{ data: 'phone', name: 'phone' },
{ data: 'won_lost', name: 'won_lost' },
{ data: 'value', render: $.fn.dataTable.render.number( ',', '.', 2, '$' ) },
{ data: 'lifetimevalue', render: $.fn.dataTable.render.number( ',', '.', 2, '$' ) },
{ data: 'created_at', render: function (data, type, row) {//data
        return moment(row.created_at).format('MM/DD/YYYY');
    } },
{ data: 'convert_deal_date', render: function (data, type, row) {//data
        if(row.convert_deal_date != null){
        return moment(row.convert_deal_date).format('MM/DD/YYYY');
        }else{ return "NA";}
    } },
{ data: 'consultation_booked_date', render: function (data, type, row) {//data
        if(row.consultation_booked_date != null){
        return moment(row.consultation_booked_date).format('MM/DD/YYYY');
        }else{ return "NA";}
    } },
{ data: 'won_lost_date', render: function (data, type, row) {//data
        if(row.won_lost_date != null){
        return moment(row.won_lost_date).format('MM/DD/YYYY');
        }else{ return "NA";}
    } },            
<?php
    if($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager'){
?>
{ data: 'ccapture', render: function ( data, type, row ) {
  return data == 1 ? 'Yes' : 'No';
} },
{ data: 'reason', name: 'reason' },
<?php } ?>
<?php
if($userrole == 'Admin' || $userrole == "Super Admin"){
?>
{ data: 'utm_source', name: 'utm_source' },
{ data: 'utm_medium', name: 'utm_medium' },
{ data: 'utm_term', name: 'utm_term' },
{ data: 'utm_content', name: 'utm_content' },
{ data: 'utm_campaign', name: 'utm_campaign' }
<?php } ?>
    ],
    orderCellsTop: true,
    order: [[ 0, 'desc' ]],
    pageLength: 100,
    lengthMenu: [
        [ 10, 25, 50, 100, 200, 500, -1 ],
        [ '10', '25', '50', '100', '200', '500', 'Show all' ]
    ],
    initComplete: function(settings, json){ 
        var info = this.api().page.info();
        console.log('Total records', info.recordsTotal);
        $('#totalcount').html(info.recordsTotal);
        if(searchParams.get('report_type')== 5 || searchParams.get('report_type') == 6 ){
        var api = this.api();
        $( api.table().footer() ).html(
            $('#totalcount').html('<i class="material-icons">attach_money</i>'+api.column(10).data().sum().toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'))
        );
        }
        
    }
    
  };
  let table = $('.datatable-Reports').DataTable(dtOverrideGlobals);
  <?php
    if($userrole == 'Admin' || $userrole == "Super Admin"){
    ?>
  let allcolumn = table.columns([18,19,20,21,22]); // here is the index of the column, starts with 0
  //allcolumn.visible(false);
  allcolumn.visible(false, false);  
//table.columns.adjust().draw( false ); // adjust column sizing and redraw
  <?php } ?>

  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })


});

  let searchParams = new URLSearchParams(window.location.search)
  let dateInterval = searchParams.get('from-to');
  let start = moment().subtract(30, 'days');
  let end = moment();
  let report_type = searchParams.get('report_type');

  if((report_type == 7 || report_type == 5) && dateInterval == null){
    start = moment().subtract(30, 'year').startOf('month');
    end = moment().endOf('month');
  }
  console.log(dateInterval);
  if (dateInterval) {
      dateInterval = dateInterval.split(' - ');
      start = dateInterval[0];
      end = dateInterval[1];
  }

  $('#date_filter').daterangepicker({
      "showDropdowns": true,
      "showWeekNumbers": false,
      "alwaysShowCalendars": true,
      startDate: start,
      endDate: end,
      locale: {
          format: 'MM/DD/YYYY',
          firstDay: 1,
      },
      ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 14 Days': [moment().subtract(14, 'days'), moment()],
          'Last 30 Days': [moment().subtract(30, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
          'This Year': [moment().startOf('year'), moment().endOf('year')],
          'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
          'All time': [moment().subtract(30, 'year').startOf('month'), moment().endOf('month')],
      }
  });
</script>
{!! $chart->renderChartJsLibrary() !!}
{!! $chart->renderJs() !!}
@endsection
