 <div class="d-flex align-items-center">
    @if(Auth::user()->hasRole('Accountant'))
         <div class="dropdown">
             <a href="#" class="btn btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
                 <i class="fa fa-chevron-down"></i>
             </a>
             <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                 @modulePermission('diagnosis', 'add')
                 <li>
                     <a href="{{ route('diagnosis.create') }}"
                        class="dropdown-item  px-5">{{ __('messages.diagnosis.new_diagnosis') }}</a>
                 </li>
                 @endmodulePermission
                 @modulePermission('diagnosis', 'view')
                 <li>
                     <a href="{{ route('diagnosis.excel') }}"
                        class="dropdown-item  px-5">{{ __('messages.common.export_to_excel') }}</a>
                 </li>
                 @endmodulePermission
             </ul>
         </div>
     @else
     @modulePermission('diagnosis', 'add')
         <a href="{{ route('diagnosis.create') }}"
            class="btn btn-primary">  {{ __('messages.diagnosis.new_diagnosis') }}</a>
     @endmodulePermission
     @endif
 </div>

