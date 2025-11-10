<div class="text-end pe-25">
    @if($row->tariff)
        {{ checkNumberFormat($row->tariff, strtoupper(getCurrentCurrency())) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
