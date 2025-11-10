 <div class="d-flex align-items-center">
    
         <div class="dropdown">
             <a href="#" class="btn btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
                 <i class="fa fa-chevron-down"></i>
             </a>
             <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                 @modulePermission('services', 'add')
                 <li>
                     <a href="{{ route('services.create') }}"
                        class="dropdown-item  px-5">{{ __('messages.service.new_service') }}</a>
                 </li>
                 @endmodulePermission
                 @modulePermission('services', 'view')
                 <li>
                     <a href="{{ route('services.excel') }}"
                        class="dropdown-item  px-5">{{ __('messages.common.export_to_excel') }}</a>
                 </li>
                 @endmodulePermission
             </ul>
         </div>
    	@modulePermission('services', 'add')
         <a href="{{ route('services.create') }}"
            class="btn btn-primary">  {{ __('messages.service.new_service') }}</a>
     	@endmodulePermission
 </div>

