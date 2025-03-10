<!-- jQuery library -->
    <script src="{{asset('js/external/jquery.min.js')}}"></script>';



<!-- BEGIN VENDOR JS-->
<script src="{{asset('js/vendors.min.js')}}"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
@yield('vendor-script')
<script src="{{asset('js/materialize-stepper.min.js')}}"></script>
<!-- END PAGE VENDOR JS-->
<!-- BEGIN THEME  JS-->
<script src="{{asset('js/plugins.js')}}"></script>
<script src="{{asset('js/search.js')}}"></script>
<script src="{{asset('js/custom/custom-script.js')}}"></script>


<!-- END THEME  JS-->

    
    
    <script src="{{asset('js/external/popper.min.js')}}"></script>
    <script src="{{asset('js/external/perfect-scrollbar.min.js')}}"></script>

    <script src="{{asset('js/external/pdfmake.min.js')}}"></script>
    <script src="{{asset('js/external/vfs_fonts.js')}}"></script>

    <script src="{{asset('js/external/jszip.min.js')}}"></script>
    
    <script src="{{asset('js/external/ckeditor.js')}}"></script>

    <script src="{{asset('js/external/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/external/dataTables.select.min.js')}}"></script>
    <script src="{{asset('js/external/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/external/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('js/external/buttons.flash.min.js')}}"></script>
    <script src="{{asset('js/external/buttons.html5.min.js')}}"></script>
    <script src="{{asset('js/external/buttons.print.min.js')}}"></script>
    <script src="{{asset('js/external/buttons.colVis.min.js')}}"></script>
    <script src="{{asset('js/external/moment.min.js')}}"></script>
    <script src="{{asset('js/external/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('js/external/select2.full.min.js')}}"></script>
    <script src="{{asset('js/external/dropzone.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('js/daterangepicker.min.js')}}"></script>
<?php if(Request::path() != 'admin'){
?>    <script src="{{ asset('js/main.js') }}"></script>

<?php }?>    

    <script>
        $(function() {
  let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
  let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
  let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
  let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
  let printButtonTrans = '{{ trans('global.datatables.print') }}'
  let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'
  let selectAllButtonTrans = '{{ trans('global.select_all') }}'
  let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'

  let languages = {
    'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
  };

  $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
  $.extend(true, $.fn.dataTable.defaults, {
    language: {
      url: languages['{{ app()->getLocale() }}']
    },
    columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0
    }, {
        orderable: false,
        searchable: false,
        targets: -1
    }],
    select: {
      style:    'multi+shift',
      selector: 'td:first-child'
    },
    order: [],
    scrollX: true,
    pageLength: 100,
    dom: 'lBfrtip<"actions">',
    buttons: [
        {   extend: 'colvis', 
            text: '<i class="material-icons">visibility</i> Show/Hide Columns' 
        },
        {
            extend: 'collection',
            text: '<i class="material-icons">file_download</i> Export',
            buttons: [
                'excel',
                'csv',
                'pdf',
                'print'
            ]
        }
    ]
  });

  $.fn.dataTable.ext.classes.sPageButton = '';
});

    </script>
    <script>
        $(document).ready(function() {
    $('.searchable-field').select2({
        minimumInputLength: 3,
        ajax: {
            url: '{{ route("admin.globalSearch") }}',
            dataType: 'json',
            type: 'GET',
            delay: 200,
            data: function (term) {
                return {
                    search: term
                };
            },
            results: function (data) {
                return {
                    data
                };
            }
        },
        escapeMarkup: function (markup) { return markup; },
        templateResult: formatItem,
        templateSelection: formatItemSelection,
        placeholder : '{{ trans('global.search') }}...',
        language: {
            inputTooShort: function(args) {
                var remainingChars = args.minimum - args.input.length;
                var translation = '{{ trans('global.search_input_too_short') }}';

                return translation.replace(':count', remainingChars);
            },
            errorLoading: function() {
                return '{{ trans('global.results_could_not_be_loaded') }}';
            },
            searching: function() {
                return '{{ trans('global.searching') }}';
            },
            noResults: function() {
                return '{{ trans('global.no_results') }}';
            },
        }

    });
    function formatItem (item) {
        if (item.loading) {
            return '{{ trans('global.searching') }}...';
        }
        var markup = "<div class='searchable-link' href='" + item.url + "'>";
        //markup += "<div class='searchable-title'>" + item.model + "</div>";
        $.each(item.fields, function(key, field) {
            if(item.fields_formated[field] == 'First Name'){
                markup += "<div class='searchable-fields'><i class='material-icons'>account_circle</i>" + item[field] +" "+ item['last_name']+"<i class='material-icons'>phone_iphone</i>"+ item['phone'] + "<i class='material-icons'>email</i>"+ item['email'] + "</div>";
            }
            
        });
        markup += "</div>";

        return markup;
    }

    function formatItemSelection (item) {
        if (!item.model) {
            return '{{ trans('global.search') }}...';
        }
        return item.model;
    }
    $(document).delegate('.searchable-link', 'click', function() {
        var url = $(this).attr('href');
        window.location = url;
    });
});

    </script>
    @yield('scripts')
<!-- BEGIN PAGE LEVEL JS-->
@yield('page-script')