<div class="d-flex justify-content-end pe-22">
    @if(!empty($row->invoiceItems))
        {{ implode(', ', array_pluck($row->invoiceItems, 'charge.chargeCategory.name')) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
