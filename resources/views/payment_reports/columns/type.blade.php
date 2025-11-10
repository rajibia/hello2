<div class="mt-2">
    @if ($row->accounts && $row->accounts->type == 1)
        <span class="badge bg-light-danger">{{ __('messages.account.debit') }}</span>
    @elseif ($row->accounts && $row->accounts->type == 2)
        <span class="badge bg-light-success">{{ __('messages.account.credit') }}</span>
    @else
        <span class="badge bg-light-secondary">{{ __('N/A') }}</span>
    @endif
</div>

