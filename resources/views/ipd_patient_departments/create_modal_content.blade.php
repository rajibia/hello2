<div class="modal-body">
    <div class="alert alert-danger d-none" id="ipdValidationErrorsBox"></div>
    {{ Form::open(['route' => ['ipd.patient.store'], 'method'=>'post', 'files' => true, 'id' => 'createIpdPatientForm', 'class' => 'needs-validation']) }}
    {{Form::hidden('patientCasesUrl',route('patient.cases.list'),['id'=>'createPatientCasesUrl','class'=>'patientCasesUrl'])}}
    {{Form::hidden('patientBedsUrl',route('patient.beds.list'),['id'=>'createPatientBedsUrl','class'=>'patientBedsUrl'])}}
    {{Form::hidden('isEdit',false,['class'=>'isEdit'])}}
    
    @php
        // Prepare the data array with required variables
        $fieldsData = [
            'ipdNumber' => \App\Models\IpdPatientDepartment::generateUniqueIpdNumber(),
            'doctors' => \App\Models\Doctor::with('doctorUser')
                ->get()
                ->sortBy('doctorUser.first_name')
                ->pluck('doctorUser.full_name', 'id')
                ->toArray(),
            'bedTypes' => \App\Models\BedType::orderBy('title')->pluck('title', 'id')->toArray(),
            'patient_id' => '',
            'modal_form' => true
        ];
        
        // Render the fields view with the data
        $fieldsContent = view('ipd_patient_departments.fields', ['data' => $fieldsData])->render();
        
        // Remove the save/cancel buttons section
        $fieldsContent = preg_replace('/<div class="d-flex justify-content-end">.*?<\/div>/s', '', $fieldsContent);
        echo $fieldsContent;
    @endphp
    
    <input type="hidden" name="ipd_number" value="{{ $fieldsData['ipdNumber'] }}">
    
    <div class="d-flex justify-content-end mt-3">
        {!! Form::button(__('messages.common.save'), ['type' => 'button', 'class' => 'btn btn-primary me-2', 'id' => 'ipdSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) !!}
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
    </div>
</div>
{{ Form::close() }}
