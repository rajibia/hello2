
    <div class="dropdown">
        <a href="javascript:void(0)" class="btn btn-primary dropdown-toggl" id="dropdownMenuButton" data-bs-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
            <i class="fa fa-chevron-down"></i>
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @modulePermission('doctor-opd-charges', 'add')
            <li>
                <a href="javascript:void(0)"
                   class="dropdown-item  px-5" data-bs-toggle="modal"
                   data-bs-target="#add_doctor_opd_charges_modal">{{ __('messages.doctor_opd_charge.new_doctor_opd_charge') }}</a>
            </li>
            @endmodulePermission
            <li>
                <a href="{{ route('doctor.opd.charges.excel') }}"
                   class="dropdown-item  px-5" data-turbo="false">{{ __('messages.common.export_to_excel') }}</a>
            </li>
        </ul>
    </div>

    	<!--@modulePermission('doctor-opd-charges', 'add')
        <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal"
       data-bs-target="#add_doctor_opd_charges_modal">{{ __('messages.doctor_opd_charge.new_doctor_opd_charge') }}</a>
       @endmodulePermission-->

