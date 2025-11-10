<div class="d-flex align-items-center mt-2">
    @if (isset($row->medicineBill) && $row->medicineBill->issue_status == 1)
        <span class="badge bg-light-success">{{ __('messages.issued') }}</span>
    @else
        <span class="badge bg-light-warning">{{ __('messages.not_issued') }}</span>
    @endif
</div>