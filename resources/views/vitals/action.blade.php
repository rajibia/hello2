<a href="{{url('vitals'.'/'.$row->id.'/edit')}}" title="<?php echo __('messages.common.edit') ?>"
    class="btn px-1 text-primary fs-3 ps-0  edit-vitals-btn">
    <i class="fa-solid fa-pen-to-square"></i>
</a>
<a href="javascript:void(0)" title="<?php echo __('messages.common.delete') ?>" data-id="{{$row->id}}" wire:key="{{$row->id}}"
    class="delete-vitals-btn btn px-1 text-danger fs-3 ps-0 ">
     <i class="fa-solid fa-trash"></i>
 </a>

