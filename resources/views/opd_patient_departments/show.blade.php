@extends('layouts.app')
@section('title')
    {{ __('messages.opd_patient.opd_patient_details') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                <a href="{{ route('opd.patient.index') }}"
                    class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
            </div>
        </di>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                
                {{ Form::hidden('visitedOPDPatients', route('opd.patient.index'), ['id' => 'showVisitedOPDPatients']) }}
                
                {{ Form::hidden('opdPatientUrl', url('opds'), ['id' => 'showOpdPatientUrl']) }}
                {{ Form::hidden('doctorUrl', url('doctors'), ['id' => 'showOpdDoctorUrl']) }}
                {{ Form::hidden('patient_id', $opdPatientDepartment->patient_id, ['id' => 'showOpdPatientId']) }}
                {{ Form::hidden('opdPatientDepartmentId', $opdPatientDepartment->id, ['id' => 'showOpdPatientDepartmentId']) }}
                {{ Form::hidden('defaultDocumentImageUrl', asset('assets/img/default_image.jpg'), ['id' => 'showOpdDefaultDocumentImageUrl', 'class' => 'defaultDocumentImageUrl']) }}
                {{ Form::hidden('opdDiagnosisCreateUrl', route('opd.diagnosis.store'), ['id' => 'showOpdDiagnosisCreateUrl']) }}
                {{ Form::hidden('opdDiagnosisUrl', route('opd.diagnosis.index'), ['id' => 'showOpdDiagnosisUrl']) }}
                {{ Form::hidden('downloadDiagnosisDocumentUrl', url('opd-diagnosis-download'), ['id' => 'showOpdDownloadDiagnosisDocumentUrl']) }}
                {{ Form::hidden('opdTimelineCreateUrl', route('opd.timelines.store'), ['id' => 'showOpdTimelineCreateUrl']) }}
                {{ Form::hidden('opdTimelinesUrl', route('opd.timelines.index'), ['id' => 'showOpdTimelinesUrl']) }}
                {{ Form::hidden('opdPatientCaseDate', $opdPatientDepartment->case_id ? $opdPatientDepartment->patientCase->date : '', ['id' => 'showOpdPatientCaseDate']) }}
                {{ Form::hidden('id', $opdPatientDepartment->id, ['id' => 'showOpdId']) }}
                {{ Form::hidden('appointmentDate', $opdPatientDepartment->appointment_date, ['id' => 'showOpdAppointmentDate']) }}
                {{ Form::hidden('opdPatients', __('messages.opd_patient.opd_patient'), ['id' => 'opdPatients']) }}
                {{ Form::hidden('opdDiagnosis', __('messages.opd_diagnosis'), ['id' => 'opdDiagnosisDeleteBtn']) }}
                {{ Form::hidden('opdTimeline', __('messages.opd_timeline'), ['id' => 'opdTimeline']) }}
                {{ Form::hidden('deleteVariable', __('messages.common.delete'), ['class' => 'deleteVariable']) }}
                {{ Form::hidden('yesVariable', __('messages.common.yes'), ['class' => 'yesVariable']) }}
                {{ Form::hidden('noVariable', __('messages.common.no'), ['class' => 'noVariable']) }}
                {{Form::hidden('bootstrapUrl',asset('assets/css/bootstrap.min.css'),['id'=>'showOpdBootstrapUrl'])}}
                {{ Form::hidden('cancelVariable', __('messages.common.cancel'), ['class' => 'cancelVariable']) }}
                {{ Form::hidden('confirmVariable', __('messages.common.are_you_sure_want_to_delete_this'), ['class' => 'confirmVariable']) }}
                {{ Form::hidden('deletedVariable', __('messages.common.deleted'), ['class' => 'deletedVariable']) }}
                {{ Form::hidden('hasBeenDeletedVariable', __('messages.common.has_been_deleted'), ['class' => 'hasBeenDeletedVariable']) }}
                {{ Form::hidden('generalExaminationCreateUrl', route('general_examinations.store'), ['id' => 'showGeneralExaminationCreateUrl']) }}
                {{ Form::hidden('generalExaminationUrl', route('general_examinations.index'), ['id' => 'showGeneralExaminationUrl']) }}
                {{ Form::hidden('generalExamination', 'General Examination', ['id' => 'generalExaminationDeleteBtn']) }}
                {{ Form::hidden('complaintCreateUrl', route('complaints.store'), ['id' => 'showComplaintCreateUrl']) }}
                {{ Form::hidden('complaintUrl', route('complaints.index'), ['id' => 'showComplaintUrl']) }}
                {{ Form::hidden('complaint', 'Complaint', ['id' => 'complaintDeleteBtn']) }}
                {{ Form::hidden('nursingNoteCreateUrl', route('nursing_progress_notes.store'), ['id' => 'showNursingNoteCreateUrl']) }}
                {{ Form::hidden('nursingNoteUrl', route('nursing_progress_notes.index'), ['id' => 'showNursingNoteUrl']) }}
                {{ Form::hidden('nursingNote', 'Nursing Note', ['id' => 'nursingNoteDeleteBtn']) }}
                {{ Form::hidden('systemicExaminationCreateUrl', route('systemic_examinations.store'), ['id' => 'showSystemicExaminationCreateUrl']) }}
                {{ Form::hidden('systemicExaminationUrl', route('systemic_examinations.index'), ['id' => 'showSystemicExaminationUrl']) }}
                {{ Form::hidden('systemicExamination', 'Systemic Examination', ['id' => 'systemicExaminationDeleteBtn']) }}
                {{ Form::hidden('noteCreateUrl', route('notes.store'), ['id' => 'showNoteCreateUrl']) }}
                {{ Form::hidden('noteUrl', route('notes.index'), ['id' => 'showNoteUrl']) }}
                {{ Form::hidden('note', 'Note', ['id' => 'noteDeleteBtn']) }}
                {{ Form::hidden('treatmentCreateUrl', route('treatments.store'), ['id' => 'showTreatmentCreateUrl']) }}
                {{ Form::hidden('treatmentUrl', route('treatments.index'), ['id' => 'showTreatmentUrl']) }}
                {{ Form::hidden('treatment', 'Treatment', ['id' => 'treatmentDeleteBtn']) }}

                <div class="col-12">
                    @include('flash::message')
                </div>
            </div>
            
            @include('pathology_tests.add_modal')
            @include('pathology_tests.edit_modal')
            @include('radiology_tests.add_modal')
            @include('radiology_tests.edit_modal')
            @include('complaints.add_modal')
            @include('complaints.edit_modal')
            @include('complaints.view_modal')
            @include('general_examinations.add_modal')
            @include('general_examinations.edit_modal')
            @include('general_examinations.view_modal')
            @include('systemic_examinations.add_modal')
            @include('systemic_examinations.edit_modal')
            @include('systemic_examinations.view_modal')
            @include('treatments.add_modal')
            @include('treatments.edit_modal')
            @include('treatments.view_modal')
            @include('notes.add_modal')
            @include('notes.edit_modal')
            @include('notes.view_modal')
            @include('nursing_progress_notes.add_modal')
            @include('nursing_progress_notes.edit_modal')
            @include('nursing_progress_notes.view_modal')
            @include('opd_timelines.add_modal')
            @include('opd_timelines.edit_modal')
            @include('opd_provisional_diagnoses.add_modal')
            @include('opd_provisional_diagnoses.edit_modal')
            @include('opd_provisional_diagnoses.view_modal')

            @include('opd_patient_departments.show_fields')
            @include('opd_diagnoses.add_modal')
            @include('opd_diagnoses.edit_modal')
            @include('opd_diagnoses.view_modal')
            
            @include('opd_diagnoses.templates.templates')
            @include('opd_patient_departments.templates.templates')



            @include('pathology_tests.show_modal')
            @include('radiology_tests.show_modal')

            @include('pathology_tests.templates.templates')
            @include('radiology_tests.templates.templates')
        </div>
    </div>
@endsection
@section('scripts')
    {{-- assets/js/opd_tab_active/opd_tab_active.js --}}
    {{-- assets/js/opd_patients/visits.js --}}
    {{-- assets/js/opd_diagnosis/opd_diagnosis.js --}}
    {{-- assets/js/opd_timelines/opd_timelines.js --}}
@endsection
