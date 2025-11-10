{{ Form::open(['route' => 'opd.patient.store', 'id' => 'createOpdPatientForm', 'class' => 'needs-validation']) }}
{{ Form::hidden('revisit', isset($data['last_visit']) ? $data['last_visit']->id : null) }}
{{ Form::hidden('currency_symbol', getCurrentCurrency(), ['class' => 'currencySymbol']) }}
{{Form::hidden('patientCasesUrl',route('patient.cases.list'),['id'=>'createOpdPatientCasesUrl','class'=>'opdPatientCasesUrl'])}}
{{Form::hidden('doctorOpdChargeUrl',route('getDoctor.OPDcharge'),['id'=>'createDoctorOpdChargeUrl','class'=>'doctorOpdChargeUrl'])}}
{{Form::hidden('chargeOpdChargeUrl',route('getCharge.OPDcharge'),['id'=>'createChargeOpdChargeUrl','class'=>'chargeOpdChargeUrl'])}}
{{Form::hidden('isEdit',false,['class'=>'isEdit'])}}
{{Form::hidden('lastVisit',(isset($data['last_visit'])) ? $data['last_visit']->patient_id : false,['id'=>'createOpdLastVisit','class'=>'lastVisit'])}}
{{Form::hidden('opdAddSubmitRoute',route('opd.patient.store'),['id'=>'opdAddSubmitRoute','class'=>'opdAddSubmitRoute'])}}

{{-- Include fields but capture the output to modify it --}}
@php
    // Prepare the data array with required variables
    $fieldsData = [
        'opdNumber' => App\Models\OpdPatientDepartment::generateUniqueOpdNumber(),
        'doctors' => App\Models\Doctor::with('doctorUser')->get()->where('doctorUser.status', '=', 1)->pluck('doctorUser.full_name', 'id')->sort(),
        'charges' => [],
        'paymentMode' => App\Models\OpdPatientDepartment::PAYMENT_MODES,
        'last_visit' => isset($data['last_visit']) ? $data['last_visit'] : null
    ];
    
    // Render the fields view with the data
    $fieldsContent = view('opd_patient_departments.fields', ['data' => $fieldsData])->render();
    
    // Remove the save/cancel buttons section
    $fieldsContent = preg_replace('/<div class="d-flex justify-content-end">.*?<\/div>/s', '', $fieldsContent);
    echo $fieldsContent;
@endphp
{{ Form::close() }}
