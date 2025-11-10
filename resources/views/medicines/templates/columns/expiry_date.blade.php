@if ($row->expiry_date === null)
    {{ __('messages.common.n/a') }}
@else
    <div class="badge bg-light-info">
        <div>{{ \Carbon\Carbon::parse($row->expiry_date)->isoFormat('Do MMMM YYYY') }}

        </div>
    </div>
@endif