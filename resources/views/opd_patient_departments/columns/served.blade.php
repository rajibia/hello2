<div class="d-flex align-items-center mt-2">
    @if ($row->served == 0)
        <span class="badge bg-light-primary">Not Served</span>
    @elseif ($row->served == 1)
        <span class="badge bg-light-success">Served</span>
    @endif    
</div>

