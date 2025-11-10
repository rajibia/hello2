<div class="d-flex justify-content-end w-75 ps-125 text-center">
    @modulePermission('document-types', 'edit')
    <a href="javascript:void(0)" title="{{__('messages.common.edit') }}" class="editDocTypBtn btn px-1 text-primary fs-3 ps-0" data-id="{{$row->id}}">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    @endmodulePermission
    @modulePermission('document-types', 'delete')
    <a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
       class="deleteDocTypeBtn btn px-1 text-danger fs-3 pe-0" wire:key="{{$row->id}}">
        <i class="fa-solid fa-trash"></i>
    </a>
    @endmodulePermission
</div>
