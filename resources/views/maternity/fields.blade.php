{{ Form::hidden('revisit', isset($data['last_visit']) ? $data['last_visit']->id : null) }}
{{ Form::hidden('currency_symbol', getCurrentCurrency(), ['class' => 'currencySymbol']) }}
<div class="row gx-10 mb-5">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('patient_id', __('messages.ipd_patient.patient_id') . ':', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    <div class="form-group mb-3 position-relative">

                    <input type="text" id="patient_search" class="form-control" placeholder="Search and select patient " autocomplete="off">
                    <div id="patient_results" class="list-group ipdPatientId position-absolute w-100" style="z-index: 1000;"></div>
                    <input type="hidden" name="patient_id" id="selected_patient_id">
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-3"  style="display:none">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('case_id', __('messages.ipd_patient.case_id') . ':', ['class' => 'form-label']) }}
                    {{ Form::select('case_id', [null], null, ['class' => 'form-select', 'required', 'id' => 'CaseId', 'disabled', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') . __('messages.cases')]) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('maternity_number', __('messages.maternity_patient.maternity_number') . ':', ['class' => 'form-label']) }}
                    {{ Form::text('maternity_number', $data['maternityNumber'], ['class' => 'form-control', 'readonly', 'id'=>'maternityNumber', 'placeholder' => __('messages.maternity_patient.maternity_number')]) }}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('height', __('messages.ipd_patient.height') . ':', ['class' => 'form-label']) }}
                    {{ Form::number('height', isset($data['last_visit']) ? $data['last_visit']->height : 0, ['placeholder' => __('messages.ipd_patient.height') ,'class' => 'form-control', 'max' => '100', 'step' => '.01']) }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('weight', __('messages.ipd_patient.weight') . ':', ['class' => 'form-label']) }}
                    {{ Form::number('weight', isset($data['last_visit']) ? $data['last_visit']->weight : 0, ['placeholder' => __('messages.ipd_patient.weight'),'class' => 'form-control', 'max' => '200', 'step' => '.01']) }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('bp', __('messages.ipd_patient.bp').':', ['class' => 'form-label']) }}
                    {{ Form::text('bp', (isset($data['last_visit'])) ? $data['last_visit']->bp : null, ['class' => 'form-control','placeholder'=>__('messages.ipd_patient.bp')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('pulse', __('messages.ipd_patient_diagnosis.pulse') . ':', ['class' => 'form-label']) }}
                    {{ Form::text('pulse', (isset($data['last_visit'])) ? $data['last_visit']->pulse : null, ['class' => 'form-control', 'id'=>'pulse', 'placeholder' => __('messages.ipd_patient_diagnosis.pulse')]) }}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('respiration', __('messages.ipd_patient_diagnosis.respiration') . ':', ['class' => 'form-label']) }}
                    {{ Form::text('respiration', (isset($data['last_visit'])) ? $data['last_visit']->respiration : null, ['class' => 'form-control', 'placeholder' => __('messages.ipd_patient_diagnosis.respiration')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('temperature', __('messages.ipd_patient_diagnosis.temperature') . ':', ['class' => 'form-label']) }}
                    {{ Form::text('temperature', (isset($data['last_visit'])) ? $data['last_visit']->temperature : null, ['class' => 'form-control', 'placeholder' => __('messages.ipd_patient_diagnosis.temperature')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('oxygen_saturation', __('messages.ipd_patient_diagnosis.oxygen_saturation') . ':', ['class' => 'form-label']) }}
                    {{ Form::text('oxygen_saturation', (isset($data['last_visit'])) ? $data['last_visit']->oxygen_saturation : null, ['class' => 'form-control', 'placeholder' => __('messages.ipd_patient_diagnosis.oxygen_saturation')]) }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('appointment_date', __('messages.maternity_patient.appointment_date') . ':', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('appointment_date', null, ['placeholder' => __('messages.maternity_patient.appointment_date'),'class' => getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control', 'id' => 'maternityAppointmentDate', 'autocomplete' => 'off', 'required']) }}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="row">
    <!-- Doctor Selection -->
    <div class="col-md-3">
        <div class="mb-5">
            {{ Form::label('doctor_id', __('messages.ipd_patient.doctor_id') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('doctor_id', $data['doctors'], isset($data['last_visit']) ? $data['last_visit']->doctor_id : null, [
                'class' => 'form-select',
                'id' => 'maternityDoctorId',
                'placeholder' => __('messages.web_home.select_doctor'),
                'data-control' => 'select2'
            ]) }}
        </div>
    </div>

    <!-- Consultation Charge (Auto-populated) -->
    <div class="col-md-3">
        <div class="mb-5">
            {{ Form::label('charge_id', 'Charge for:', ['class' => 'form-label']) }}
            {{ Form::select('charge_id', $data['charges'] ?? [], isset($data['last_visit']) ? $data['last_visit']->charge_id : null, [
                'class' => 'form-select',
                'id' => 'maternityChargeId',
                'placeholder' => __('messages.select_consultation_charge'),
                'data-control' => 'select2',
                'disabled'=>true
            ]) }}
            {{ Form::hidden('charge_id', '1', ['id' => 'hiddenChargeId']) }}
        </div>
    </div>

    <!-- Standard Charge (Auto-populated) -->
    <div class="col-md-3">
        <div class="mb-5">
            <div class="form-group">
                {{ Form::label('standard_charge', __('messages.doctor_maternity_charge.standard_charge') . ':', ['class' => 'form-label']) }}
                <div class="input-group">
                    {{ Form::text('standard_charge', null, [
                        'class' => 'form-control price-input',
                        'id' => 'maternityStandardCharge',
                        'placeholder' => __('messages.doctor_maternity_charge.standard_charge'),
                        'readonly' => true
                    ]) }}
                    <div class="input-group-text border-0">
                        <span>{{ getCurrencySymbol() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="col-md-3">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('is_old_patient', __('messages.ipd_patient.is_old_patient') . ':', ['class' => 'form-label']) }}<br>
                    <div class="form-check form-switch">
                        <input class="form-check-input w-35px h-20px" name="is_old_patient" id="is_old_patient" type="checkbox" value="1">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('symptoms',__('messages.ipd_patient.symptoms').':', ['class' => 'form-label']) }}
                {{ Form::textarea('symptoms', null, ['class' => 'form-control', 'rows' => 4,'placeholder'=>__('messages.ipd_patient.symptoms')]) }}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('notes',__('messages.ipd_patient.notes').':', ['class' => 'form-label']) }}
                {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 4,'placeholder'=>__('messages.ipd_patient.notes')]) }}
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-end">
    {!! Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-3', 'id' => 'btnMaternitySave']) !!}
    <a href="{!! route('maternity.index') !!}" class="btn btn-secondary">{!! __('messages.common.cancel') !!}</a>
</div>
<script>
$(document).ready(function () {
    // Live search for patients
    $('#patient_search').on('input', function () {
        let query = $(this).val();
        if (query.length > 1) {
            $.ajax({
                url: "{{ route('patients.d.search') }}",
                type: "POST",
                data: { query: query, _token: '{{ csrf_token() }}' },
                beforeSend: function() {
                    $('#patient_results').html('<div class="list-group-item">Searching...</div>').show();
                },
                success: function (data) {
                    console.log('Search results:', data);
                    let resultsBox = $('#patient_results');
                    resultsBox.empty();

                    if (data.error) {
                        resultsBox.html('<div class="list-group-item text-danger">Search error: ' + data.error + '</div>').show();
                        return;
                    }

                    if (Object.keys(data).length) {
                        for (const patientId in data) {
                            if (data.hasOwnProperty(patientId)) {
                                const patient = data[patientId];

                                resultsBox.append(`
                                    <a href="#" class="list-group-item list-group-item-action" data-id="${patientId}" data-name="${patient.first_name} ${patient.last_name}">
                                        <strong>${patient.first_name} ${patient.last_name}</strong><br>
                                        <small>Phone: ${patient.phone || 'N/A'} | Gender: ${patient.gender == '0' ? 'Male' : 'Female'}</small>
                                    </a>
                                `);
                            }
                        }
                        resultsBox.show();
                    } else {
                        resultsBox.html('<div class="list-group-item text-muted">No patients found</div>').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    $('#patient_results').html('<div class="list-group-item text-danger">Search failed. Please try again.</div>').show();
                }
            });
        } else {
            $('#patient_results').hide();
        }
    });

    // When a result is selected
    $(document).on('click', '#patient_results a', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let name = $(this).data('name');
        $('#patient_search').val(name);
        $('#selected_patient_id').val(id);
        $('#patient_results').hide();

        // Automatically check the "is old patient" checkbox
        // If patient ID exists, it means they're already in the system
        if (id) {
            $('#is_old_patient').prop('checked', true);
        }
    });

    // Hide results on outside click
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#patient_search, #patient_results').length) {
            $('#patient_results').hide();
        }
    });
});
</script>
<script>
$(document).ready(function() {
    $('#maternityDoctorId').on('change', function() {
        const doctorId = $(this).val();
        const doctorName = $(this).find('option:selected').text();

        if (doctorId) {
            // Set loading states
            $('#maternityChargeId').html('<option value="">Loading...</option>').prop('disabled', true);
            $('#maternityStandardCharge').val('Loading...');

            $.ajax({
                url: '{{ route('getDoctor.Maternitycharge') }}',
                type: 'GET',
                data: { doctor_id: doctorId },
                                success: function(response) {
                    // Set doctor name as the charge option and disable the field
                    $('#maternityChargeId').html(`<option value="1">${doctorName}</option>`).prop('disabled', true);

                    // Set standard charge or default to 0
                    $('#maternityStandardCharge').val(response.standard_charge || '0.00');

                    // Set the charge_id value to 1 (or get it from response if available)
                    $('#maternityChargeId').val('1');
                    $('#hiddenChargeId').val('1');
                },
                error: function() {
                    $('#maternityChargeId').html('<option value="">Error loading charges</option>')
                        .prop('disabled', false);
                    $('#maternityStandardCharge').val('0.00');
                }
            });
        } else {
            // Reset fields
            $('#maternityChargeId').html('<option value="">Select Consultation Charge</option>');
            $('#maternityStandardCharge').val('');
            $('#hiddenChargeId').val('');
        }
    });

    // Update standard charge when charge is selected
    $('#maternityChargeId').on('change', function() {
        const chargeId = $(this).val();
        if (chargeId) {
            $.get('{{ route('getCharge.Maternitycharge') }}', { charge_id: chargeId })
             .then(response => {
                 $('#maternityStandardCharge').val(response.amount || '0.00');
             })
             .catch(() => $('#maternityStandardCharge').val('0.00'));
        }
    });
});
</script>
