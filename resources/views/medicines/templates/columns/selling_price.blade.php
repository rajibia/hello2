<div class="d-flex align-items-center">
    @if($row->selling_price)
        {{ checkNumberFormat($row->selling_price, strtoupper(getCurrentCurrency())) }}
    @else
        {{__('messages.common.n/a')}}
    @endif
</div>
