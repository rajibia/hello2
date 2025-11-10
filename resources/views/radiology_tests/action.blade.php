<?php $currentRoute = request()->route()->getName();  ?>
@if((!str_contains($currentRoute, "ipd") && !str_contains(url()->current(), 'livewire/message/radiology-tests') &&
(!str_contains($currentRoute, "opd"))))
    <a href="{{ route('radiology.test.edit',$row->id)}}" title="{{__('messages.common.edit') }}"
    class="btn px-1 text-primary fs-3 ps-0">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
@else
    <a href="javascript:void(0)" title="<?php echo __('messages.new_change.view_radiology_test'); ?>" class="editRadiologyTestBillBtn btn px-0 text-success fs-3"
        data-id="{{ $row->id }}" >
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
@endif
<a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
   class="deleteRadiologyTestBtn btn px-1 text-danger fs-3 pe-0" wire:key="{{$row->id}}">
    <i class="fa-solid fa-trash"></i>
</a>
