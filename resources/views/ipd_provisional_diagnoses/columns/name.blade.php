<div class="ipd-name">
    @if ($row->name != '')
        <span>{{$row->name}}</span>
    @else
        {{__('messages.common.n/a')}}
    @endif
</div>
