<div class="d-flex justify-content-end w-75 ps-125 text-center">
    @modulePermission('roles', 'view')
    <a href="{{route('roles.module-assign', $row->id)}}" title="{{ 'Assign Modules' }}" data-id="{{ $row->id }}"
       class="role-assign-module-btn btn px-1 text-primary fs-3 ps-0">
        <i class="fa-solid fa-circle-plus"></i>
    </a>
    @endmodulePermission
    @modulePermission('roles', 'edit')
    <a href="javascript:void(0)" title="{{__('messages.common.edit') }}" data-id="{{ $row->id }}"
       class="role-edit-btn btn px-1 text-primary fs-3 ps-0">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    @endmodulePermission
    @modulePermission('roles', 'delete')
    <a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
       class="role-delete-btn btn px-1 text-danger fs-3 pe-0" wire:key="{{$row->id}}">
        <i class="fa-solid fa-trash"></i>
    </a>
    @endmodulePermission
</div>
