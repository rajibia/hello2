<div class="d-flex justify-content-end w-75">
     @modulePermission('categories', 'edit')
    <a title="{{__('messages.common.edit')}}" data-id="{{ $row->id }}"
       class="btn px-1 text-primary fs-3 ps-0 category-edit-btn">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    @endmodulePermission
     @modulePermission('categories', 'delete')
    <a title="{{__('messages.common.delete')}}" href="javascript:void(0)" data-id="{{ $row->id }}" wire:key="{{$row->id}}"
       class="btn px-1 text-danger fs-3 ps-0 category-delete-btn">
        <i class="fa-solid fa-trash"></i>
    </a>
    @endmodulePermission
</div>

