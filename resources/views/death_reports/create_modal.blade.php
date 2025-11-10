<div id="add_death_reports_modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">{{ __('messages.death_report.new_death_report') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'addDeathReportForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="deathReportErrorsBox"></div>
                <div class="row">
                    <!-- Case -->
                    <div class="form-group col-md-6 mb-4">
                        {{ Form::label('case_id', __('messages.case.case') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::select('case_id', $cases, null, ['class' => 'form-select', 'required', 'id' => 'deathCaseId', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.case.case')]) }}
                    </div>

                    <!-- Doctor -->
                    @if (Auth::user()->hasRole('Doctor'))
                        <input type="hidden" name="doctor_id" value="{{ Auth::user()->owner_id }}">
                    @else
                        <div class="form-group col-md-6 mb-4">
                            {{ Form::label('doctor_name', __('messages.case.doctor') . ':', ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::select('doctor_id', $doctors, null, ['class' => 'form-select', 'required', 'id' => 'deathDoctorId', 'placeholder' => __('messages.web_appointment.select_doctor')]) }}
                        </div>
                    @endif

                    <!-- Date -->
                    <div class="form-group col-md-6 mb-4">
                        {{ Form::label('date', __('messages.death_report.date') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('date', null, ['id'=>'deathDate', 'class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'), 'required','autocomplete' => 'off','placeholder'=>__('messages.death_report.date')]) }}
                    </div>

                    <!-- Cause of Death -->
                    <div class="form-group col-md-6 mb-4">
                        {{ Form::label('cause_of_death', __('messages.death_report.cause_of_death') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('cause_of_death', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.death_report.cause_of_death')]) }}
                    </div>

                    <!-- Immediate Cause of Death -->
                    <div class="form-group col-md-6 mb-4">
                        {{ Form::label('immediate_cause_of_death', __('messages.death_report.immediate_cause_of_death') . ':', ['class' => 'form-label']) }}
                        {{ Form::text('immediate_cause_of_death', null, ['class' => 'form-control', 'placeholder' => __('messages.death_report.immediate_cause_of_death')]) }}
                    </div>

                    <!-- Location of Death -->
                    <div class="form-group col-md-6 mb-4">
                        {{ Form::label('location_of_death', __('messages.death_report.location_of_death') . ':', ['class' => 'form-label']) }}
                        {{ Form::text('location_of_death', null, ['class' => 'form-control', 'placeholder' => __('messages.death_report.location_of_death')]) }}
                    </div>

                    <!-- Next of Kin -->
                    <div class="form-group col-md-6 mb-4">
                        {{ Form::label('next_of_kin', __('messages.death_report.next_of_kin') . ':', ['class' => 'form-label']) }}
                        {{ Form::text('next_of_kin', null, ['class' => 'form-control', 'placeholder' => __('messages.death_report.next_of_kin')]) }}
                    </div>

                    <!-- Next of Kin Contact -->
                    <div class="form-group col-md-6 mb-4">
                        {{ Form::label('next_of_kin_contact', __('messages.death_report.next_of_kin_contact') . ':', ['class' => 'form-label']) }}
                        {{ Form::text('next_of_kin_contact', null, ['class' => 'form-control', 'placeholder' => __('messages.death_report.next_of_kin_contact')]) }}
                    </div>

                    <!-- Description -->
                    <div class="form-group col-12 mb-4">
                        {{ Form::label('description', __('messages.death_report.description').(':'), ['class' => 'form-label']) }}
                        {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => __('messages.death_report.description')]) }}
                    </div>

                    <!-- Attachments -->
                    <div class="form-group col-12 mb-4">
                        {{ Form::label('attachments', __('messages.death_report.attachments') . ':', ['class' => 'form-label']) }}
                        {{ Form::file('attachments[]', ['class' => 'form-control', 'multiple']) }}
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'deathReportSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" aria-label="Close" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
