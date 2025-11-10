<div class="d-flex justify-content-center align-items-center mt-2">
    @if ($row->status == 2)
        <span class="badge bg-light-success">Done</span>
    @elseif ($row->status == 1)
        <span class="badge bg-light-warning">In Progress</span>
    @else
        <span class="badge bg-light-info">Pending</span>
    @endif
</div>
