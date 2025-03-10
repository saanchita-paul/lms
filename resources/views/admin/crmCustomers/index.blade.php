@extends('layouts.admin')
@section('content')
@can('crm_customer_create')
    <div class="row">
        
        <!-- <div class="col s2 m6 l6">
            <a class="btn btn-success right" href="{{ route('admin.crm-customers.index') }}">
                <i class="material-icons">picture_in_picture</i> Kanban View
            </a>
        </div> -->
    </div>
    <div class="row">
        <div class="col s6 m6 l6">
            <a class="btn btn-success" href="{{ route('admin.crm-customers.create') }}">
                <i class="material-icons">add_box</i> {{ trans('global.add') }} {{ trans('cruds.crmCustomer.title_singular') }}
            </a>
        </div>
        <div class="clinic-filter col s6">            
        <!-- -->
        <form method="POST" id="" action="{{ route('admin.crm-customers.settings') }}" enctype="multipart/form-data" class="">
           @csrf
             <?php
               
$user = auth()->user()->roles;
$userrole = $user[0]['title'];
                if(count($clinics) > 1){
                //if($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager'){?>

                <select id="boot-multiselect-demo" class="select2 browser-default select2-hidden-accessible" name="filter_clinic[]" data-placeholder="Select a clinic..." multiple="multiple">
                    
                    @foreach($clinics as $id => $clinic)
                        <option value="{{ $clinic->id }}" @foreach($selectedclinic as $selected) @if($selected == $clinic->id) selected @endif @endforeach >{{ $clinic->clinic_name }} - {{ $clinic->dr_name }}</option>
                    @endforeach
                    
                </select>
                <button type="submit" name="submit" class="btn btn-success cyan"><i class='material-icons'>send</i> Apply</button>
            <?php } ?>        
            <a class="btn btn-success" href="{{ route('admin.crm-customers.index') }}">
                <i class="material-icons">picture_in_picture</i> Kanban View
            </a>     
        </form>
        </div>
        <!-- -->


    </div>
@endcan
<div class="card">
  <div class="card-content">
    <div class="card-header col s10">
        {{ trans('cruds.crmCustomer.title_singular') }} {{ trans('global.list') }}
    </div>

    @if (app('request')->input('view') != 'table')
    <div class="card-body">

      
      @include('admin.crmCustomers.kanban', ['crm_statuses' => $crm_statuses ] )
      
    </div> 
    @endif 

    @if (app('request')->input('view') == 'consults')
    <div class="card-body">

      
      @include('admin.crmCustomers.kanban', ['crm_statuses' => $crm_statuses ] )
      
    </div> 
    @endif 

    <?php if($userrole == 'Admin' || $userrole == "Super Admin" ){?>
    <div class="col s2 right">
        @if(request()->has('view_deleted'))

        <a href="{{ route('admin.crm-customers.index', ['view' => 'table']) }}" class="btn btn-info btn-sm">View All</a>

        @else

        <a href="{{ route('admin.crm-customers.index', ['view' => 'table','view_deleted' => 'DeletedRecords']) }}" class="btn btn-primary btn-sm">View Deleted Leads</a>

        @endif
        
    </div>
    <?php } ?>  

    @if (app('request')->input('view') == 'table')
    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-CrmCustomer">
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
                    <?php
                    if($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager'){?>
                    <th>
                        {{ trans('cruds.crmCustomer.fields.ccapture') }}
                    </th>        
                    <?php }
                    ?>
                    <th>
                        &nbsp;
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
                    <?php
                    if($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager'){?>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>        
                    <?php }
                    ?>
                    <td>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
    @endif
  </div>  
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  let searchParams = new URLSearchParams(window.location.search)

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.crm-customers.index') }}?show="+searchParams.get('show')+"&view_deleted="+searchParams.get('view_deleted'),
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
{ data: 'value', render: $.fn.dataTable.render.number( ',', '.', 2, '$' ) },
<?php
    if($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager'){
?>
{ data: 'ccapture', render: function ( data, type, row ) {
  return data == 1 ? 'Yes' : 'No';
} },
<?php } ?>
    { data: 'actions',  name: '{{ trans('global.actions') }}', render: function(data, type, row, meta){
            if(type === 'display'){
                if(searchParams.get('view_deleted') == "DeletedRecords"){
                data = '<a href="crm-customers/restore/one/' + row.id + '" >Restore</a>';
                }
            }

            return data;
         } 
    }


    ],
    orderCellsTop: true,
    order: [[ 0, 'desc' ]],
    pageLength: 100,
    lengthMenu: [
        [ 10, 25, 50, 100, 200, 500, -1 ],
        [ '10', '25', '50', '100', '200', '500', 'Show all' ]
    ],
    
  };
  let table = $('.datatable-CrmCustomer').DataTable(dtOverrideGlobals);

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

</script>
@endsection