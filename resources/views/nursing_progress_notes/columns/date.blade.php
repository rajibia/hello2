<div class="d-flex align-items-center">
    @if ($row->created_at === null)
        {
        {{ __('messages.common.n/a') }}
    @else
        <div class="badge bg-light-info">
            <div>
                {{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('jS M, Y g:i A') }}
            </div>
        </div>
    @endif
</div>
