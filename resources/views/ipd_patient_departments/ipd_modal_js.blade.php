<script>
    $(document).ready(function () {
        // Initialize select2 elements in the modal with proper dropdown parent
        $('#createIpdPatientModal').on('shown.bs.modal', function() {
            // Initialize Select2 for elements with data-control="select2"
            $('#createIpdPatientModal select[data-control="select2"]').each(function() {
                // Destroy any existing select2 instances first
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
                
                $(this).select2({
                    dropdownParent: $('#createIpdPatientModal'),
                    width: '100%'
                });
            });
        });
        
        // Patient search handling
        $('#patient_search').on('input', function() {
            var query = $(this).val();
            if (query.length > 1) {
                $.ajax({
                    url: '{{ route("ipd.patient.search") }}',
                    type: 'GET',
                    data: { query: query },
                    success: function(data) {
                        var resultsBox = $('#patient_results');
                        resultsBox.empty();
                        
                        if (Object.keys(data).length) {
                            resultsBox.empty(); // Clear previous results
                            
                            for (const patientId in data) {
                                if (data.hasOwnProperty(patientId)) {
                                    const patient = data[patientId];
                                    const gender = patient.gender == 0 ? 'Male' : 'Female';
                                    
                                    resultsBox.append(`
                                        <a href="javascript:void(0)" class="list-group-item list-group-item-action patient-result-item" 
                                           data-id="${patient.id}" 
                                           data-name="${patient.first_name} ${patient.last_name}">
                                            ${patient.first_name} ${patient.last_name}
                                            <span class="text-muted">(${patient.phone || ''}) (Gender: ${gender})</span>
                                        </a>
                                    `);
                                }
                            }
                            resultsBox.show();
                        } else {
                            resultsBox.hide();
                        }
                    }
                });
            } else {
                $('#patient_results').hide();
            }
        });
        
        // Patient selection handling
        $(document).on('click', '.patient-result-item', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');
            
            // Set the selected patient
            $('#patient_search').val(name);
            $('#selected_patient_id').val(id);
            $('#patient_results').hide();
            
            // Automatically check the "is old patient" checkbox
            if (id) {
                $('#ipdFlexSwitchDefault').prop('checked', true);
            }
            
            // Get patient details for vitals
            if (id) {
                $.ajax({
                    url: '{{ route("ipd.patient.details") }}',
                    type: 'GET',
                    data: { patient_id: id },
                    success: function(response) {
                        if (response && response.data) {
                            // Fill in vitals if available
                            if (response.data.height) $('#height').val(response.data.height);
                            if (response.data.weight) $('#weight').val(response.data.weight);
                            if (response.data.bp) $('#bp').val(response.data.bp);
                            if (response.data.temperature) $('#temperature').val(response.data.temperature);
                            if (response.data.respiration) $('#respiration').val(response.data.respiration);
                        }
                    }
                });
            }
        });

        // Hide results on outside click
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#patient_search, #patient_results').length) {
                $('#patient_results').hide();
            }
        });
        
        // Doctor charge handling
        $('#ipdDoctorId').on('change', function () {
            const doctorId = $(this).val();
            const doctorName = $(this).find('option:selected').text();
            
            if (doctorId !== '') {
                // Set loading states
                $('#ipdChargeId').html('<option value="">Loading...</option>').prop('disabled', true);
                $('#ipdStandardCharge').val('Loading...');
                
                $.ajax({
                    url: '{{ route("get.doctor.charges") }}',
                    type: 'GET',
                    data: { doctor_id: doctorId },
                    success: function (response) {
                        // Set doctor name as the charge option and disable the field
                        $('#ipdChargeId').html(`<option value="1">${doctorName}</option>`).prop('disabled', true);
                        
                        // Set standard charge or default to 0
                        $('#ipdStandardCharge').val(response.standard_charge || '0.00');
                    },
                    error: function () {
                        // Reset on error
                        $('#ipdChargeId').html('<option value="">No charges found</option>');
                        $('#ipdStandardCharge').val('0.00');
                    }
                });
            } else {
                // Reset if no doctor selected
                $('#ipdChargeId').html('<option value="">Select Charge</option>');
                $('#ipdStandardCharge').val('');
            }
        });
        
        // Bed type change handling
        $('#ipdBedTypeId').on('change', function () {
            const bedTypeId = $(this).val();
            const patientBedsUrl = $('#createPatientBedsUrl').val();
            
            // Reset and disable bed dropdown
            $('#ipdBedId').html('<option value="">{{ __("messages.common.choose") . " " . __("messages.bed_assign.bed") }}</option>').prop('disabled', true);
            
            if (bedTypeId) {
                $.ajax({
                    url: patientBedsUrl,
                    type: 'GET',
                    data: {
                        id: bedTypeId,
                        isEdit: false,
                        bedId: '',
                        ipdPatientBedTypeId: ''
                    },
                    success: function (response) {
                        // Enable bed dropdown
                        $('#ipdBedId').prop('disabled', false);
                        
                        // Populate bed options
                        $.each(response.data, function (id, name) {
                            $('#ipdBedId').append($('<option>', {
                                value: id,
                                text: name
                            }));
                        });
                    },
                    error: function() {
                        $('#ipdBedId').prop('disabled', true);
                    }
                });
            }
        });
        
        // Initialize admission date picker
        $('#createIpdPatientModal').on('shown.bs.modal', function() {
            setTimeout(function() {
                if ($('#ipdAdmissionDate').length && typeof flatpickr !== 'undefined') {
                    // Get current date and time
                    const now = new Date();
                    
                    // Initialize flatpickr for admission date
                    flatpickr('#ipdAdmissionDate', {
                        enableTime: true,
                        dateFormat: 'Y-m-d h:i K', // 12-hour format with AM/PM
                        defaultDate: now,
                        minDate: 'today',
                        time_24hr: false, // Set to false for 12-hour format with AM/PM
                        disableMobile: true,
                        allowInput: true,
                        clickOpens: true,
                        minuteIncrement: 5
                    });
                    
                    // Make sure the field is not readonly (which flatpickr sets by default)
                    $('#ipdAdmissionDate').prop('readonly', false);
                }
            }, 500);
        });
        
        // Reset form and reinitialize components when modal is opened
        $('#createIpdPatientModal').on('show.bs.modal', function() {
            // Reset form fields if needed
            $('#createIpdPatientForm').trigger('reset');
            
            // Disable charge field by default
            $('#ipdChargeId').prop('disabled', true);
        });
        
        // Modal save button handler
        $('#ipdSave').on('click', function() {
            $('#createIpdPatientForm').submit();
        });
        
        // Handle modal close to clean up any Select2 instances
        $('#createIpdPatientModal').on('hidden.bs.modal', function() {
            // Clean up any Select2 instances to prevent memory leaks
            $('#createIpdPatientModal select').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
            });
        });
        
        // Form submission handling
        $('#createIpdPatientForm').on('submit', function(e) {
            e.preventDefault();
            
            // Disable save button to prevent multiple submissions
            $('#ipdSave').attr('disabled', true).html("<span class='spinner-border spinner-border-sm'></span> Processing...");
            
            // Clear previous errors
            $('#ipdValidationErrorsBox').addClass('d-none').html('');
            
            $.ajax({
                url: '{{ route("ipd.patient.store") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        // Show success message
                        displaySuccessMessage(result.message);
                        
                        // Close modal
                        $('#createIpdPatientModal').modal('hide');
                        
                        // Refresh the Livewire table without page reload
                        if (typeof window.livewire !== 'undefined') {
                            window.livewire.emit('refresh');
                        }
                    }
                },
                error: function(result) {
                    // Re-enable save button
                    $('#ipdSave').attr('disabled', false).html('{{ __("messages.common.save") }}');
                    
                    // Display validation errors
                    if (result.responseJSON.errors) {
                        $('#ipdValidationErrorsBox').removeClass('d-none').html('');
                        $.each(result.responseJSON.errors, function(key, value) {
                            $('#ipdValidationErrorsBox').append('<div class="alert alert-danger">' + value + '</div>');
                        });
                    }
                },
                complete: function() {
                    // Re-enable save button
                    $('#ipdSave').attr('disabled', false).html('{{ __("messages.common.save") }}');
                }
            });
        });
    });
</script>
