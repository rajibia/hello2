@modulePermission('pathology-tests-templates', 'view')
<a href="javascript:void(0)" title="<?php echo __('messages.new_change.view_pathology_test'); ?>" class="showPathologyTestBtn btn px-0 text-success fs-3" 
    data-id="{{ $row->id }}" wire:key="{{$row->id}}" >
    <i class="fa fa-eye"></i>
</a>
@endmodulePermission
@modulePermission('pathology-tests-templates', 'view')
<a href="{{ route('pathology.test.template.pdf', $row->id) }}" title="<?php echo __('messages.new_change.print_pathology_test'); ?>" class="btn px-2 text-warning fs-3" 
    target="_blank">
    <i class="fa fa-print"></i>
</a>
@endmodulePermission
@modulePermission('pathology-tests-templates', 'edit')
<a href="{{ route('pathology.test.template.edit',$row->id)}}" title="{{__('messages.common.edit') }}"
   class="btn px-1 text-primary fs-3 ps-0">
    <i class="fa-solid fa-pen-to-square"></i>
</a>
@endmodulePermission
@modulePermission('pathology-tests-templates', 'delete')
<a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
   class="deletePathologyTestBtn btn px-1 text-danger fs-3 pe-0" wire:key="{{$row->id}}">
    <i class="fa-solid fa-trash"></i>
</a>
@endmodulePermission