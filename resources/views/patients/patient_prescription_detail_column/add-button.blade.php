@aware(['component'])
 
@php
    $theme = $component->getTheme();
@endphp
 



@php
    $configurableAreas = $this->getConfigurableAreas();
    $pId = null;
    if (isset($configurableAreas['toolbar-right-end'])) {
        
        foreach ($configurableAreas['toolbar-right-end'] as $configurableArea) {
            
            if (is_array($configurableArea)) {
                if (isset($configurableArea['patientId'])) {
                    $pId = $configurableArea['patientId'];
                }
            }
        }
    }
    
@endphp

@if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Patient') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse'))
    <a data-turbo="false" href="{{ route('prescription.excel') }}"
       class="btn btn-primary" style="margin-right: 10px">{{ __('messages.common.export_to_excel') }}</a>
@endif

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $pId != null)
    <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPrescriptionModal">
        {{__('messages.prescription.new_prescription')}}
    </a>
    
    <!-- Modal directly in the same file as the button -->
    <div id="createPrescriptionModal" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">{{ __('messages.prescription.new_prescription') }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body p-2">
                    <div class="alert alert-danger d-none hide" id="prescriptionErrorsBox"></div>
                    <div id="prescriptionFormContent" class="m-0">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer removed as per user request -->
            </div>
        </div>
    </div>
    
    <!-- Medicine Modal - Directly included in our file -->
    <div id="add_new_medicine_patient" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">{{ __('messages.prescription.new_medicine') }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                {{ Form::open(['id' => 'createMedicineFromPrescriptionPatient', 'method' => 'POST', 'url' => route('prescription.medicine.store')]) }}
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="medicinePrescriptionErrorBox"></div>
                    <div class="row">
                        <!-- Name Field -->
                        <div class="form-group col-md-6 mb-5">
                            {{ Form::label('name', __('messages.medicine.medicine').(':'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::text('name', null, ['class' => 'form-control','minlength' => 2, 'id' => 'medicineNameId','placeholder'=>__('messages.medicine.medicine')]) }}
                        </div>

                        <!-- Category Field -->
                        <div class="form-group col-md-6 mb-5">
                            {{ Form::label('category_id', __('messages.medicine.category').(':'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            <select name="category_id" id="prescriptionMedicineCategoryId" class="form-select">
                                <option value="">{{ __('messages.medicine.select_category') }}</option>
                                <!-- Options will be loaded via AJAX -->
                            </select>
                        </div>

                        <!-- Brand Field -->
                        <div class="form-group col-md-6 mb-5">
                            {{ Form::label('brand_id', __('messages.medicine.brand').(':'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            <select name="brand_id" id="prescriptionMedicineBrandId" class="form-select">
                                <option value="">Select Brand</option>
                                <!-- Options will be loaded via AJAX -->
                            </select>
                        </div>

                        <!-- Salt Composition Field -->
                        <div class="form-group col-md-6 mb-5">
                            {{ Form::label('salt_composition', __('messages.medicine.salt_composition').(':'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::text('salt_composition', null, ['class' => 'form-control','required','placeholder'=>__('messages.medicine.salt_composition')]) }}
                        </div>

                        <!-- Buying Price Field -->
                        <div class="form-group col-md-6 mb-5">
                            {{ Form::label('buying_price', __('messages.medicine.buying_price').(':'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::text('buying_price', null, ['class' => 'form-control price-input','placeholder'=>__('messages.medicine.buying_price')]) }}
                        </div>

                        <!-- Selling Price Field -->
                        <div class="form-group col-md-6 mb-5">
                            {{ Form::label('selling_price', __('messages.medicine.selling_price').(':'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::text('selling_price', null, ['class' => 'form-control price-input','placeholder'=>__('messages.medicine.selling_price')]) }}
                        </div>
                        
                        <!-- Quantity Field removed as requested -->
                        
                        <!-- Available Quantity Field (Hidden) -->
                        {{ Form::hidden('available_quantity', 0, ['id' => 'availableQuantityId']) }}
                        
                        <!-- Store Quantity Field (Hidden) -->
                        {{ Form::hidden('store_quantity', 0, ['id' => 'storeQuantityId']) }}

                        <!-- Effect Field -->
                        <div class="form-group col-md-12 mb-5">
                            {{ Form::label('side_effects', __('messages.medicine.side_effects').(':'), ['class' => 'form-label']) }}
                            {{ Form::textarea('side_effects', null, ['class' => 'form-control', 'rows'=>4,'placeholder'=>__('messages.medicine.side_effects')]) }}
                        </div>

                        <!-- Effect Field -->
                        <div class="form-group col-md-12 mb-5">
                            {{ Form::label('description', __('messages.medicine.description').(':'), ['class' => 'form-label']) }}
                            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows'=>4,'placeholder'=>__('messages.medicine.description')]) }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    {{ Form::button(__('messages.common.save'), ['type' => 'submit','class' => 'btn btn-primary m-0','id' => 'prescriptionMedicineSave','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    
    <!-- Include prescription medicine template -->
    @include('prescriptions.templates.templates')
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsrender/1.0.11/jsrender.min.js"></script>
    <script>
    // Function to prepare template rendering
    function prepareTemplateRender(templateSelector, data) {
        let template = $.templates(templateSelector);
        return template.render(data);
    }
    
    $(document).ready(function() {
        // Load prescription form when modal opens
        $('#createPrescriptionModal').on('show.bs.modal', function () {
            // Modal opening
            // Get the patient ID from the URL or data attribute
            const patientId = {{ $pId }};
            
            // Load the prescription form via AJAX
            $.ajax({
                url: '/prescriptions/create',
                type: 'GET',
                data: { ref_p_id: patientId, modal: true },
                success: function(response) {
                    // Process the response to extract just what we need
                    const formContent = $(response).find('#createPrescription').html();
                    const hiddenInputs = $(response).find('input[type="hidden"]').not('#createPrescription input[type="hidden"]');
                    
                    // Build our form content
                    let modalContent = '<div class="container-fluid">';
                    modalContent += '<div class="d-flex flex-column">';
                    
                    // Add hidden inputs
                    hiddenInputs.each(function() {
                        modalContent += $(this).prop('outerHTML');
                    });
                    
                    // Start the form
                    modalContent += '<form id="modalPrescriptionForm" method="POST" action="{{ route('prescriptions.store') }}">';
                    modalContent += '@csrf';
                    
                    // Add the main form content
                    modalContent += formContent;
                    
                    // Close the form and containers
                    modalContent += '</form></div></div>';
                    
                    // Insert into modal
                    $('#prescriptionFormContent').html(modalContent);
                    
                    // Initialize any select2 dropdowns
                    $('#prescriptionFormContent select').select2({
                        width: '100%',
                        dropdownParent: $('#createPrescriptionModal')
                    });
                    
                    // Initialize date pickers
                    if ($('#prescriptionDate').length) {
                        $('#prescriptionDate').flatpickr({
                            format: 'YYYY-MM-DD',
                            useCurrent: true,
                            sideBySide: true,
                            locale: $('.userCurrentLanguage').val(),
                        });
                    }
                    
                    // Handle the add_new_medicine modal button inside our modal
                    $('#prescriptionFormContent').on('click', '[data-bs-target="#add_new_medicine"]', function(e) {
                        // Update the target to our patient-specific medicine modal
                        $(this).attr('data-bs-target', '#add_new_medicine_patient');
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Medicine button clicked
                        
                        // Load medicine categories and brands via AJAX
                        $.ajax({
                            url: '/medicines/get-medicine-details',
                            type: 'GET',
                            success: function(result) {
                                // Medicine details loaded
                                
                                if (result.success) {
                                    // Populate the dropdowns
                                    let categoryOptions = '<option value="">Select Category</option>';
                                    $.each(result.data.categories, function(key, value) {
                                        categoryOptions += '<option value="' + key + '">' + value + '</option>';
                                    });
                                    $('#prescriptionMedicineCategoryId').html(categoryOptions);
                                    
                                    let brandOptions = '<option value="">Select Brand</option>';
                                    $.each(result.data.brands, function(key, value) {
                                        brandOptions += '<option value="' + key + '">' + value + '</option>';
                                    });
                                    $('#prescriptionMedicineBrandId').html(brandOptions);
                                    
                                    // Form action is already set in the Form::open declaration
                                    // No need to set it again here
                                    
                                    // Initialize select2
                                    $('#prescriptionMedicineCategoryId, #prescriptionMedicineBrandId').select2({
                                        width: '100%',
                                        dropdownParent: $('#add_new_medicine_patient')
                                    });
                                    
                                    // Show the medicine modal
                                    $('#add_new_medicine_patient').modal('show');
                                } else {
                                    // Failed to load medicine details
                                }
                            },
                            error: function(xhr) {
                                console.error('Error loading medicine details:', xhr);
                            }
                        });
                    });
                    
                    // When medicine modal is hidden, reopen prescription modal
                    $('#add_new_medicine_patient').on('hidden.bs.modal', function() {
                        // Medicine modal closed
                        setTimeout(function() {
                            $('#createPrescriptionModal').modal('show');
                        }, 500); // Small delay to prevent modal conflicts
                    });
                    
                    // Handle medicine form submission
                    $('#createMedicineFromPrescriptionPatient').on('submit', function(e) {
                        e.preventDefault();
                        // Medicine form submitted
                        
                        // Show loading indicator
                        $('#prescriptionMedicineSave').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');
                        
                        // Add quantity fields with default value 0
                        const formData = $(this).serialize() + '&quantity=0&available_quantity=0';
                        
                        $.ajax({
                            url: $(this).attr('action'),
                            type: 'POST',
                            data: formData,
                            success: function(result) {
                                $('#prescriptionMedicineSave').prop('disabled', false).html('{{ __('messages.common.save') }}');
                                
                                if (result.success) {
                                    // Reset the form
                                    $('#createMedicineFromPrescriptionPatient')[0].reset();
                                    
                                    // Close medicine modal
                                    $('#add_new_medicine_patient').modal('hide');
                                    
                                    // Show success message
                                    displaySuccessMessage(result.message || 'Medicine created successfully');
                                    
                                    // Refresh the medicine dropdown in the prescription form
                                    if ($('#medicineId').length) {
                                        // Get the new medicine ID
                                        let newMedicineId = result.data.id;
                                        let newMedicineName = result.data.name;
                                        
                                        // Add the new medicine to the dropdown
                                        let newOption = new Option(newMedicineName, newMedicineId, true, true);
                                        $('#medicineId').append(newOption).trigger('change');
                                    }
                                    
                                    // Prevent any additional requests to the current page
                                    e.stopPropagation();
                                    
                                    // Reopen prescription modal with a delay
                                    setTimeout(function() {
                                        $('#createPrescriptionModal').modal('show');
                                    }, 500);
                                } else {
                                    $('#medicinePrescriptionErrorBox').removeClass('d-none').html(result.message || 'Error creating medicine');
                                }
                            },
                            error: function(xhr, status, error) {
                                // Reset button state
                                $('#prescriptionMedicineSave').prop('disabled', false).html('{{ __('messages.common.save') }}');
                                
                                // Show error message
                                $('#medicinePrescriptionErrorBox').removeClass('d-none').html('Error creating medicine: ' + (xhr.responseJSON?.message || 'Unknown error'));
                                
                                // Prevent any additional requests
                                e.stopPropagation();
                                
                                // Error handled
                            }
                        });
                    });
                },
                error: function() {
                    $('#prescriptionFormContent').html('<div class="alert alert-danger">Error loading prescription form</div>');
                }
            });
        });
        
        // Handle save button click
        $(document).on('click', '#savePrescriptionBtn', function() {
            $('#modalPrescriptionForm').submit();
        });
        
        // Reset modal content when closed
        $('#createPrescriptionModal').on('hidden.bs.modal', function () {
            $('#prescriptionFormContent').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        });
    });
    </script>
@endif