@extends('layouts.admin')
@section('content')

<div class="card">

<div class="card-content">
    
    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.users.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <td>
                            {{ $user->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <td>
                            {{ $user->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <td>
                            {{ $user->email }}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.roles') }}
                        </th>
                        <td>
                            @foreach($user->roles as $key => $roles)
                                <span class="label label-info">{{ $roles->title }}</span>
                            @endforeach
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.last_login_date') }}
                        </th>
                        <td>
                            {{ $user->last_login_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.profile_pic') }}
                        </th>
                        <td>
                            @if($user->profile_pic)
                                <a href="{{ $user->profile_pic->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $user->profile_pic->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>

</div>

<div class="card">
<div class="card-content">
    
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="manager_clinics">
            @includeIf('admin.users.relationships.managerClinics', ['clinics' => $user->managerClinics])
        </div>
    </div>
</div>
</div>
@endsection