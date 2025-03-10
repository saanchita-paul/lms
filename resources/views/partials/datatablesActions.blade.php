<div class="display-flex">
@can($viewGate)
    <a class="btn-floating" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
        <i class="material-icons">remove_red_eye</i>
    </a>
@endcan
@can($editGate)
    <a style="float:center;" class="btn-floating purple" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
       <i class="material-icons">edit</i>
    </a>
@endcan
@can($deleteGate)
    <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="">

        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn-floating grey"><i class='material-icons'>delete</i></button>
    </form>
@endcan
</div>