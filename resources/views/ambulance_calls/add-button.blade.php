<div class="card-toolbar">
    <div class="d-flex align-items-center py-1">
        
            <div class="dropdown">
                <a href="javascript:void(0)" class="btn btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @modulePermission('ambulance-calls', 'add')
                    <li>
                        <a href="{{ route('ambulance-calls.create') }}"
                           class="dropdown-item  px-5">{{ __('messages.ambulance_call.new_ambulance_call') }}</a>
                    </li>
                    @endmodulePermission
                    @modulePermission('ambulance-calls', 'view')
                    <li>
                        <a href="{{ route('ambulance.calls.excel') }}"
                           class="dropdown-item  px-5">{{ __('messages.common.export_to_excel') }}</a>
                    </li>
                    @endmodulePermission
                </ul>
            </div>
        	@modulePermission('ambulance-calls', 'add')
            <a href="{{ route('ambulance-calls.create') }}"
               class="btn btn-primary"> {{ __('messages.ambulance_call.new_ambulance_call') }}</a>
       		@endmodulePermission
    </div>
</div>
