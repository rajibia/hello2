<!-- Modal -->
<div id="add_ipd_diagnosis_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add IPD Diagnosis</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{ Form::open(['id'=>'addIpdDiagnosisForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="ipdDiagnosisErrorsBox"></div>
                {{ Form::hidden('ipd_patient_department_id', $ipdPatientDepartment->id) }}
                {{ Form::hidden('opd_patient_department_id', $ipdPatientDepartment->id) }}

                <div class="row">
                    <!-- ICD-10 Code Search -->
                    <div class="form-group col-sm-6 mb-3 position-relative">
                        <label for="code_search" class="form-label">ICD-10 Code:</label>
                        <input type="text" id="code_search" class="form-control" placeholder="Search ICD-10 code..." autocomplete="off">
                        <div id="code_results" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                        <input type="hidden" name="code" id="selected_code">
                    </div>

                    <!-- Diagnosis Name -->
                    <div class="form-group col-sm-6 mb-3">
                        <label for="name" class="form-label">Diagnosis Name:</label>
                        <input id="name" name="name" class="form-control" readonly placeholder="Diagnosis Name">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6 mb-3">
                        <label for="description" class="form-label">Diagnosis Description</label>
                        <input id="description" name="description" class="form-control" placeholder="Diagnosis Description">
                    </div>

                    <div class="form-group col-sm-6 mb-3">
                        {{ Form::label('report_date', 'Report Date:', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('report_date', null, ['placeholder' => 'Report Date', 'class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'), 'id' => 'opdDiagnosisReportDate', 'autocomplete' => 'off', 'required']) }}
                    </div>
                </div>
            </div>

            <div class="modal-footer p-0">
                {{ Form::button('Save', ['type'=>'submit','class' => 'btn btn-primary me-3','id'=>'ipdDiagnosisSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            {{ Form::close() }}
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
