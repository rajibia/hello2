<div class="d-flex justify-content-center">
    @role('Admin|Accountant')
        <a href="{{ route('ipd.patient.show', $row->ipdPatient->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="{{ __('messages.common.view') }}">
            <span class="svg-icon svg-icon-3">
                <i class="fas fa-eye"></i>
            </span>
        </a>
    @else
        @role('Patient')
            <a href="{{ route('patient.ipd.show', $row->ipdPatient->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="{{ __('messages.common.view') }}">
                <span class="svg-icon svg-icon-3">
                    <i class="fas fa-eye"></i>
                </span>
            </a>
        @else
            <span class="text-muted">{{ __('messages.common.n/a') }}</span>
        @endrole
    @endrole
</div>