<div class="badge bg-light-info">
    <div class="mb-1">{{ \Carbon\Carbon::parse($row->appointment_date)->format('jS M, Y') }}</div>
    <div>{{ \Carbon\Carbon::parse($row->appointment_date)->format('g:i A') }}</div>
</div>
