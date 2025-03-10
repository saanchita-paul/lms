@extends('layouts.admin')
@section('content')

<div class="card">
  <div class="card-content dashboard">
    <div class="card-header">
        {{ trans('cruds.userreports.title') }}
    </div>
  
<?php
$sourcefilerid = request()->input('source_id', "");
$userfilerid = request()->input('user_id', "");
$icontype = "fa fa-handshake";
?>
    <div class="row d-flex justify-content-center text-center" id="chart">
        <div class="col s12 m4" id="card-stats">
            <form action="" id="filtersForm">
                <div class="input-field">
                    <input type="text" name="from-to" class="" id="date_filter">
                    
                </div>

                <div class="input-field">
                    <select class="form-control" name="user_id" id="user_id">
                        @foreach($userfilter as $id => $user)
                                <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $userfilerid ?? '') == $id ? 'selected' : '' }}>{{ $user }}</option>
                            @endforeach
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
                  <p>Consultations Booked</p>
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
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-UserReports">    
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
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.userreports.index') }}?from-to="+searchParams.get('from-to')+"&source_id="+searchParams.get('source_id')+"&user_id="+searchParams.get('user_id'),
    columns: [
     { data: 'id', name: 'id' },
{ data: 'clinic_clinic_name', name: 'clinic.clinic_name' },
{ data: 'first_name', name: 'first_name' },
{ data: 'last_name', name: 'last_name' },
{ data: 'phone_form', name: 'phone_form' },
{ data: 'source_source_name', name: 'source.source_name' },
{ data: 'status_name', name: 'status.name' },
{ data: 'email', name: 'email' },
{ data: 'phone', name: 'phone' },
{ data: 'won_lost', name: 'won_lost' },
{ data: 'value', render: $.fn.dataTable.render.number( ',', '.', 2, '$' ) }
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
        
        
    }
    
  };
  let table = $('.datatable-UserReports').DataTable(dtOverrideGlobals);



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
