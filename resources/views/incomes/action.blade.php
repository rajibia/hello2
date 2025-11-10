<div class="d-flex align-items-center">
    @modulePermission('incomes', 'edit')
    <a title="{{__('messages.common.edit')}}" data-id="{{ $row->id }}"
       class=" btn px-1 text-primary fs-3 ps-0 editIncomesBtn">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    @endmodulePermission
    @modulePermission('incomes', 'delete')
    <a title="{{__('messages.common.delete')}}" href="javascript:void(0)" data-id="{{ $row->id }}" wire:key="{{$row->id}}"
       class="btn px-1 text-danger fs-3 pe-0 deleteIncomesBtn">
        <i class="fa-solid fa-trash"></i>
    </a>
    @endmodulePermission
</div>    

    
