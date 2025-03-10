@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <h5>{{ trans('global.create') }} {{ trans('cruds.crmChat.title_singular') }}</h5>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.crm-chats.store") }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
            <input type="hidden" name="is_sms" value="1">
            <div class="form-group">
                <label class="required" for="lead_id">{{ trans('cruds.crmChat.fields.name') }}</label>
                <select class="browser-default form-control select2 {{ $errors->has('customer') ? 'is-invalid' : '' }}" name="lead_id" id="lead_id" required>
                    @foreach($customers as $id => $customer)
                        <option value="{{ $id }}" {{ old('lead_id') == $id ? 'selected' : '' }}>{{ $customer }}</option>
                    @endforeach
                </select>
                @if($errors->has('customer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('customer') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.crmChat.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="chat">{{ trans('cruds.crmChat.fields.chat') }}</label>
                <textarea class="form-control {{ $errors->has('chat') ? 'is-invalid' : '' }}" name="chat" id="chat" required>{{ old('chat') }}</textarea>
                @if($errors->has('chat'))
                    <div class="invalid-feedback">
                        {{ $errors->first('chat') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.crmChat.fields.chat_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection