{{-- <a href="{{(url('medicines'.'/'. $row->id .'/transfer'))}}" title="<?php echo __('messages.common.transfer') ?>"
   class=" btn px-1 text-success fs-3 ps-0">
                <i class="fa-solid fa-exchange"></i>
</a>
<a href="{{(url('medicines'.'/'. $row->id .'/edit'))}}" title="<?php echo __('messages.common.edit') ?>"
   class=" btn px-1 text-primary fs-3 ps-0">
                <i class="fa-solid fa-pen-to-square"></i>
</a>
<a href="javascript:void(0)" title="<?php echo __('messages.common.delete') ?>" data-id="{{$row->id}}" wire:key="{{$row->id}}"
   class="deleteMedicineBtn  btn px-1 text-danger fs-3 ps-0">
                  <i class="fa-solid fa-trash"></i>
</a> --}}
<div class="dropdown">
   <a href="#" class="btn btn-primary dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions
   </a>
   <ul class="dropdown-menu action-dropdown" aria-labelledby="dropdownMenuButton">
      @role('Admin|Pharmacist')
       <li>
           <a href="{{(url('medicines'.'/'. $row->id .'/transfer'))}}" class="dropdown-item  px-5">{{ __('messages.transfer')}}</a>
       </li>
      @endrole
      @role('Admin|Pharmacist')
       <li>
           <a href="{{(url('medicines'.'/'. $row->id .'/adjust-stock'))}}" class="dropdown-item  px-5">Stock Adjustment</a>
       </li>
      @endrole
      @modulePermission('medicines', 'edit')
       <li>
           <a href="{{(url('medicines'.'/'. $row->id .'/edit'))}}" class="dropdown-item  px-5">{{ __('messages.common.edit')}}</a>
       </li>
       @endmodulePermission
       @modulePermission('medicines', 'delete')
       <li>
         <a href="javascript:void(0)" class="dropdown-item  px-5 deleteMedicineBtn" data-id="{{$row->id}}" wire:key="{{$row->id}}">{{__('messages.common.delete')}}</a>
     </li>
     @endmodulePermission
   </ul>
</div>