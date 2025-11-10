<div class="card-toolbar">
    <div class="d-flex align-items-center ">
        @if(Auth::user()->hasRole('Receptionist'))
            <div class="dropdown">
                <a href="javascript:void(0)" class="btn btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
                    <i class="fa fa-chevron-down"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                	@modulePermission('insurances', 'add')
                    <li>
                        <a href="{{ route('insurances.create') }}"
                           class="dropdown-item  px-5">{{ __('messages.insurance.new_insurance') }}</a>
                    </li>
                   	@endmodulePermission
                    <li>
                        <a href="{{ route('insurances.excel') }}"
                           class="dropdown-item  px-5">{{ __('messages.common.export_to_excel') }}</a>
                    </li>
                </ul>
            </div>
        @else
        @modulePermission('insurances', 'add')
            <a href="{{ route('insurances.create') }}"
               class="btn btn-primary">{{ __('messages.insurance.new_insurance') }}</a>
        @endmodulePermission
        @endif
    </div>
</div>
