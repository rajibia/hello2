{{-- <a href="{{url('systemic_examinations'.'/'.$row->id)}}" title="<?php echo __('messages.common.view') ?>"
    class="btn px-1 text-primary fs-3 ps-0  view-complaints-btn">
    <i class="fa-solid fa-eye"></i>
</a>
<a href="{{url('systemic_examinations'.'/'.$row->id.'/edit')}}" title="<?php echo __('messages.common.edit') ?>"
    class="btn px-1 text-primary fs-3 ps-0  edit-systemic_examinations-btn">
    <i class="fa-solid fa-pen-to-square"></i>
</a>
<a href="javascript:void(0)" title="<?php echo __('messages.common.delete') ?>" data-id="{{$row->id}}" wire:key="{{$row->id}}"
    class="delete-systemic_examinations-btn btn px-1 text-danger fs-3 ps-0 ">
     <i class="fa-solid fa-trash"></i>
 </a> --}}


 <a title="<?php echo __('messages.common.view'); ?>" data-id="{{ $row->id }}" class="btn px-1 text-primary fs-3 ps-0 viewSystemicExaminationBtn">
    <i class="fa-solid fa-eye"></i>
</a>
<a title="{{__('messages.common.edit')}}" data-id="{{ $row->id }}"
   class="btn px-1 text-primary fs-3 ps-0 editSystemicExaminationBtn">
    <i class="fa-solid fa-pen-to-square"></i>
</a>
<a title="{{__('messages.common.delete')}}" href="javascript:void(0)" data-id="{{ $row->id }}" wire:key="{{$row->id}}"
   class="btn px-1 text-danger fs-3 ps-0 deleteSystemicExaminationBtn">
    <i class="fa-solid fa-trash"></i>
</a>

