@extends('layouts.admin')
@section('content')
@can('clinic_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col s2 m6 l6">
            <a class="btn btn-success" href="{{ route('admin.clinics.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.clinic.title_singular') }}
            </a>
            <!-- <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Clinic', 'route' => 'admin.clinics.parseCsvImport'])
            -->
        </div>
    </div>
@endcan
<div class="card">
<div class="card-content">
    <div class="card-header">
        {{ trans('cruds.clinic.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Clinic">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.clinic.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.clinic.fields.clinic_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.clinic.fields.clinic_legal_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.clinic.fields.dr_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.clinic.fields.hotline_phone_number') }}
                    </th>
                    <th>
                        {{ trans('cruds.clinic.fields.lead_center') }}
                    </th>
                    <th>
                        {{ trans('cruds.clinic.fields.status') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
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
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search browser-default" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Clinic::LEAD_CENTER_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search browser-default" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Clinic::STATUS_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>

</div>


@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.clinics.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'clinic_name', name: 'clinic_name' },
{ data: 'clinic_legal_name', name: 'clinic_legal_name' },
{ data: 'dr_name', name: 'dr_name' },
{ data: 'hotline_phone_number', name: 'hotline_phone_number' },
{ data: 'lead_center', name: 'lead_center' },
{ data: 'status', name: 'status' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Clinic').DataTable(dtOverrideGlobals);
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