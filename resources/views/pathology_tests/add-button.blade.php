@php
    $currentRoute = request()->route()->getName();
@endphp

@if (($currentRoute == 'pathology.test.index' || $currentRoute == 'pathology.test.show') && !str_contains($currentRoute, 'livewire'))
    <a href="{{ route('pathology.test.create') }}" class="btn btn-primary">
        {{ __('messages.pathology_test.new_bill') }}
    </a>
@endif

@aware(['component'])

@php
    $theme = $component->getTheme();
@endphp

@php
    $configurableAreas = $this->getConfigurableAreas();
    $pId = null;
    $opdId = null;
    $ipdId = null;
    $maternityId = null;
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
                if (isset($configurableArea['maternityId'])) {
                    $maternityId = $configurableArea['maternityId'];
                }
            }
        }
    }

    // Debug information (remove this in production)
    // echo "<!-- Debug: pId=$pId, opdId=$opdId, ipdId=$ipdId, maternityId=$maternityId -->";
@endphp

@modulePermission('pathology-tests', 'add')
@if ($pId != null)
    <a href="{{ route('pathology.test.create', ['ref_p_id' => $pId ]) }}"
        class="btn btn-primary">{{ __('messages.pathology_test.request_pathology_test') }}</a>
@endif
@endmodulePermission

@modulePermission('pathology-tests', 'add')
@if ($opdId != null)
    <a href="{{ route('pathology.test.create', ['ref_opd_id' => $opdId ]) }}"
        class="btn btn-primary">{{ __('messages.pathology_test.request_pathology_test') }}</a>
@endif
@endmodulePermission

@modulePermission('pathology-tests', 'add')
@if ($ipdId != null)
    <a href="{{ route('pathology.test.create', ['ref_ipd_id' => $ipdId ]) }}"
        class="btn btn-primary">{{ __('messages.pathology_test.request_pathology_test') }}</a>
@endif
@endmodulePermission

@modulePermission('pathology-tests', 'add')
@if ($maternityId != null)
    <a href="{{ route('pathology.test.create', ['ref_maternity_id' => $maternityId ]) }}"
        class="btn btn-primary">{{ __('messages.pathology_test.request_pathology_test') }}</a>
@endif
@endmodulePermission
