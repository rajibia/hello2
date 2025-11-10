<div class="d-flex align-items-center mt-2">
    @if (isset($row->medicineBill) && $row->medicineBill->payment_status == 1)
        <span class="badge bg-light-success">{{ __('messages.invoice.paid') }}</span>
    @else
        <span class="badge bg-light-warning">{{ __('messages.invoice.not_paid') }}</span>
    @endif
</div>
