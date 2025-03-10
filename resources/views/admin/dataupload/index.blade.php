@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-md-6">
        <div class="card">
        <div class="card-content">
            <div class="card-header">
                <h5> {{ trans('cruds.dataupload.title') }}</h5>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route("admin.dataupload.store") }}">
                    @csrf
                    
                    <div class="input-field">
                        <label for="uploadfile"></label>
                        <select class="form-control" name="clinic_id" id="clinic_id">
                            @foreach($clinics as $key => $item)
                                <option value="{{ $item->id }}" @if(Request::get('clinic_id')==$item->id) selected @endif >{{ $item->clinic_name }}</option>
                            @endforeach
                        </select>
                    </div>    
                    <div class="input-field">
                        <label for="uploadfile"></label>
                        <div class="form-control needsclick dropzone {{ $errors->has('uploadfile') ? 'is-invalid' : '' }}" id="uploadfile-dropzone">
                        </div>
                        @if($errors->has('uploadfile'))
                            <div class="invalid-feedback">
                                {{ $errors->first('uploadfile') }}
                            </div>
                        @endif

                        @if($dataupload->uploadfile)
                                <a href="{{ $dataupload->uploadfile->getUrl() }}" target="_blank" style="display: inline-block">
                                    View
                                </a>
                        @endif
                        
                    </div>
                    <div class="input-field">
                        <button class="btn btn-danger" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>  
<?php
//print_r(json_decode($patients));
$leads = json_decode($patients);
foreach($leads as $value){
    echo "ID:".$value->id;
    echo "   First Name: ".$value->first_name;
    echo "   Last Name: ".$value->last_name;
    echo "   Email: ".$value->email;
    echo "   Phone: ".$value->phone;
    echo "   Value: ".$value->value;
    echo "   Lifetime Value: ".$value->lifetimevalue;
    echo "<br><hr>";
}
?>



@endsection

{{-- page script --}}
@section('page-script')


@endsection
@section('scripts')
@parent
<script>
    Dropzone.options.uploadfileDropzone = {
    url: '{{ route('admin.dataupload.storeMedia') }}',
    maxFilesize: 10, // MB
    acceptedFiles: '.csv',
    maxFiles: 1,
    addRemoveLinks: false,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    addRemoveLinks: true,
    success: function (file, response) {
      $('form').find('input[name="uploadfile"]').remove()
      $('form').append('<input type="hidden" name="uploadfile" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="uploadfile"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
    
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}


$(function () {
            $('#clinic_id').on('change', function () {
                $.each($(this).find('option:selected'), function (index, item) {
                    var selected = $(item).val();
                    window.location.href = window.location.pathname+"?"+$.param({'clinic_id':selected})

                });
            });
});

</script>
@endsection
