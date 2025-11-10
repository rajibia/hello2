<div class="d-flex flex-column">
    @if($row->invoices->count() > 0)
        <div class="mb-1">
            <span class="badge bg-info me-1">OPD: {{ $row->invoices->count() }}</span>
        </div>
    @endif

    @if($row->medicine_bills->count() > 0)
        <div class="mb-1">
            <span class="badge bg-success me-1">Medicine: {{ $row->medicine_bills->count() }}</span>
        </div>
    @endif

    @if($row->ipdPatientDepartments->count() > 0)
        <div class="mb-1">
            <span class="badge bg-warning me-1">IPD: {{ $row->ipdPatientDepartments->count() }}</span>
        </div>
    @endif

    @if($row->pathologyTests->count() > 0)
        <div class="mb-1">
            <span class="badge bg-primary me-1">Pathology: {{ $row->pathologyTests->count() }}</span>
        </div>
    @endif

    @if($row->radiologyTests->count() > 0)
        <div class="mb-1">
            <span class="badge bg-secondary me-1">Radiology: {{ $row->radiologyTests->count() }}</span>
        </div>
    @endif

    @if($row->maternity->count() > 0)
        <div class="mb-1">
            <span class="badge bg-danger me-1">Maternity: {{ $row->maternity->count() }}</span>
        </div>
    @endif

    @if($row->invoices->count() == 0 && $row->medicine_bills->count() == 0 && $row->ipdPatientDepartments->count() == 0 && $row->pathologyTests->count() == 0 && $row->radiologyTests->count() == 0 && $row->maternity->count() == 0)
        <span class="text-muted">No bills</span>
    @endif
</div>
