<!-- Check if the user does not have the role of 'Receptionist' -->
{{-- @if (!Auth::user()->hasRole('Receptionist')) --}}
    <!-- Edit Button -->
     @modulePermission('roster', 'edit')
    <a href="{{ url('roster'. '/'.$row->id.'/edit') }}" title="{{ __('messages.common.edit') }}" 
       class="btn px-1 text-primary fs-3 ps-0 roster-edit-btn">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
   	@endmodulePermission
{{-- @endif --}}
 @modulePermission('roster', 'delete')
    <!-- Delete Button -->
<a href="javascript:void(0)" title="{{ __('messages.common.delete') }}" data-id="{{ $row->id }}" wire:key="{{ $row->id }}"
   class="roster-delete-btn btn px-1 text-danger fs-3 ps-0">
    <i class="fa-solid fa-trash"></i>
</a>
@endmodulePermission
