 @role('Admin|Doctor|Receptionist')
    @if(!($row->bill_status))
        <a href="{{ route('ipd.patient.edit',$row->id) }}" title="<?php echo __('messages.common.edit') ?>"
        class="btn px-1 text-primary fs-3 ps-0" data-id="{{$row->id}}">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
    @endif
     <!-- View Button -->
    <a href="{{ route('ipd.antenatal.show', $row->id) }}" 
   title="{{ __('View Antenatal Record') }}" 
   class="btn px-1 text-success fs-3 ps-0">
    <i class="fa-solid fa-eye"></i>
</a>

@endrole
<a href="javascript:void(0)" 
   title="<?php echo __('messages.common.delete') ?>" 
   data-id="{{ $row->id }}" 
   wire:key="{{ $row->id }}" 
   class="deleteIpdAntenatal btn px-1 text-danger fs-3 ps-0">
    <i class="fa-solid fa-trash"></i>
</a>

