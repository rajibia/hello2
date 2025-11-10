<div class="dropdown">
    <a href="javascript:void(0)" class="btn btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown"
       aria-haspopup="true" aria-expanded="false">
        {{ __('messages.common.actions') }}
        <i class="fa fa-chevron-down"></i>
    </a>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        @modulePermission('pathology-tests-templates', 'add')
        <li>
            <a href="{{ route('pathology.test.templates.create') }}" class="dropdown-item px-5">
                {{ __('messages.pathology_test.new_pathology_test_template') }}
            </a>
        </li>
        @endmodulePermission
        <li>
            <a href="{{ route('pathology.tests.excel') }}" data-turbo="false" class="dropdown-item px-5">
                {{ __('messages.common.export_to_excel') }}
            </a>
        </li>
    </ul>
</div>
@modulePermission('pathology-tests-templates', 'add')
<a href="{{ route('pathology.test.templates.create') }}" class="btn btn-primary">
    {{ __('messages.pathology_test.new_pathology_test_template') }}
</a>
@endmodulePermission

{{-- @aware(['component']) --}}
 
@php
    $theme = $component->getTheme();
@endphp 

@php
    $configurableAreas = $this->getConfigurableAreas();
    $pId = null;
    $opdId = null;
    $ipdId = null;
    if (isset($configurableAreas['toolbar-right-end'])) {
        
        foreach ($configurableAreas['toolbar-right-end'] as $configurableArea) {
            
            if (is_array($configurableArea)) {
                if (isset($configurableArea['patientId'])) {
                    $pId = $configurableArea['patientId'];
                }
                if (isset($configurableArea['opdId'])) {
                    $opdId = $configurableArea['opdId'];
                }
                if (isset($configurableArea['ipdId'])) {
                    $ipdId = $configurableArea['ipdId'];
                }
            }
        }
    }
    // dd($ipdId);
    
@endphp

@modulePermission('pathology-tests-templates', 'add')
@if ($pId != null)
    <a href="{{ route('pathology.test.template.create', ['ref_p_id' => $pId ]) }}"
        class="btn btn-primary">{{ __('messages.pathology_test.new_pathology_test') }}</a>
@endif
@endmodulePermission

@modulePermission('pathology-tests-templates', 'add')
@if ($opdId != null)
    <a href="{{ route('pathology.test.template.create', ['ref_opd_id' => $opdId ]) }}"
        class="btn btn-primary">{{ __('messages.pathology_test.new_pathology_test') }}</a>
@endif
@endmodulePermission

@modulePermission('pathology-tests-templates', 'add')
@if ($ipdId != null)
    <a href="{{ route('pathology.test.template.create', ['ref_ipd_id' => $ipdId ]) }}"
        class="btn btn-primary">{{ __('messages.pathology_test.request_pathology_test') }}</a>
@endif
@endmodulePermission

