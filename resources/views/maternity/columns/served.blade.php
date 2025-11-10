@if($row->served)
    <span class="badge bg-light-success">Served</span>
@else
    <span class="badge bg-light-warning">Pending</span>
@endif
