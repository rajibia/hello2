<div class="d-flex align-items-center">
    <span class="badge badge-light-success me-2">IPD</span>
    @role('Admin|Accountant')
        <a href="{{ route('ipd.patient.show', $row->ipdPatient->id) }}" class="text-decoration-none">
            {{ $row->ipdPatient->ipd_number }}
        </a>
    @else
        @role('Patient')
            <a href="{{ route('patient.ipd.show', $row->ipdPatient->id) }}" class="text-decoration-none">
                {{ $row->ipdPatient->ipd_number }}
            </a>
        @else
            {{ $row->ipdPatient->ipd_number }}
        @endrole
    @endrole
</div>