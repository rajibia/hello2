<div class="d-flex align-items-center mt-2">
    @if ($row->bleeding == 0)
        <span class="badge bg-light-primary">No</span>
    @elseif ($row->bleeding == 1)
        <span class="badge bg-light-success">Yes</span>
    @endif    
</div>

