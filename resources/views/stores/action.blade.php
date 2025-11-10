<div class="d-flex justify-content-end w-75 ps-125 text-center">
    <a href="javascript:void(0)" title="{{__('messages.common.edit') }}"
       class="btn px-2 text-primary fs-3 ps-0 editStoreBtn" data-id="{{ $row->id }}">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
       class="btn px-2 text-danger fs-3 ps-0 deleteStoreBtn">
        <i class="fa-solid fa-trash"></i>
    </a>
</div>
