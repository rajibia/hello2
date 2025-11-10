<div class="d-flex align-items-center">
    @if($row->buying_price)
        {{ checkNumberFormat($row->buying_price, strtoupper(getCurrentCurrency())) }}
    @else
        {{__('messages.common.n/a')}}
    @endif
</div>
