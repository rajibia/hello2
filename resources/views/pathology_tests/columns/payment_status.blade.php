@if($row->balance == 0)
    <span class="badge bg-success">Paid</span>
@elseif($row->amount_paid > 0)
    <span class="badge bg-warning">Partial</span>
@else
    <span class="badge bg-danger">Unpaid</span>
@endif
