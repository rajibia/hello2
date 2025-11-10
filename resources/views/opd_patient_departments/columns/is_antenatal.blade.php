<div class="d-flex align-items-center mt-2">
    @if ($row->is_antenatal == 0)
        <span class="badge bg-light-primary">No</span>
    @elseif ($row->is_antenatal == 1)
        <span class="badge bg-light-success">Yes</span>
    @endif    
</div>

