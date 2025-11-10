<div class="d-flex align-items-center mt-2">
    @if ($row->haemorrhoids == 0)
        <span class="badge bg-light-primary">No</span>
    @elseif ($row->haemorrhoids == 1)
        <span class="badge bg-light-success">Yes</span>
    @endif    
</div>

