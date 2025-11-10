@extends('layouts.app')
@section('title')
    {{ __('messages.maternity_patient.maternity_patient_details') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                <a href="{{ route('maternity.index') }}"
                    class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
            </div>
        </di>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                
                {{ Form::hidden('visitedMaternityPatients', route('maternity.index'), ['id' => 'showVisitedMaternityPatients']) }}
                
                {{ Form::hidden('maternityPatientUrl', url('maternity'), ['id' => 'showMaternityPatientUrl']) }}
                {{ Form::hidden('doctorUrl', url('doctors'), ['id' => 'showMaternityDoctorUrl']) }}
                {{ Form::hidden('patient_id', $maternityPatient->patient_id, ['id' => 'showMaternityPatientId']) }}
                {{ Form::hidden('maternityPatientId', $maternityPatient->id, ['id' => 'showMaternityPatientId']) }}
                {{ Form::hidden('defaultDocumentImageUrl', asset('assets/img/default_image.jpg'), ['id' => 'showMaternityDefaultDocumentImageUrl', 'class' => 'defaultDocumentImageUrl']) }}
                {{ Form::hidden('maternityDiagnosisCreateUrl', route('maternity.diagnosis.store'), ['id' => 'showMaternityDiagnosisCreateUrl']) }}
                {{ Form::hidden('maternityDiagnosisUrl', route('maternity.diagnosis.index'), ['id' => 'showMaternityDiagnosisUrl']) }}
                {{ Form::hidden('downloadDiagnosisDocumentUrl', url('maternity-diagnosis-download'), ['id' => 'showMaternityDownloadDiagnosisDocumentUrl']) }}
                {{ Form::hidden('maternityTimelineCreateUrl', route('maternity.timelines.store'), ['id' => 'showMaternityTimelineCreateUrl']) }}
                {{ Form::hidden('maternityTimelinesUrl', route('maternity.timelines.index'), ['id' => 'showMaternityTimelinesUrl']) }}
                {{ Form::hidden('maternityPatientCaseDate', $maternityPatient->case_id ? $maternityPatient->patientCase->date : '', ['id' => 'showMaternityPatientCaseDate']) }}
                {{ Form::hidden('id', $maternityPatient->id, ['id' => 'showMaternityId']) }}
                {{ Form::hidden('appointmentDate', $maternityPatient->appointment_date, ['id' => 'showMaternityAppointmentDate']) }}
                {{ Form::hidden('maternityPatients', __('messages.maternity_patient.maternity_patient'), ['id' => 'maternityPatients']) }}
                {{ Form::hidden('maternityDiagnosis', __('messages.maternity_diagnosis'), ['id' => 'maternityDiagnosisDeleteBtn']) }}
                {{ Form::hidden('maternityTimeline', __('messages.maternity_timeline'), ['id' => 'maternityTimeline']) }}
                {{ Form::hidden('deleteVariable', __('messages.common.delete'), ['class' => 'deleteVariable']) }}
                {{ Form::hidden('yesVariable', __('messages.common.yes'), ['class' => 'yesVariable']) }}
                {{ Form::hidden('noVariable', __('messages.common.no'), ['class' => 'noVariable']) }}
                {{Form::hidden('bootstrapUrl',asset('assets/css/bootstrap.min.css'),['id'=>'showMaternityBootstrapUrl'])}}
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
            @include('maternity_timelines.add_modal')
            @include('maternity_timelines.edit_modal')
            @include('maternity_provisional_diagnoses.add_modal')
            @include('maternity_provisional_diagnoses.edit_modal')
            @include('maternity_provisional_diagnoses.view_modal')

            @include('maternity.show_fields')
            @include('maternity_diagnoses.add_modal')
            @include('maternity_diagnoses.edit_modal')
            @include('maternity_diagnoses.view_modal')
            
            @include('maternity_diagnoses.templates.templates')
            @include('maternity.templates.templates')



            @include('pathology_tests.show_modal')
            @include('radiology_tests.show_modal')

            @include('pathology_tests.templates.templates')
            @include('radiology_tests.templates.templates')
        </div>
    </div>
@endsection
@section('scripts')
    {{-- assets/js/maternity_tab_active/maternity_tab_active.js --}}
    {{-- assets/js/maternity_patients/visits.js --}}
    {{-- assets/js/maternity_diagnosis/maternity_diagnosis.js --}}
    {{-- assets/js/maternity_timelines/maternity_timelines.js --}}
@endsection
