{{-- <div id="add_opd_diagnoses_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.ipd_patient_diagnosis.new_ipd_diagnosis') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id'=>'addOpdDiagnosisForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="opdDiagnosisErrorsBox"></div>
                {{ Form::hidden('opd_patient_department_id',$opdPatientDepartment->id) }}
                <div class="row">
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('report_type', __('messages.ipd_patient_diagnosis.report_type').':',['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('report_type', null, ['class' => 'form-control','required','placeholder' => __('messages.ipd_patient_diagnosis.report_type')]) }}
                    </div>
                    
                    <div class="form-group col-md-12 mb-5">
                        <div class="form-group">
                            {{ Form::label('report_date', __('messages.ipd_patient_diagnosis.report_date').':',['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::text('report_date', null, ['placeholder' => __('messages.ipd_patient_diagnosis.report_date'),'class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'),'id' => 'opdDiagnosisReportDate','autocomplete' => 'off', 'required']) }}
                        </div>
                    </div>
                    <div class="form-group col-md-12 mb-5">
                        <div class="form-group">
                            {{ Form::label('description', __('messages.ipd_patient_diagnosis.description').':',['class' => 'form-label']) }}
                            {{ Form::textarea('description', null, ['placeholder' => __('messages.ipd_patient_diagnosis.description'),'class' => 'form-control', 'rows' => 4,'id'=>'opdDiagnosisDescription']) }}
                        </div>
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('document', __('messages.ipd_patient_diagnosis.document').':',['class' => 'form-label']) }}
                        <div class="d-block">
                            <div class="image-picker">
                                <div class="image previewImage" id="opdDiagnosisPreviewImage"
                                     style="background-image: url({{ asset('assets/img/default_image.jpg') }})">
                                </div>
                                <span class="picker-edit rounded-circle text-gray-500 fs-small" title="{{__('messages.ipd_patient_diagnosis.document')}}">
                                    <label>
                                        <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                        <input type="file" id="opdDiagnosisDocumentImage" name="file"
                                               class="image-upload d-none" accept="image/*"/>
                                         <input type="hidden" name="avatar_remove">
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-0">
                        {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary me-3','id'=>'btnOpdDiagnosisSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                        <button type="button" id="btnOpdDiagnosisCancel"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div> --}}

<div id="add_opd_diagnoses_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 40%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('Add OPD Diagnosis') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addOpdDiagnosisForm">
                    <div class="row">
                        <!-- Hidden input for opd_patient_department_id -->
                        {{ Form::hidden('opd_patient_department_id', $opdPatientDepartment->id) }}
                        
                        <!-- ICD-10 Code Search -->
                        <div class="form-group col-sm-6 mb-3 position-relative">
                            <label for="code_search" class="form-label">ICD-10 Code:</label>
                            <input type="text" id="code_search" class="form-control" placeholder="Search ICD-10 code..." autocomplete="off">
                            <div id="code_results" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                            <input type="hidden" name="code" id="selected_code">
                        </div>
                    
                        <?php /*
                        <!-- Diagnosis Code Select -->
                        <div class="form-group col-sm-6 mb-3">
                            <label for="code" class="form-label">{{ __('ICD-10 Code') }}:</label>
                            <select id="code" name="code" class="form-control" required>
                                <option value="" disabled selected>{{ __('Select ICD-10 Code') }}</option>
                                @foreach($diagnosisCategories as $category)
                                    <option value="{{ $category->code }}" data-name="{{ $category->name }}">{{ $category->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        */?>

                        <!-- Diagnosis Name Select (read-only) -->
                        <div class="form-group col-sm-6 mb-3">
                            <label for="name" class="form-label">{{ __('Diagnosis Name') }}:</label>
                            <input id="name" name="name" class="form-control" readonly placeholder="{{ __('Diagnosis Name') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 mb-3">
                            <label for="description" class="form-label">{{ __('Diagnosis Description') }}</label>
                            <input id="description" name="description" class="form-control" placeholder="{{ __('Diagnosis Description') }}">
                        </div>
                        {{-- <div class="form-group col-sm-6 mb-3">
                            {{ Form::label('report_date', __('messages.ipd_patient_diagnosis.report_date').':',['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::text('report_date', null, ['placeholder' => __('messages.ipd_patient_diagnosis.report_date'),'class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'),'id' => 'opdDiagnosisReportDate','autocomplete' => 'off', 'required']) }}
                        </div> --}}

                        <div class="form-group col-sm-6 mb-3">
                            {{ Form::label('report_date', __('messages.ipd_patient_diagnosis.report_date').':',['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::text('report_date', null, ['placeholder' => __('messages.ipd_patient_diagnosis.report_date'),'class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'),'id' => 'opdDiagnosisReportDate','autocomplete' => 'off', 'required']) }}
                        </div>
                    </div>

                    <div class="modal-footer p-0">
                        {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary me-3','id'=>'btnOpdDiagnosisSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                        <button type="button" id="btnOpdDiagnosisCancel"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Scripts -->
<script>
    $(document).ready(function () {
        // Live search for diagnosis code
        $('#code_search').on('input', function () {
            let query = $(this).val();
            if (query.length > 1) {
                $.ajax({
                    url: "{{ route('diagnosis.search') }}",
                    type: "POST",
                    data: { query: query },
                    success: function (data) {
                        let resultsBox = $('#code_results');
                        resultsBox.empty();
                        if (data.length) {
                            data.forEach(item => {
                                resultsBox.append(`
                                    <a href="#" class="list-group-item list-group-item-action" data-code="${item.code}" data-name="${item.name}">
                                        <strong>${item.code}</strong> - ${item.name}
                                    </a>
                                `);
                            });
                            resultsBox.show();
                        } else {
                            resultsBox.hide();
                        }
                    }
                });
            } else {
                $('#code_results').hide();
            }
        });

        // When a result is selected
        $(document).on('click', '#code_results a', function (e) {
            e.preventDefault();
            let code = $(this).data('code');
            let name = $(this).data('name');
            $('#code_search').val(code);
            $('#selected_code').val(code);
            $('#name').val(name);
            $('#code_results').hide();
        });

        // Hide results on outside click
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#code_search, #code_results').length) {
                $('#code_results').hide();
            }
        });
    });
</script>


<script>
    document.getElementById('code').addEventListener('change', function() {
        // Get the selected option
        var selectedOption = this.options[this.selectedIndex];
        
        // Get the corresponding name from the data-name attribute
        var diagnosisName = selectedOption.getAttribute('data-name');
        
        // Set the value of the diagnosis name input
        document.getElementById('name').value = diagnosisName;
    });
</script>
