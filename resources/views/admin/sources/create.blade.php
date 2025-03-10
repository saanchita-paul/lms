@extends('layouts.admin')
@section('content')

<div class="card">
<div class="card-content">
    <div class="card-header">
        <h5>{{ trans('global.create') }} {{ trans('cruds.source.title_singular') }}</h5>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sources.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="source_name">{{ trans('cruds.source.fields.source_name') }}</label>
                <input class="form-control {{ $errors->has('source_name') ? 'is-invalid' : '' }}" type="text" name="source_name" id="source_name" value="{{ old('source_name', 'Direct') }}" required>
                @if($errors->has('source_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('source_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.source.fields.source_name_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
</div>


@endsection