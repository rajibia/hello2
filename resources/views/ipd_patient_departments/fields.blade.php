<div class="row gx-10 mb-5">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('patient_id', __('messages.ipd_patient.patient_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{-- {{ Form::select('patient_id', $data['patients'], $data['patient_id'] ?? null, ['class' => 'form-select ipdPatientId', 'required',  ($data['patient_id'] != '' ? 'disabled' : ''), 'id' => 'ipdPatientId', 'placeholder' => __('messages.document.select_patient'), 'tabindex' => '1', 'data-control' => 'select2']) }} --}}
                <div class="form-group mb-3 position-relative">
                    
                    <input type="text" value="{{$data['patient_name'] ?? ''}}" id="patient_search" class="form-control" placeholder="Search and select patient " autocomplete="off">
                    <div id="patient_results" class="list-group ipdPatientId position-absolute w-100" style="z-index: 1000;"></div>
                    <input type="hidden" name="patient_id" id="selected_patient_id" value="{{$data['patient_id'] ?? ''}}">
                </div>
            {{--
            <select id="ipdPatientId" class="form-control ipdPatientId" name="patient_id" required>
                <option value="">Select Patient</option>
                @foreach ($data['patients2'] as $id => $patient)
                    <option value="{{ $id }}" data-gender="{{ $patient['gender'] }}">
                        {{ $patient['name'] }} (Gender: {{ $patient['gender'] == '0' ? 'Male' : 'Female' }})
                    </option>
                @endforeach
            </select>
            --}}
            </div>
        </div>
        {{-- <input type="hidden" class="ipdPatientIdHidden" name="patient_id" value="{{ $data['patient_id'] }}"> --}}
    </div>
    <!-- Gender Display -->
        {{-- <div id="genderDisplay" class="mt-3"></div> --}}
    <div class="col-lg-3 col-md-6 col-sm-12" style="display:none">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('case_id', __('messages.ipd_patient.case_id') . ':', ['class' => 'form-label required']) }}
                {{ Form::select('case_id', [null], null, ['class' => 'form-select ipdDepartmentCaseId', 'id' => 'ipdDepartmentCaseId', 'disabled', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') . __('messages.cases')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('ipd_number', __('messages.ipd_patient.ipd_number') . ':', ['class' => 'form-label']) }}
                {{ Form::text('ipd_number', $data['ipdNumber'], ['class' => 'form-control', 'readonly', 'placeholder' => __('messages.ipd_patient.ipd_number')]) }}
            </div>
        </div>
    </div>
            {{-- <!-- Antenatal Section (Initially hidden) -->
        <div class="col-md-3" id="antenatalSection" style="display: none;">
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
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('height', __('messages.ipd_patient.height') . ':', ['class' => 'form-label']) }}
                {{ Form::number('height', 0, ['class' => 'form-control ipdDepartmentFloatNumber', 'max' => '100', 'step' => '.01', 'tabindex' => '2', 'placeholder' => __('messages.ipd_patient.height')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('weight', __('messages.ipd_patient.weight') . ':', ['class' => 'form-label']) }}
                {{ Form::number('weight', 0, ['placeholder' => __('messages.ipd_patient.weight'), 'class' => 'form-control ipdDepartmentFloatNumber', 'data-mask' => '##0,00', 'max' => '200', 'step' => '.01', 'tabindex' => '3']) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('bp', __('messages.ipd_patient.bp') . ':', ['class' => 'form-label']) }}
                {{ Form::text('bp', null, ['class' => 'form-control', 'tabindex' => '4', 'placeholder' => __('messages.ipd_patient.bp')]) }}
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('pulse', __('messages.ipd_patient_diagnosis.pulse') . ':', ['class' => 'form-label']) }}
                {{ Form::text('pulse', null, ['class' => 'form-control', 'placeholder' => __('messages.ipd_patient_diagnosis.pulse')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('respiration', __('messages.ipd_patient_diagnosis.respiration') . ':', ['class' => 'form-label']) }}
                {{ Form::text('respiration', null, ['class' => 'form-control', 'placeholder' => __('messages.ipd_patient_diagnosis.respiration')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('temperature', __('messages.ipd_patient_diagnosis.temperature') . ':', ['class' => 'form-label']) }}
                {{ Form::text('temperature', null, ['class' => 'form-control', 'placeholder' => __('messages.ipd_patient_diagnosis.temperature')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('oxygen_saturation', __('messages.ipd_patient_diagnosis.oxygen_saturation') . ':', ['class' => 'form-label']) }}
                {{ Form::text('oxygen_saturation', null, ['class' => 'form-control', 'placeholder' => __('messages.ipd_patient_diagnosis.oxygen_saturation')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('admission_date', __('messages.ipd_patient.admission_date') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::text('admission_date', null, ['placeholder' => __('messages.ipd_patient.admission_date'), 'class' => getLoggedInUser()->thememode ? 'bg-light ipdAdmissionDate form-control' : 'bg-white ipdAdmissionDate form-control', 'id' => 'ipdAdmissionDate', 'autocomplete' => 'off', 'required', 'tabindex' => '5']) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('doctor_id', __('messages.ipd_patient.doctor_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('doctor_id', $data['doctors'], null, ['class' => 'form-select', '', 'id' => 'ipdDoctorId', 'placeholder' => __('messages.web_home.select_doctor'), 'data-control' => 'select2', 'tabindex' => '6']) }}
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('bed_type_id', __('messages.ipd_patient.bed_type_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('bed_type_id', $data['bedTypes'], null, ['class' => 'form-select ipdBedTypeId', 'required', 'id' => 'ipdBedTypeId', 'placeholder' => __('messages.bed.select_bed_type'), 'data-control' => 'select2', 'tabindex' => '7']) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('bed_id', __('messages.ipd_patient.bed_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('bed_id', [null], null, ['class' => 'form-select bedId', 'required', 'id' => 'ipdBedId', 'disabled', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.bed_assign.bed')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('is_old_patient', __('messages.ipd_patient.is_old_patient') . ':', ['class' => 'form-label']) }}
                <div class="form-check form-switch">
                    <input class="form-check-input w-35px h-20px" name="is_old_patient" type="checkbox" value="1"
                        id="ipdFlexSwitchDefault">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('symptoms', __('messages.ipd_patient.symptoms') . ':', ['class' => 'form-label']) }}
                {{ Form::textarea('symptoms', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => __('messages.ipd_patient.symptoms')]) }}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('notes', __('messages.ipd_patient.notes') . ':', ['class' => 'form-label']) }}
                {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => __('messages.ipd_patient.notes')]) }}
            </div>
        </div>
    </div>
</div>
@if(!isset($data['modal_form']) || !$data['modal_form'])
<div class="d-flex justify-content-end">
    {!! Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2', 'id' => 'ipdSave']) !!}
    <a href="{!! route('ipd.patient.index') !!}" class="btn btn-secondary">{!! __('messages.common.cancel') !!}</a>
</div>
@endif
<script>
  document.getElementById('patientSelect').addEventListener('change', function () {
    var selectedOption = this.options[this.selectedIndex];
    var patientId = selectedOption.value;
    var gender = selectedOption.getAttribute('data-gender');
    
    // Update the hidden patient_id input
    var hiddenPatientId = document.querySelector('.ipdPatientIdHidden');
    if (hiddenPatientId) {
        hiddenPatientId.value = patientId;
    }
    
    // // Display gender information
    // var genderDisplay = document.getElementById('genderDisplay');
    // genderDisplay.innerHTML = "Selected patient's gender: " + (gender === '0' ? 'Female' : 'Male');
    
    // Show or hide the antenatal section based on gender
    var antenatalSection = document.getElementById('antenatalSection');
    if (gender === '0') {
        antenatalSection.style.display = 'block';
    } else {
        antenatalSection.style.display = 'none';  
    }
});

    
</script>
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
     // <a href="#" class="list-group-item list-group-item-action" data-id="${item.id}" data-name="${item.last_name}">
                                //     ${item.last_name} (Gender: ${item.gender == '0' ? 'Male' : 'Female'})
                                // </a>
    // When a result is selected
    $(document).on('click', '#patient_results a', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let name = $(this).data('name');
        $('#patient_search').val(name);
        $('#selected_patient_id').val(id);
        $('#patient_results').hide();
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
    document.addEventListener('DOMContentLoaded', function () {
        const bedTypeIdSelect = document.getElementById('ipdBedTypeId');
        const bedIdSelect = document.getElementById('ipdBedId');

        // Listen for change on the bed type dropdown
        bedTypeIdSelect.addEventListener('change', function () {
            const bedTypeId = this.value;

            // Clear and disable the bed dropdown
            bedIdSelect.innerHTML = '<option value="">{{ __('messages.common.choose') . __('messages.bed_assign.bed') }}</option>';
            bedIdSelect.disabled = true;

            if (bedTypeId) {
                // Make an AJAX request to fetch unassigned beds
                fetch(`/beds/unassigned/${bedTypeId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate the bed dropdown with the fetched data
                        for (const id in data) {
                            if (Object.hasOwnProperty.call(data, id)) {
                                const option = document.createElement('option');
                                option.value = id;
                                option.textContent = data[id];
                                bedIdSelect.appendChild(option);
                            }
                        }
                        // Enable the bed dropdown
                        bedIdSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching beds:', error);
                    });
            }
        });
    });
</script>

