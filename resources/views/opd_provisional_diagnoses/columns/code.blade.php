<div class="ipd-code">
    @if ($row->code != '')
        <span>{{$row->code}}</span>
    @else
        {{__('messages.common.n/a')}}
    @endif
</div>
