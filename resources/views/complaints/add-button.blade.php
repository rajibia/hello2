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

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $pId != null)
    <a href="{{ route('complaints.create', ['ref_p_id' => $pId ]) }}"
        class="btn btn-primary">New Complaint</a>
@endif

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $opdId != null)
    <a href="{{ route('complaints.create', ['ref_opd_id' => $opdId ]) }}"
        class="btn btn-primary">New Complaint</a>
@endif

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $ipdId != null)
    <a href="{{ route('complaints.create', ['ref_ipd_id' => $ipdId ]) }}"
        class="btn btn-primary">New Complaint</a>
@endif
