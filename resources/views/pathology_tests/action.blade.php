<?php $currentRoute = request()->route()->getName();  ?>
{{-- <a href="javascript:void(0)" title="<?php echo __('messages.new_change.view_pathology_test'); ?>" class="showPathologyTestBillBtn btn px-0 text-success fs-3"
    data-id="{{ $row->id }}" >
    <i class="fa fa-eye"></i>
</a> --}}

@if($row->balance > 0)
    @if($row->patient && $row->patient->company_id)
        <a href="{{ route('companies.view', $row->patient->company_id) }}" title="View Company Bills" class="btn px-1 text-warning fs-3">
            <i class="fa fa-credit-card"></i>
        </a>
    @else
        <a href="{{ route('patient.bills.show', $row->patient_id) }}" title="View Patient Bills" class="btn px-1 text-warning fs-3">
            <i class="fa fa-credit-card"></i>
        </a>
    @endif
@endif

@if($row->status == 0 && Auth::user()->hasRole('Lab Technician'))
    <form action="{{ route('pathology.test.accept', $row->id) }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" title="Accept Test Request" class="btn px-1 text-info fs-3"
                onclick="return confirm('Are you sure you want to accept this test request?')">
            <i class="fa fa-check"></i>
        </button>
    </form>
@endif

<a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
   class="deletePathologyTestBtn btn px-1 text-danger fs-3 pe-0" wire:key="{{$row->id}}">
    <i class="fa-solid fa-trash"></i>
</a>
