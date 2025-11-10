<div class="d-flex align-items-center">
    @modulePermission('pharmacists', 'edit')
    <a href="{{ route('pharmacists.edit',$row->id)}}" title="{{__('messages.common.edit') }}"
       class="btn px-1 text-primary fs-3 ps-0">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    @endmodulePermission
    @modulePermission('pharmacists', 'delete')
    <a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
       class="delete-pharmacist-btn btn px-1 text-danger fs-3 pe-0" wire:key="{{$row->id}}">
        <i class="fa-solid fa-trash"></i>
    </a>
    @endmodulePermission
</div>
