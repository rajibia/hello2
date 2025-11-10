<div class="dropdown">    
        
        <a href="#" class="btn btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
            <i class="fa fa-chevron-down"></i>
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @modulePermission('brands', 'add')
            <li>
                <a href="{{ route('brands.create') }}"
                   class="dropdown-item  px-5">{{ __('messages.medicine.new_medicine_brand') }}</a>
            </li>
            @endmodulePermission
            <li>
                <a href="{{ route('brands.excel') }}" data-turbo="false"
                   class="dropdown-item  px-5">{{ __('messages.common.export_to_excel') }}</a>
            </li>
        </ul> 
        
        @modulePermission('brands', 'add')   
        <a href="{{ route('brands.create') }}"
           class="btn btn-primary">{{ __('messages.medicine.new_medicine_brand') }}</a>
    	@endmodulePermission
</div>
