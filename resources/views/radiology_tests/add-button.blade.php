@php
    $currentRoute = request()->route()->getName();
@endphp

@if (($currentRoute == 'radiology.test.index' || $currentRoute == 'radiology.test.show') && !str_contains($currentRoute, 'livewire'))
        <a href="{{ route('radiology.test.create') }}" class="btn btn-primary">
            {{ __('messages.radiology_test.new_bill') }}
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
    // dd($ipdId);

@endphp

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor')) && $pId != null)
    <a href="{{ route('radiology.test.create', ['ref_p_id' => $pId ]) }}"
        class="btn btn-primary">{{ __('messages.radiology_test.request_radiology_test') }}</a>
@endif

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor')) && $opdId != null)
    <a href="{{ route('radiology.test.create', ['ref_opd_id' => $opdId ]) }}"
        class="btn btn-primary">{{ __('messages.radiology_test.request_radiology_test') }}</a>
@endif

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor')) && $ipdId != null)
    <a href="{{ route('radiology.test.create', ['ref_ipd_id' => $ipdId ]) }}"
        class="btn btn-primary">{{ __('messages.radiology_test.request_radiology_test') }}</a>
@endif

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor')) && $maternityId != null)
    <a href="{{ route('radiology.test.create', ['ref_maternity_id' => $maternityId ]) }}"
        class="btn btn-primary">{{ __('messages.radiology_test.request_radiology_test') }}</a>
@endif

