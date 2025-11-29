<script>
    let invoiceItemTemplate = $.templates('#invoiceItemTemplate');
        let invoiceItemTemplate = $.templates('#invoiceItemTemplate');
        let uniqueId = 0;
        let charges = [];
    
    $(document).ready(function() {
        // Load charges data
        loadChargesData();
        
        // Initialize select2 for dropdowns
        $('#invoice_patient_id, #status').select2({
            dropdownParent: $('#createInvoiceModal')
        });
        
        // Handle modal show event
        $('#createInvoiceModal').on('show.bs.modal', function(e) {
            // Get the button that triggered the modal
            let button = $(e.relatedTarget);
            let patientId = button.data('patient-id');
            
            console.log('Modal opening with patient ID:', patientId);
            
            // Reset form
            $('#createInvoiceForm')[0].reset();
            
            // Set today's date directly in the input field
            $('#invoice_date').val(moment().format('YYYY-MM-DD'));
            
            // Clear any existing invoice items
            $('.invoice-item-container').empty();
            
            // Store the patient ID for use after modal is fully shown
            $(this).data('patientId', patientId);
        });
        
        // Handle modal after shown event
        $('#createInvoiceModal').on('shown.bs.modal', function(e) {
            // Get the patient ID stored in the modal data
            let patientId = $(this).data('patientId');
            
            // Add default invoice item row
            addInvoiceItemRow();
            
            // Initialize select2 for all charge dropdowns in the modal
            $('.chargeId').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
                
                $(this).select2({
                    dropdownParent: $('#createInvoiceModal'),
                    width: '100%'
                }).on('select2:open', function() {
                    setTimeout(function() {
                        $('.select2-search__field').focus();
                    }, 0);
                });
            });
            
            // Try different approaches to find the patient option
            // First try direct value match
            let patientOption = $('#invoice_patient_id option[value="' + patientId + '"]');
            
            // If not found, try with parseInt
            if (patientOption.length === 0) {
                patientOption = $('#invoice_patient_id option[value="' + parseInt(patientId) + '"]');
                console.log('Trying with parseInt:', parseInt(patientId));
            }
            
            // If still not found, try with toString
            if (patientOption.length === 0) {
                // Try to find by looping through options and comparing as strings
                $('#invoice_patient_id option').each(function() {
                    if (String($(this).val()) === String(patientId)) {
                        patientOption = $(this);
                        console.log('Found by string comparison');
                    }
                });
            }
            
            console.log('Found patient option:', patientOption.length > 0 ? 'yes' : 'no');
            
            if (patientOption.length > 0) {
                let patientName = patientOption.text();
                let patientValue = patientOption.val();
                console.log('Patient name:', patientName, 'value:', patientValue);
                
                // First destroy any existing select2
                if ($('#invoice_patient_id').data('select2')) {
                    $('#invoice_patient_id').select2('destroy');
                }
                
                // Remove all options except the selected one
                $('#invoice_patient_id option').not('[value="' + patientValue + '"]').remove();
                
                // Initialize select2 with disabled
                $('#invoice_patient_id').prop('disabled', true).select2({
                    dropdownParent: $('#createInvoiceModal'),
                    width: '100%'
                });
                
                // Add hidden input for form submission
                $('#hidden_patient_container').html('<input type="hidden" name="patient_id" value="' + patientValue + '">');
                
                console.log('Patient dropdown set to single option and disabled');
            } else {
                console.error('Could not find patient with ID:', patientId);
                // Initialize select2 normally
                $('#invoice_patient_id').select2({
                    dropdownParent: $('#createInvoiceModal'),
                    width: '100%'
                });
            }
            
            if (!patientId) {
                // Initialize select2 for patient dropdown (normal case)
                $('#invoice_patient_id').select2({
                    dropdownParent: $('#createInvoiceModal'),
                    width: '100%'
                });
            }
            
            // Calculate initial amounts
            calculateAndSetInvoiceAmount();
        });
        
        // Handle adding new invoice item
        $(document).on('click', '#modalAddInvoiceItem', function() {
            addInvoiceItemRow();
        });
        
        // Handle removing invoice item
        $(document).on('click', '.deleteInvoiceItem', function() {
            // Don't remove if it's the only row
            if ($('.invoice-item-container tr').length > 1) {
                $(this).closest('tr').remove();
                resetInvoiceItemNumbers();
                calculateAndSetInvoiceAmount();
            } else {
                // If it's the last row, just clear the values
                $(this).closest('tr').find('select').val('').trigger('change');
                $(this).closest('tr').find('input[type="text"]').val('');
                $(this).closest('tr').find('.qty').val(1);
                $(this).closest('tr').find('.price').val('0.00');
                $(this).closest('tr').find('.item-total').text('0.00');
                calculateAndSetInvoiceAmount();
            }
        });
        
        // Form is submitted through standard HTML form submission
        // The controller has been updated to redirect to the invoice detail page
        
        // Handle quantity and price changes
        $(document).on('keyup change', '.qty, .price', function() {
            calculateAndSetInvoiceAmount();
        });
        
        // Handle charge selection
        $(document).on('change', '.chargeId', function () {
            let chargeId = $(this).val();
            let priceInput = $(this).closest('tr').find('.price');
            
            if (chargeId !== '') {
                // Get charge price via AJAX
                $.ajax({
                    url: '{{ route("charge.standard.rate") }}',
                    type: 'GET',
                    data: {id: chargeId},
                    dataType: 'json',
                    success: function(result) {
                        if (result.success) {
                            let standardCharge = result.data;
                            priceInput.val(parseFloat(standardCharge).toFixed(2));
                            calculateAndSetInvoiceAmount();
                        }
                    },
                    error: function() {
                        priceInput.val('0.00');
                        calculateAndSetInvoiceAmount();
                    }
                });
            } else {
                priceInput.val('0.00');
                calculateAndSetInvoiceAmount();
            }
        });
        
    });
    
    // Function to add invoice item row
    function addInvoiceItemRow() {
        uniqueId++;
        
        // Get charges data from the hidden field
        let chargesJson = $('#createInvoiceCharges').val();
        let chargesData = [];
        
        if (chargesJson) {
            try {
                chargesData = JSON.parse(chargesJson);
            } catch (e) {
                console.error('Error parsing charges data in addInvoiceItemRow:', e);
            }
        }
        
        let data = {
            'uniqueId': uniqueId,
            'charges': chargesData
        };
        
        let invoiceItemHtml = invoiceItemTemplate.render(data);
        $('.invoice-item-container').append(invoiceItemHtml);
        
        // Get the newly added row
        let $newRow = $('.invoice-item-container tr:last');
        
        // First destroy any existing select2 instances to prevent duplicates
        if ($newRow.find('.chargeId').hasClass('select2-hidden-accessible')) {
            $newRow.find('.chargeId').select2('destroy');
        }
        
        // Initialize select2 with proper configuration - only for the new row
        setTimeout(function() {
            // First destroy if already initialized
            if ($newRow.find('.added-row-select').hasClass('select2-hidden-accessible')) {
                $newRow.find('.added-row-select').select2('destroy');
            }
            
            // Initialize with specific options for added rows
            $newRow.find('.added-row-select').select2({
                dropdownParent: $('#createInvoiceModal'),
                width: '100%',
                minimumResultsForSearch: 0  // Always show search field
            });
            
            // Add specific open event handler
            $newRow.find('.added-row-select').on('select2:open', function() {
                setTimeout(function() {
                    $('.select2-container--open .select2-search__field').focus();
                }, 0);
            });
            
            // Set default values
            $newRow.find('.qty').val(1);
            $newRow.find('.price').val('0.00');
            
            // Recalculate
            calculateAndSetInvoiceAmount();
        }, 100);
        
        resetInvoiceItemNumbers();
    }
    
    // Add invoice item button click handler
    $(document).on('click', '#addInvoiceItem', function () {
        addInvoiceItemRow();
        calculateAndSetInvoiceAmount();
    });
    
    // Delete invoice item button click handler
    $(document).on('click', '.deleteInvoiceItem', function () {
        $(this).closest('tr').remove();
        resetInvoiceItemNumbers();
        calculateAndSetInvoiceAmount();
        
        // If no items left, add a new row
        if ($('.invoice-item-container tr').length === 0) {
            addInvoiceItemRow();
        }
    });
    
    // Function to load charges data
    function loadChargesData() {
        // Get charges data from the hidden field in the add button template
        let chargesJson = $('#createInvoiceCharges').val();
        console.log('Charges JSON:', chargesJson);

        if (chargesJson) {
            try {
                let chargesData = JSON.parse(chargesJson);
                console.log('Parsed charges data:', chargesData);
                charges = chargesData;

                // Clear existing invoice items
                $('.invoice-item-container tr').remove();

                // Add first invoice item row with charges data
                addInvoiceItemRow();

                // Initialize all select2 elements
                setTimeout(function() {
                    initializeSelect2();
                }, 200);

            } catch (e) {
                console.error('Error parsing charges data:', e);
                // Still add a row even if there's an error parsing charges
                $('.invoice-item-container tr').remove();
                addInvoiceItemRow();
            }
        } else {
            console.error('Charges data not found');
            // Still add a row even if charges data is not found
            $('.invoice-item-container tr').remove();
            addInvoiceItemRow();
        }
    }
    
    // Function to initialize all select2 elements
    function initializeSelect2() {
        $('.chargeId').each(function() {
            // First destroy if already initialized
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).select2('destroy');
            }
            
            // Then initialize with proper configuration
            $(this).select2({
                dropdownParent: $('#createInvoiceModal'),
                width: '100%'
            }).on('select2:open', function() {
                setTimeout(function() {
                    $('.select2-search__field').focus();
                }, 0);
            });
        });
    }
    
    // Function to reset invoice item numbers
    function resetInvoiceItemNumbers() {
        let index = 1;
        $('.invoice-item-container tr').each(function() {
            $(this).find('.item-number').text(index);
            index++;
        });
    }
    
    // Function to calculate and set invoice amount
    function calculateAndSetInvoiceAmount() {
        let totalAmount = 0;
        
        $('.invoice-item-container tr').each(function () {
            let qty = parseInt($(this).find('.qty').val()) || 0;
            let priceVal = $(this).find('.price').val();
            let price = 0;
            
            if (priceVal) {
                price = parseFloat(priceVal.toString().replace(/,/g, '')) || 0;
            }
            
            let amount = qty * price;
            
            if (!isNaN(amount)) {
                $(this).find('.amount').text(amount.toFixed(2));
                totalAmount += amount;
            }
        });
        
        $('#total').text(totalAmount.toFixed(2));
        
        let discountPercent = parseFloat($('#discount').val()) || 0;
        
        let discountAmount = (totalAmount * discountPercent) / 100;
        $('#discountAmount').text(discountAmount.toFixed(2));
        
        let finalAmount = totalAmount - discountAmount;
        $('#finalAmount').text(finalAmount.toFixed(2));
    }
    
    // Function to initialize select2 for charge dropdown
    function initializeChargeSelect2(selector) {
        $(selector).select2({
            dropdownParent: $('#createInvoiceModal')
        });
    }
    
    // Function to validate form
    function validateForm() {
        // Clear previous errors
        $('#invoiceErrorsBox').addClass('d-none').html('');
        
        let isValid = true;
        let errorMessages = [];
        
        // Check patient
        if (!$('#invoice_patient_id').val()) {
            errorMessages.push('Please select a patient');
            isValid = false;
        }
        
        // Check invoice date
        if (!$('#invoice_date').val()) {
            errorMessages.push('Please select an invoice date');
            isValid = false;
        }
        
        // Check if at least one item has values
        let hasValidItems = false;
        $('.invoice-item-container tr').each(function() {
            let chargeId = $(this).find('.chargeId').val();
            let price = $(this).find('.price').val();
            
            if (chargeId && price) {
                hasValidItems = true;
            }
        });
        
        if (!hasValidItems) {
            errorMessages.push('Please add at least one valid invoice item');
            isValid = false;
        }
        
        // Display errors if any
        if (!isValid) {
            $('#invoiceErrorsBox').removeClass('d-none').html(errorMessages.join('<br>'));
        }
        
        return isValid;
        $('#modal_discount').val(0);
        $('.invoice-item-container').empty();
        $('#modal_total').text('0');
        $('#modal_discountAmount').text('0');
        $('#modal_finalAmount').text('0');
        $('#modal_total_amount').val(0);
        $('#invoiceValidationErrorsBox').hide().html('');
    }
</script>
