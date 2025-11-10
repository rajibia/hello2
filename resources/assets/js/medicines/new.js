document.addEventListener('turbo:load', loadMedicineData)

function loadMedicineData() {
    if (!$('#createMedicine').length && !$('#editMedicine').length && !$('#transferMedicine').length) {
        return
    }

    let qtyEle = $('#qty');
    qtyEle.blur(() => {
        if (qtyEle.val() < 0) {
            qtyEle.val(0);
        }
    });

    $('#medicineBrandId,#medicineCategoryId', '#medicineTransferFrom', '#medicineTransferTo').select2({
        width: '100%',
    });

    $('#medicineNameId').focus();
}
    listenSubmit('#createMedicine, #editMedicine', function () {
        $('#medicineSave').attr('disabled', true);
    });

    // function listenSubmit(formId, submitCallback) {
    //     $(formId).on('submit', function (e) {
    //         e.preventDefault(); // Prevent default form submission

    //         // Execute the provided submitCallback
    //         var shouldSubmit = submitCallback();

    //         // Submit the form if shouldSubmit is true
    //         if (shouldSubmit) {
    //             this.submit();
    //         }
    //     });
    // }
    // listenSubmit('#transferMedicine', function () {
    //     // Reset previous validation errors
    //     resetValidationErrors();

    //     // Validate transfer_from, transfer_to, and transfer_quantity
    //     var transferFrom = $('#medicineTransferFrom').val();
    //     var transferTo = $('#medicineTransferTo').val();
    //     var transferQuantity = $('#transferQuantityId').val();

    //     if (transferFrom === '' || transferTo === '' || transferQuantity === '' || transferQuantity <= 0) {
    //         // Display validation error messages
    //         displayValidationErrors({
    //             transfer_from: transferFrom === '' ? 'Transfer From is required' : '',
    //             transfer_to: transferTo === '' ? 'Transfer To is required' : '',
    //             transfer_quantity: transferQuantity === '' || transferQuantity <= 0 ? 'Transfer Quantity must be greater than 0' : '',
    //         });

    //         // Prevent form submission
    //         $('#medicineSave').attr('disabled', true);

    //         // Focus on the first empty input
    //         focusOnFirstEmptyInput();
    //     } else if (transferFrom === transferTo) {
    //         // Display validation error message
    //         displayValidationErrors({
    //             transfer_from: 'Transfer From cannot be the same as Transfer To',
    //         });

    //         // Prevent form submission
    //         $('#medicineSave').attr('disabled', true);

    //         // Focus on the first empty input
    //         focusOnFirstEmptyInput();
    //     } else {
    //         // Additional validation for transfer_from quantity
    //         var transferFromQuantityId;
    //         if (transferFrom === 'Dispensary') {
    //             transferFromQuantityId = $('#quantityId');
    //         } else if (transferFrom === 'Store') {
    //             transferFromQuantityId = $('#storeQuantityId');
    //         }

    //         if (transferFromQuantityId && parseInt(transferFromQuantityId.val()) < parseInt(transferQuantity)) {
    //             // Display validation error message
    //             displayValidationErrors({
    //                 transfer_from_quantity: 'Transfer From quantity must be greater than or equal to Transfer Quantity',
    //             });

    //             // Prevent form submission
    //             $('#medicineSave').attr('disabled', true);

    //             // Focus on the first empty input
    //             focusOnFirstEmptyInput();
    //         } else {
    //             // Allow form submission
    //             $('#medicineSave').removeAttr('disabled');
    //         }
    //     }
    // });

    // // Function to reset validation errors
    // function resetValidationErrors() {
    //     $('.validation-error').text('');
    // }

    // // Function to display validation errors
    // function displayValidationErrors(errors) {
    //     for (var field in errors) {
    //         if (errors.hasOwnProperty(field)) {
    //             $('#' + field + 'Error').text(errors[field]);
    //         }
    //     }
    // }

    // // Function to focus on the first empty input
    // function focusOnFirstEmptyInput() {
    //     var firstEmptyInput = $('input:not([type="hidden"]), select').filter(function() {
    //         return this.value === '';
    //     }).first();

    //     if (firstEmptyInput.length) {
    //         firstEmptyInput.focus();
    //     }
    // }