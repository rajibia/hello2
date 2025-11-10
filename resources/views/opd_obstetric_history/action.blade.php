@role('Admin|Doctor|Receptionist')
    <a href="" title="<?php echo __('messages.common.edit') ?>"
    class="btn px-1 text-primary fs-3 ps-0" data-id="{{$row->id}}">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
@endrole
<a href="javascript:void(0)" 
   title="<?php echo __('messages.common.delete') ?>" 
   data-id="{{ $row->id }}" 
   wire:key="{{ $row->id }}" 
   class="deleteIpdObstetric btn px-1 text-danger fs-3 ps-0">
    <i class="fa-solid fa-trash"></i>
</a>

