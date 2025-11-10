<script>
    // Format functions for Select2 dropdowns
    function formatSelect2Result(state) {
        if (!state.id) {
            return state.text;
        }
        
        return $('<span style="padding: 5px;">' + state.text + '</span>');
    }

    function formatSelect2Selection(state) {
        if (!state.id) {
            return state.text;
        }
        
        return state.text;
    }

    // Use an immediately invoked function expression (IIFE) to avoid polluting the global namespace
(function() {
    // Keep track of whether we've initialized the handlers
    let handlersInitialized = false;
    
    // Function to initialize all event handlers
    function initializeHandlers() {
        // Only initialize once
        if (handlersInitialized) {
            return;
        }
        
        // Set flag to true
        handlersInitialized = true;
        // Initialize select2 elements in the modal with proper dropdown parent
        $('#createMaternityModal').on('shown.bs.modal', function() {
            // Initialize Select2 for elements with data-control="select2"
            $('#createMaternityModal select[data-control="select2"]').each(function() {
                // Destroy any existing select2 instances first
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
                
                $(this).select2({
                    dropdownParent: $('#createMaternityModal'),
                    width: '100%'
                });
            });
        });
        
        // Initialize flatpickr for date inputs
        $('#createMaternityModal').on('shown.bs.modal', function() {
            // Initialize flatpickr for appointment date
            setTimeout(function() {
                if ($('#maternityAppointmentDate').length) {
                    // Destroy existing flatpickr instance if it exists
                    if ($('#maternityAppointmentDate')[0]._flatpickr) {
                        $('#maternityAppointmentDate')[0]._flatpickr.destroy();
                    }
                    
                    // Get current date and time
                    const now = new Date();
                    
                    $('#maternityAppointmentDate').flatpickr({
                        enableTime: true,
                        dateFormat: 'Y-m-d h:i K', // h:i K format for 12-hour time with AM/PM
                        // Set default to current date and time
                        defaultDate: now,
                        defaultHour: now.getHours(),
                        defaultMinute: now.getMinutes(),
                        minDate: 'today',
                        time_24hr: false, // Set to false for 12-hour format with AM/PM
                        disableMobile: true,
                        allowInput: true,
                        clickOpens: true,
                        minuteIncrement: 5
                    });
                    
                    // Make sure the field is not readonly (which flatpickr sets by default)
                    $('#maternityAppointmentDate').prop('readonly', false);
                }
            }, 500);
        });
        
        // Patient search handling
        $('#patient_search').on('input', function() {
            var query = $(this).val();
            if (query.length > 1) {
                $.ajax({
                    url: '{{ route("patients.d.search") }}', // Using the same patient search route as OPD
                    type: 'POST',
                    data: { query: query, _token: '{{ csrf_token() }}' },
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
        
        // When a result is selected
        $(document).on('click', '#patient_results a', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let name = $(this).data('name');
            $('#patient_search').val(name);
            $('#selected_patient_id').val(id);
            $('#patient_results').hide();
            
            // Automatically check the "is old patient" checkbox
            if (id) {
                $('#is_old_patient').prop('checked', true);
            }
        });
        
        // Hide results on outside click
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#patient_search, #patient_results').length) {
                $('#patient_results').hide();
            }
        });

        // When a patient result is selected
        $(document).on('click', '.patient-result-item', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');
            
            // Set the patient name and ID
            $('#patient_search').val(name);
            $('#selected_patient_id').val(id);
            $('#patient_results').hide();
            
            // Check the "is old patient" checkbox
            $('#is_old_patient').prop('checked', true);
            
            // Get patient cases if available
            if (id) {
                $.ajax({
                    url: $('#createMaternityPatientCasesUrl').val(),
                    type: 'get',
                    dataType: 'json',
                    data: { id: id },
                    success: function(data) {
                        if (data.data && data.data.length !== 0) {
                            $('#CaseId').empty();
                            $('#CaseId').append($('<option></option>').attr('value', '').text('Select Case'));
                            $.each(data.data, function (i, v) {
                                $('#CaseId').append($('<option></option>').attr('value', v.id).text(v.case_id));
                            });
                            $('#CaseId').prop('disabled', false);
                        }
                    }
                });
                
                // For patient vitals, we'll get them from the patient's previous OPD records
                $.ajax({
                    url: '{{ route("maternity.patient.details") }}',
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
        $('#maternityDoctorId').on('change', function () {
            const doctorId = $(this).val();
            const doctorName = $(this).find('option:selected').text();
            
            if (doctorId !== '') {
                // Set loading states
                $('#maternityChargeId').html('<option value="">Loading...</option>').prop('disabled', true);
                $('#maternityStandardCharge').val('Loading...');
                
                $.ajax({
                    url: '{{ route("getDoctor.Maternitycharge") }}',
                    type: 'GET',
                    data: { doctor_id: doctorId },
                    success: function (response) {
                        // Set doctor name as the charge option and disable the field
                        $('#maternityChargeId').html(`<option value="1">${doctorName}</option>`).prop('disabled', true);
                        
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
                $('#maternityStandardCharge').val('0.00');
                $('#maternityChargeId').html('<option value="">Select Consultation Charge</option>');
                $('#maternityChargeId').prop('disabled', true);
            }
        });
        
        // Reset form and reinitialize components when modal is opened
        $('#createMaternityModal').on('show.bs.modal', function() {
            // Reset form fields if needed
            $('#createMaternityForm').trigger('reset');
            
            // Disable charge field by default
            $('#maternityChargeId').prop('disabled', true);
        });
        
        // Remove any existing click handlers from the save button
        $('#saveMaternityBtn').off('click');
        
        // Modal save button handler
        // Instead of submitting the form directly, we'll trigger our own custom event
        // This avoids duplicate submissions
        $('#saveMaternityBtn').on('click', function() {
            // Trigger our custom event instead of form submit
            $('#createMaternityForm').trigger('submitMaternityForm');
        });
        
        // Handle modal close to clean up any Select2 instances
        $('#createMaternityModal').on('hidden.bs.modal', function() {
            // Clean up any Select2 instances to prevent memory leaks
            $('#createMaternityModal select').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
            });
        });
        
        // Remove any existing form submission handlers
        $('#createMaternityForm').off('submitMaternityForm');
        
        // Form submission - using custom event to avoid duplicate submissions
        $('#createMaternityForm').on('submitMaternityForm', function (e) {
            e.preventDefault();
            
            if (!$('#selected_patient_id').val()) {
                displayErrorMessage('Please select a patient');
                return false;
            }
            
            if (!$('#maternityDoctorId').val()) {
                displayErrorMessage('Please select a doctor');
                return false;
            }
            
            $('#saveMaternityBtn').prop('disabled', true);
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function (result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#createMaternityModal').modal('hide');
                        
                        // Redirect to the maternity patient detail page
                        setTimeout(function () {
                            window.location.href = '/maternity/' + result.data.id;
                        }, 1000);
                    }
                },
                error: function (result) {
                    displayErrorMessage(result.responseJSON.message);
                    $('#saveMaternityBtn').prop('disabled', false);
                },
                complete: function () {
                    $('#saveMaternityBtn').prop('disabled', false);
                }
            });
        });
    }
    
    // Initialize handlers on document ready
    $(document).ready(function() {
        initializeHandlers();
    });
    
    // Also initialize handlers on turbo:load event (for Turbo/Hotwire navigation)
    $(document).on('turbo:load', function() {
        initializeHandlers();
    });
    
    // Also initialize handlers on livewire:load event (for Livewire navigation)
    $(document).on('livewire:load', function() {
        initializeHandlers();
    });
    
    // Also initialize on livewire:navigated (for Livewire page navigation)
    $(document).on('livewire:navigated', function() {
        initializeHandlers();
    });
})();
</script>
