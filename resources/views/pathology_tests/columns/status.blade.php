@if ($row->status == 0)
    <span class="badge bg-warning">Pending</span>
@elseif ($row->status == 1)
    <span class="badge bg-success">Completed</span>
@else
    <span class="badge bg-secondary">Unknown</span>
@endif
