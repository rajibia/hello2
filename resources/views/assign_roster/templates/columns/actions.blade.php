<!-- Check if the user does not have the role of 'Receptionist' -->
{{-- @if (!Auth::user()->hasRole('Receptionist')) --}}
    <!-- Edit Button -->
    @modulePermission('assign-roster', 'edit')
    <a href="{{ url('assign-roster'. '/'.$row->id.'/edit') }}" title="{{ __('messages.common.edit') }}" 
       class="btn px-1 text-primary fs-3 ps-0 roster-edit-btn">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
   @endmodulePermission
{{-- @endif --}}
    <!-- Delete Button -->
    @modulePermission('assign-roster', 'delete')
    <a href="javascript:void(0)" title="{{ __('messages.common.delete') }}" data-id="{{ $row->id }}" wire:key="{{ $row->id }}"
    class="assign-roster-delete-btn btn px-1 text-danger fs-3 ps-0">
        <i class="fa-solid fa-trash"></i>
    </a>
	@endmodulePermission