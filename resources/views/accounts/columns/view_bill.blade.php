@php
    $patient = \App\Models\Patient::find($row->patient_id);
@endphp
@if(!$patient || !$patient->company_id)
<a href="{{ route('patient.bills.show', $row->patient_id) }}" class="btn btn-success btn-sm">
    {{ __('View') }}
</a>
@else

<a href="{{ route('company-billing.index', ['patient_id' => $row->patient_id, 'company_id' => $patient->company_id]) }}" class="btn btn-success btn-sm">
    {{ __('View (Company)') }}
</a>
@endif
