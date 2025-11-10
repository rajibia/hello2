@if ($row->created_at && strtotime($row->created_at) !== false)
    <div class="badge bg-light-primary">
        {{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('jS M, Y g:i A') }}
    </div>
@else
    {{ __('messages.common.n/a') }}
@endif
