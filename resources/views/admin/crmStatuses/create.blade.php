@extends('layouts.admin')
@section('content')

<div class="card">
<div class="card-content">
    <div class="card-header">
        </h5>{{ trans('global.create') }} {{ trans('cruds.crmStatus.title_singular') }}</div>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.crm-statuses.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.crmStatus.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.crmStatus.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="board">{{ trans('cruds.crmStatus.fields.board') }}</label>
                <input class="form-control {{ $errors->has('board') ? 'is-invalid' : '' }}" type="text" name="board" id="board" value="{{ old('board', '') }}" required>
                @if($errors->has('board'))
                    <div class="invalid-feedback">
                        {{ $errors->first('board') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.crmStatus.fields.board_helper') }}</span>
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