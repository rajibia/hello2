<a title="<?php echo __('messages.common.view'); ?>" data-id="{{ $row->id }}" class="btn px-1 text-primary fs-3 ps-0 viewNursingNoteBtn">
    <i class="fa-solid fa-eye"></i>
</a>
<a title="{{__('messages.common.edit')}}" data-id="{{ $row->id }}"
   class="btn px-1 text-primary fs-3 ps-0 editNursingNoteBtn">
    <i class="fa-solid fa-pen-to-square"></i>
</a>
<a title="{{__('messages.common.delete')}}" href="javascript:void(0)" data-id="{{ $row->id }}" wire:key="{{$row->id}}"
   class="btn px-1 text-danger fs-3 ps-0 deleteNursingNoteBtn">
    <i class="fa-solid fa-trash"></i>
</a>
