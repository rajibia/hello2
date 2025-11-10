<div class="d-flex align-items-center flex-wrap justify-content-end">
    <div class="me-3">
        <input class="form-control custom-width" id="time_range" /><b class="caret"></b>
    </div>

    @modulePermission('appointments', 'add')
        <div class="d-flex align-items-center py-1">
            <a href="{{ route('appointments.create') }}"
                class="btn btn-primary">{{ __('messages.appointment.new_appointment') }}</a>
        </div>
    @endmodulePermission
    @modulePermission('appointments', 'view')
        <div class="d-flex align-items-center py-1">
            <a data-turbo="false" href="{{ route('appointments.excel') }}"
                class="btn btn-primary">{{ __('messages.common.export_to_excel') }}</a>
        </div>
   @endmodulePermission
    @if (Auth::user()->hasRole('Patient|Receptionist'))
        <div class="dropdown pt-1">
            <a href="#" class="btn btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
                <i class="fa-solid fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                @modulePermission('appointments', 'add')
                <li>
                    <a href="{{ route('appointments.create') }}" class="dropdown-item  px-5">
                        {{ __('messages.appointment.new_appointment') }}
                    </a>
                </li>
                @endmodulePermission
                @modulePermission('appointments', 'view')
                <li>
                    <a href="{{ route('appointments.excel') }}" class="dropdown-item  px-5" data-turbo="false">
                        {{ __('messages.common.export_to_excel') }}
                    </a>
                </li>
                @endmodulePermission
            </ul>
        </div>
    @endif
</div>
