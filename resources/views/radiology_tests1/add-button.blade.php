@if(Auth::user()->hasRole('Lab Technician'))
    <div class="dropdown">
        <a href="javascript:void(0)" class="btn btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
            <i class="fa fa-chevron-down"></i>
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <li>
                <a href="{{ route('radiology.test.create') }}"
                   class="dropdown-item  px-5">New Radiology Test</a>
            </li>
            <li>
                <a href="{{ route('radiology.tests.excel') }}"
                   data-turbo="false" class="dropdown-item  px-5">{{ __('messages.common.export_to_excel') }}</a>
            </li>
        </ul>
    </div>
@else
    <a href="{{ route('radiology.test.create') }}"
       class="btn btn-primary">New Radiology Test</a>
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

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Lab Technician')) && $pId != null)
    <a href="{{ route('radiology.test.create', ['ref_p_id' => $pId ]) }}"
        class="btn btn-primary">New Radiology Test</a>
@endif

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Lab Technician')) && $opdId != null)
    <a href="{{ route('radiology.test.create', ['ref_opd_id' => $opdId ]) }}"
        class="btn btn-primary">New Radiology Test</a>
@endif

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Lab Technician')) && $ipdId != null)
    <a href="{{ route('radiology.test.create', ['ref_ipd_id' => $ipdId ]) }}"
        class="btn btn-primary">New Radiology Test</a>
@endif

