{{-- View Bill Button --}}
@if($row->company_id)
    {{-- Company Patient - Redirect to company billing --}}
    <a href="/company-billing?patient_id={{ $row->id }}&company_id={{ $row->company_id }}"
       title="View Company Bills"
       class="btn btn-success btn-sm">
        <i class="fa-solid fa-eye me-1"></i>View (Company)
    </a>
@else
    {{-- Individual Patient - Redirect to patient bills --}}
    <a href="{{ route('patient.bills.show',$row->id) }}"
       title="View All Bills"
       class="btn btn-success btn-sm">
        <i class="fa-solid fa-eye me-1"></i>View
    </a>
@endif
