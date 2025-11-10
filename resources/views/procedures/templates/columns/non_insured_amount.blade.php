<div class="text-end pe-25">
    @if($row->non_insured_amount)
        {{ checkNumberFormat($row->non_insured_amount, strtoupper(getCurrentCurrency())) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
