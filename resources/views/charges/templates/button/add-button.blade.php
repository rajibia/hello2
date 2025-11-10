
    <div class="dropdown">
        <a href="javascript:void(0)" class="btn btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
            <i class="fa fa-chevron-down"></i>
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @modulePermission('charges', 'add')
            <li>
                <a href="javascript:void(0)"
                   class="dropdown-item  px-5" data-bs-toggle="modal"
                   data-bs-target="#add_charges_modal">{{ __('messages.charge.new_charge') }}</a>
            </li>
            @endmodulePermission   
            <li>
                <a href="{{ route('charges.excel') }}" target="_blank"
                   class="dropdown-item  px-5">{{ __('messages.common.export_to_excel') }}</a>
            </li>
           
        </ul>
    </div>

	<!--@modulePermission('charges', 'add')
    <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal"
       data-bs-target="#add_charges_modal">{{ __('messages.charge.new_charge') }}</a>
    @endmodulePermission -->  

