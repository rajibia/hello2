@if ($row->expiry_date === null)
    {{ __('messages.common.n/a') }}
@else
    @php
        $expiryDate = \Carbon\Carbon::parse($row->expiry_date);
        $now = \Carbon\Carbon::now();

        $isExpired = $expiryDate->isPast();
        $isNearExpiry = !$isExpired && $now->diffInMonths($expiryDate, false) <= 2;
    @endphp

    <div class="badge
        {{ $isExpired ? 'bg-light-danger' : ($isNearExpiry ? 'bg-light-warning' : 'bg-light-info') }}">
        <div>
            {{ $expiryDate->isoFormat('Do MMMM YYYY') }}

            @if ($isExpired)
                <small class="text-danger"> (Expired)</small>
            @elseif ($isNearExpiry)
                <small class="text-warning"> (Near Expiry)</small>
            @endif
        </div>
    </div>
@endif
