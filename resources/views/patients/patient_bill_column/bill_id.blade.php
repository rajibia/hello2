{{-- Display bill number based on bill type --}}
@if($row->bill_type == 'Medicine Bill')
    @if(Auth::user()->hasRole('Admin'))
        <a href="{{ url('bills',$row->id) }}">
            <span class="badge bg-light-info">{{ $row->bill_number }}</span></a>
    @elseif(Auth::user()->hasRole('Patient'))
        <a href="{{ url('employee/bills',$row->id) }}">
            <span class="badge bg-light-info">{{ $row->bill_number }}</span></a>
    @elseif(Auth::user()->hasRole('Accountant'))
        <a href="{{ url('bills',$row->id) }}">
            <span class="badge bg-light-info">{{ $row->bill_number }}</span></a>
    @else
        <span class="badge bg-light-info">{{ $row->bill_number }}</span>
    @endif
@elseif($row->bill_type == 'Laboratory Bill')
    @if(Auth::user()->hasRole('Admin|Accountant'))
        <span class="badge bg-light-success">{{ $row->bill_number }}</span>
    @else
        <span class="badge bg-light-success">{{ $row->bill_number }}</span>
    @endif
@elseif($row->bill_type == 'Radiology Bill')
    @if(Auth::user()->hasRole('Admin|Accountant'))
        <span class="badge bg-light-warning">{{ $row->bill_number }}</span>
    @else
        <span class="badge bg-light-warning">{{ $row->bill_number }}</span>
    @endif
@elseif($row->bill_type == 'Maternity Bill')
    @if(Auth::user()->hasRole('Admin|Accountant'))
        <span class="badge bg-light-primary">{{ $row->bill_number }}</span>
    @else
        <span class="badge bg-light-primary">{{ $row->bill_number }}</span>
    @endif
@else
    <span class="badge bg-light-secondary">{{ $row->bill_number }}</span>
@endif
