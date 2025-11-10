@if($row->patient_id)
<a href="{{ route('patient.bills.show', $row->patient_id) }}" class="btn btn-success btn-sm">
    {{ __('View') }}
</a>
@else
<span class="text-muted">N/A</span>
@endif
