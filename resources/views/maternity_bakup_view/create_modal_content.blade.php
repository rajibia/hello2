{{ Form::open(['route' => 'maternity.patient.store', 'id' => 'createMaternityForm', 'class' => 'needs-validation']) }}
{{ Form::hidden('revisit', isset($data['last_visit']) ? $data['last_visit']->id : null) }}
{{ Form::hidden('currency_symbol', getCurrentCurrency(), ['class' => 'currencySymbol']) }}
{{Form::hidden('patientCasesUrl',route('patient.cases.list'),['id'=>'createMaternityPatientCasesUrl','class'=>'maternityPatientCasesUrl'])}}
{{Form::hidden('doctorMaternityChargeUrl',route('getDoctor.Maternitycharge'),['id'=>'createDoctorMaternityChargeUrl','class'=>'doctorMaternityChargeUrl'])}}
{{Form::hidden('chargeMaternityChargeUrl',route('getCharge.Maternitycharge'),['id'=>'createChargeMaternityChargeUrl','class'=>'chargeMaternityChargeUrl'])}}
{{Form::hidden('isEdit',false,['class'=>'isEdit'])}}
{{Form::hidden('lastVisit',(isset($data['last_visit'])) ? $data['last_visit']->patient_id : false,['id'=>'createMaternityLastVisit','class'=>'lastVisit'])}}
{{Form::hidden('maternityAddSubmitRoute',route('maternity.patient.store'),['id'=>'maternityAddSubmitRoute','class'=>'maternityAddSubmitRoute'])}}

{{-- Include fields but capture the output to modify it --}}
@php
    // Prepare the data array with required variables
    $fieldsData = [
        'maternityNumber' => App\Models\Maternity::generateUniqueMaternityNumber(),
        'doctors' => App\Models\Doctor::with('doctorUser')->get()->where('doctorUser.status', '=', 1)->pluck('doctorUser.full_name', 'id')->sort(),
        'charges' => [],
        'paymentMode' => App\Models\Maternity::PAYMENT_MODES,
        'last_visit' => isset($data['last_visit']) ? $data['last_visit'] : null
    ];
    
    // Render the fields view with the data
    $fieldsContent = view('maternity.fields', ['data' => $fieldsData])->render();
    
    // Remove the save/cancel buttons section
    $fieldsContent = preg_replace('/<div class="d-flex justify-content-end">.*?<\/div>/s', '', $fieldsContent);
    echo $fieldsContent;
@endphp
{{ Form::close() }}
