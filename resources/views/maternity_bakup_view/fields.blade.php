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
                {{--
                    <select id="patientSelect" class="form-control" name="patient_id" required>
                        <option value="">Select Patient</option>
                        @foreach ($data['patients'] as $id => $patient)
                        
                            <option value="{{ $id }}" data-gender="{{ $patient['gender'] }}">{{ $id }}
                                {{ $patient['name'] }} (Gender: {{ $patient['gender'] == '0' ? 'Male' : 'Female' }})
                            </option>
                        @endforeach
                    </select>
                    --}}
                </div>
                <!-- Hidden field for patient_id to include in form submission -->
                {{-- {{ Form::hidden('patient_id', '', ['id' => 'hiddenPatientId']) }} --}}
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
        {{-- <!-- Gender Display -->
        <div id="genderDisplay" class="mt-3"></div> --}}
        <!-- Antenatal Section (Initially hidden) -->
        {{-- <div class="col-md-3" id="antenatalSection" style="display: none;">
            <div class="mb-5">
                {{ Form::label('antenatal', __('messages.ipd_patient.antenatal'), ['class' => 'form-label']) }}
                <span class="is-valid">
                    <div class="form-check form-switch">
                        {{ Form::checkbox('is_antenatal', '1', false, ['class' => 'form-check-input', 'id' => 'isAntenatal', 'tabindex' => '6']) }}
                        <label class="form-check-label" for="isAntenatal">{{ __('messages.ipd_patient.is_antenatal') }}</label>
                    </div>
                </span>
            </div>
        </div> --}}
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
        {{--
        <div class="col-md-3">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('doctor_id', __('messages.ipd_patient.doctor_id') . ':', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::select('doctor_id', $data['doctors'], isset($data['last_visit']) ? $data['last_visit']->doctor_id : null, ['placeholder' => __('messages.ipd_patient.doctor_id'),'class' => 'form-select', '', 'id' => 'opdDoctorId', 'placeholder' => __('messages.web_home.select_doctor'), 'data-control' => 'select2']) }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-5">
                <div class="mb-5">
                    {{ Form::label('charge_id', __('messages.consultation_charge') . ':', ['class' => 'form-label']) }}
                    
                    {{ Form::select('charge_id', $data['charges'], isset($data['last_visit']) ? $data['last_visit']->charge_id : null, ['class' => 'form-select', 'id' => 'opdChargeId', 'placeholder' => __('messages.select_consultation_charge'), 'data-control' => 'select2']) }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-5">
                <div class="mb-5">
                    <div class="form-group">
                        {{ Form::label('standard_charge', __('messages.doctor_opd_charge.standard_charge') . ':', ['class' => 'form-label']) }}
                        
                        <div class="input-group">
                            {{ Form::text('standard_charge', null , ['class' => 'form-control price-input', 'id' => 'opdStandardCharge','placeholder'=>__('messages.doctor_opd_charge.standard_charge')]) }}
                            <div class="input-group-text border-0"><a><span>{{ getCurrencySymbol() }}</span></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        --}}
        
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
    {{-- <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('payment_mode', __('messages.ipd_payments.payment_mode') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('payment_mode', $data['paymentMode'], null, ['class' => 'form-select', 'required', 'id' => 'opdPaymentMode', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') .' '. __('messages.payment.payment')]) }}
            </div>
        </div>
    </div> --}}
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
    {{-- <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('paid_amount',__('messages.paid_amount').':', ['class' => 'form-label']) }}
                {{ Form::text('paid_amount', 0, ['placeholder' => __('messages.paid_amount'),'class' => getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control', 'id' => 'opdPaidAmount', 'autocomplete' => 'off', 'required']) }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('change',__('messages.change').':', ['class' => 'form-label']) }} <br>
                <span class="form-control" id="changeText">0</span>
                {{ Form::hidden('change', 0, ['placeholder' => __('messages.change'),'class' => getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control', 'id' => 'opdChange', 'autocomplete' => 'off', 'required']) }}
            </div>
        </div>
    </div> --}}
</div>
<div class="d-flex justify-content-end">
    {!! Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-3', 'id' => 'btnOpdSave']) !!}
    <a href="{!! route('opd.patient.index') !!}" class="btn btn-secondary">{!! __('messages.common.cancel') !!}</a>
</div>
<script>
    // Function to handle the display of antenatal toggle based on gender
    function handlePatientSelection() {
        var patientSelect = document.getElementById('patientSelect');
        var selectedOption = patientSelect.options[patientSelect.selectedIndex];

        if (selectedOption) {
            var patientId = selectedOption.value;
            var gender = selectedOption.getAttribute('data-gender');

            // Update the hidden patient_id input
            document.getElementById('hiddenPatientId').value = patientId;

            // Show or hide the antenatal section based on gender
            var antenatalSection = document.getElementById('antenatalSection');
            if(antenatalSection){
                if (gender === '1') {
                    antenatalSection.style.display = 'block'; // Show antenatal section for female
                } else {
                    antenatalSection.style.display = 'none'; // Hide antenatal section for male
                }
                
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var patientSelect = document.getElementById('patientSelect');

        // Attach event listener for manual selection
        patientSelect.addEventListener('change', handlePatientSelection);

        // Watch for autofill scenarios
        const observer = new MutationObserver(() => {
            handlePatientSelection();
        });

        // Observe changes in the `patientSelect` dropdown
        observer.observe(patientSelect, {
            childList: true,
            attributes: true,
            subtree: false,
        });

        // Run the logic on page load (in case a patient is pre-selected)
        handlePatientSelection();
    });
</script>
<script>
$(document).ready(function () {
    // Live search for patients
    $('#patient_search').on('input', function () {
        let query = $(this).val();
        if (query.length > 1) {
            $.ajax({
                url: "{{ route('patients.d.search') }}", // Define this route
                type: "POST",
                data: { query: query, _token: '{{ csrf_token() }}' },
                success: function (data) {
                    console.log(data);
                    let resultsBox = $('#patient_results');
                    resultsBox.empty();
                    if (Object.keys(data).length) {
                        resultsBox.empty(); // It's a good practice to clear previous results
                    
                        for (const patientId in data) {
                            if (data.hasOwnProperty(patientId)) {
                                const patient = data[patientId];
                                
                                // The 'name' property contains the full name and phone number combined
                                const fullName = patient.name.split('(')[0].trim();
                                const phoneMatch = patient.name.match(/\((.*?)\)/);
                                const phoneNumber = phoneMatch ? phoneMatch[1] : '';
                    
                                // Extracting first and last name might be difficult if not stored separately in the 'name' field.
                                // Let's assume the 'name' field is just "First Last".
                                // If you need more granular data, you should adjust your PHP code.
                                
                                resultsBox.append(`
                                    <a href="#" class="list-group-item list-group-item-action" data-id="${patientId}" data-name="${patient.first_name} ${patient.last_name}">
                                        ${patient.first_name} ${patient.last_name} (${patient.phone}) (Gender: ${patient.gender == '0' ? 'Male' : 'Female'})
                                    </a>
                                `);
                            }
                        }
                        resultsBox.show();
                    } else {
                        resultsBox.hide();
                    }
                    // if (data.length) {
                    //     data.forEach(item => {
                    //         resultsBox.append(`
                    //             <a href="#" class="list-group-item list-group-item-action" data-id="${item.id}" data-name="${item.first_name} ${item.last_name} (${item.phone ? item.phone : ''})">
                    //                 ${item.first_name} ${item.last_name} (${item.phone ? item.phone : ''}) (Gender: ${item.gender == '0' ? 'Male' : 'Female'})
                    //             </a>
                    //         `);
                    //     });
                    //     resultsBox.show();
                    // } else {
                    //     resultsBox.hide();
                    // }
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
                url: '{{ route('get.doctor.charges') }}',
                type: 'GET',
                data: { doctor_id: doctorId },
                success: function(response) {
                    // console.log(response);
                    // Populate charges dropdown
                    // $('#opdChargeId').html('<option value="">Select Consultation Charge</option>')
                    //     .prop('disabled', false);
                    
                    // if (response.charges && Object.keys(response.charges).length > 0) {
                    //     $.each(response.charges, function(key, value) {
                    //         $('#opdChargeId').append($('<option></option>').attr('value', key).text(value));
                    //     });
                    // } else {
                    //     $('#opdChargeId').append('<option value="">No charges available</option>');
                    // }
                    $('#maternityChargeId').html(`<option value="">${doctorName} </option>`);
                    
                    // Set standard charge or default to 0
                    $('#maternityStandardCharge').val(response.standard_charge || '0.00');
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
        }
    });

    // Update standard charge when charge is selected
    $('#maternityChargeId').on('change', function() {
        const chargeId = $(this).val();
        if (chargeId) {
            $.get('{{ route('get.charge.amount') }}', { charge_id: chargeId })
             .then(response => {
                 $('#maternityStandardCharge').val(response.amount || '0.00');
             })
             .catch(() => $('#maternityStandardCharge').val('0.00'));
        }
    });
});
</script>
