<div class="badge bg-light-info">
    @if(!empty($row->return_date))
    {{ \Carbon\Carbon::parse($row->return_date)->translatedFormat('jS M,Y')}}
    @else
    {{__('messages.common.n/a')}}
    @endif
</div>
