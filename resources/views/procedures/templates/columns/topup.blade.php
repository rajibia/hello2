<div class="text-end pe-25">
    @if($row->topup)
        {{ checkNumberFormat($row->topup, strtoupper(getCurrentCurrency())) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
