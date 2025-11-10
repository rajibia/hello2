<div>
    @if(Auth::user()->hasRole('Admin|Doctor|Case Manager|Receptionist'))
    <a href="{{ route('patient.admissions.excel') }}"
        class="btn btn-primary me-4">
        <i class="fas fa-file-excel"></i>
    </a>
    @endif
	@modulePermission('patient-admissions', 'add')
    <a href="{{ route('patient-admissions.create') }}"
        class="btn btn-primary">{{ __('messages.patient_admission.new_patient_admission') }}</a>
    @endmodulePermission
</div>
