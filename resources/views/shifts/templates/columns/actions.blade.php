{{-- <div class="d-flex gap-2">
    <a href="" title="{{ __('messages.common.edit') }}"
       class="btn btn-primary p-1" data-id="{{ $row->id }}">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
	@modulePermission('shifts', 'delete')
    <a href="javascript:void(0)" data-id="{{ $row->id }}" title="{{ __('messages.common.delete') }}" wire:key="{{ $row->id }}"
        class="btn btn-danger p-1" onclick="return confirm('Are you sure you want to delete this shift?');">
        <i class="fa-solid fa-trash"></i>
    </a>
    @endmodulePermission
</div> --}}

{{-- <a href="{{url('doctors'. '/'.$row->id.'/edit')}}" title="{{__('messages.common.edit') }}"
   class="btn px-1 text-primary fs-3 ps-0 doctor-edit-btn">
    <i class="fa-solid fa-pen-to-square"></i>
</a>
<a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}" wire:key="{{$row->id}}"
   class="doctor-delete-btn btn px-1 text-danger fs-3 ps-0">
    <i class="fa-solid fa-trash"></i>
</a> --}}

<!-- Check if the user does not have the role of 'Receptionist' -->
{{-- @if (!Auth::user()->hasRole('Receptionist')) --}}
    <!-- Edit Button -->
    @modulePermission('shifts', 'edit')
    <a href="{{ url('shifts'. '/'.$row->id.'/edit') }}" title="{{ __('messages.common.edit') }}" 
       class="btn px-1 text-primary fs-3 ps-0 shift-edit-btn">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
   @endmodulePermission
{{-- @endif --}}
    <!-- Delete Button -->
    @modulePermission('shifts', 'delete')
    <a href="javascript:void(0)" title="{{ __('messages.common.delete') }}" data-id="{{ $row->id }}" wire:key="{{ $row->id }}"
       class="shift-delete-btn btn px-1 text-danger fs-3 ps-0">
        <i class="fa-solid fa-trash"></i>
    </a>
    @endmodulePermission