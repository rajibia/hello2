{{-- resources/views/ipd_patient_departments/create_modal_content.blade.php --}}
<div class="modal-body">
    <div class="alert alert-danger d-none" id="ipdValidationErrorsBox"></div>

    {{ Form::open([
        'route' => 'ipd.patient.store',
        'method' => 'post',
        'files' => true,
        'id' => 'createIpdPatientForm',
        'class' => 'needs-validation'
    ]) }}

        @csrf

        {{ Form::hidden('patientCasesUrl', route('patient.cases.list'), ['class' => 'patientCasesUrl']) }}
        {{ Form::hidden('patientBedsUrl', route('patient.beds.list'), ['class' => 'patientBedsUrl']) }}
        {{ Form::hidden('isEdit', false, ['class' => 'isEdit']) }}

        @php
            $fieldsData = [
                'ipdNumber' => \App\Models\IpdPatientDepartment::generateUniqueIpdNumber(),
                'doctors' => \App\Models\Doctor::with('doctorUser')
                    ->get()
                    ->sortBy('doctorUser.first_name')
                    ->pluck('doctorUser.full_name', 'id')
                    ->toArray(),
                'bedTypes' => \App\Models\BedType::orderBy('title')->pluck('title', 'id')->toArray(),
                'patient_id' => '',
                'patient_name' => '',
                'modal_form' => true
            ];
            
            // Render fields and remove the save/cancel buttons (we add them manually below)
            $fieldsContent = view('ipd_patient_departments.fields', ['data' => $fieldsData])->render();
            $fieldsContent = preg_replace('/<div class="d-flex justify-content-end">.*?<\/div>/s', '', $fieldsContent);
            echo $fieldsContent;
        @endphp

        <input type="hidden" name="ipd_number" value="{{ $fieldsData['ipdNumber'] }}">

        <div class="d-flex justify-content-end mt-5 gap-3">
            <button type="submit" class="btn btn-primary px-4">
                {{ __('messages.common.save') }}
            </button>
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                {{ __('messages.common.cancel') }}
            </button>
        </div>
    {{ Form::close() }}
</div>