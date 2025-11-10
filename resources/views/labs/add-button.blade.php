 <div class="d-flex align-items-center">
    @if(Auth::user()->hasRole('Accountant'))
         <div class="dropdown">
             <a href="#" class="btn btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
                 <i class="fa fa-chevron-down"></i>
             </a>
             <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                 @modulePermission('labs', 'new')
                 <li>
                     <a href="{{ route('labs.create') }}"
                        class="dropdown-item  px-5">{{ __('messages.lab.new_lab') }}</a>
                 </li>
                 @endmodulePermission
                 @modulePermission('labs', 'view')
                 <li>
                     <a href="{{ route('labs.excel') }}"
                        class="dropdown-item  px-5">{{ __('messages.common.export_to_excel') }}</a>
                 </li>
                 @endmodulePermission
             </ul>
         </div>
     @else
     	@modulePermission('labs', 'add')
         <a href="{{ route('labs.create') }}"
            class="btn btn-primary">  {{ __('messages.lab.new_lab') }}</a>
        @endmodulePermission
     @endif
 </div>

