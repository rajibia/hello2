 <div class="d-flex align-items-center">
    @if(Auth::user()->hasRole('Accountant'))
         <div class="dropdown">
             <a href="#" class="btn btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
                 <i class="fa fa-chevron-down"></i>
             </a>
             <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                 @modulePermission('scans', 'add')
                 <li>
                     <a href="{{ route('scans.create') }}"
                        class="dropdown-item  px-5">{{ __('messages.scan.new_scan') }}</a>
                 </li>
                 @endmodulePermission
    			 @modulePermission('scans', 'view')
                 <li>
                     <a href="{{ route('scans.excel') }}"
                        class="dropdown-item  px-5">{{ __('messages.common.export_to_excel') }}</a>
                 </li>
                 @endmodulePermission
             </ul>
         </div>
     @else
     @modulePermission('scans', 'add')
         <a href="{{ route('scans.create') }}"
            class="btn btn-primary">  {{ __('messages.scan.new_scan') }}</a>
     @endmodulePermission
     @endif
 </div>

