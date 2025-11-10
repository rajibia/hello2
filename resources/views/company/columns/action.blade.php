<div class="btn-group">
    @modulePermission('companies', 'edit')
    	<a href="{{ route('companies.edit', $row->id) }}" class="btn btn-sm btn-primary">Edit</a>
    @endmodulePermission
    @modulePermission('companies', 'view')
    <a href="{{ route('companies.view', $row->id) }}" class="btn btn-sm btn-info">View</a>
    @endmodulePermission
    @modulePermission('companies', 'delete')
    <button wire:click="$emit('deleteCompany', {{ $row->id }})" class="btn btn-sm btn-danger">Delete</button>
    @endmodulePermission
</div>
