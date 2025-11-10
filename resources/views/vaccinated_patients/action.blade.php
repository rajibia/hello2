    <div class="d-flex align-items-center">
        @modulePermission('vaccinated-patients', 'edit')
        <a href="javascript:void(0)" title="{{__('messages.common.edit') }}" data-id="{{ $row->id }}"
           class="edit-vaccinatedPatient-btn btn px-1 text-primary fs-3 ps-0">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
        @endmodulePermission
        @modulePermission('vaccinated-patients', 'delete')
        <a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
           class="delete-vaccinatedPatient-btn btn px-1 text-danger fs-3 pe-0" wire:key="{{$row->id}}">
            <i class="fa-solid fa-trash"></i>
        </a>
        @endmodulePermission
    </div>
