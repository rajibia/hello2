
    <div class="dropdown">
        <a href="#" class="btn btn-primary dropdown-toggle" id="dropdownMenuButton"
           data-bs-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
        </a>
        <ul class="dropdown-menu action-dropdown" aria-labelledby="dropdownMenuButton">
            @modulePermission('medicine-purchase', 'add')
            <li>
                <a href="{{ route('medicines.create') }}"
                   class="dropdown-item  px-5">{{ __('messages.medicine.new_medicine') }}</a>
            </li>
            @endmodulePermission
            @modulePermission('medicine-purchase', 'view')
            <li>
                <a href="{{ route('medicines.excel') }}"
                   class="dropdown-item  px-5" data-turbo="false">{{ __('messages.common.export_to_excel') }}</a>
            </li>
            @endmodulePermission
        </ul>
    </div>
	@modulePermission('medicine-purchase', 'add')
    <a href="{{ route('medicines.create') }}" class="btn btn-primary">
        {{ __('messages.medicine.new_medicine') }}
    </a>
    @endmodulePermission

