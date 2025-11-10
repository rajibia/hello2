document.addEventListener('turbo:load', loadSupplierListingData)

function loadSupplierListingData() {
    if (!$('#showSupplierUrl').length) {
        return
    }

    // Edit And Delete AdvancedPayment Modal
    $('#editSupplierPaymentDate').flatpickr({
        dateFormat: 'Y-m-d',
        locale : $('.userCurrentLanguage').val(),
    });

    $('#editAdvancedPaymentModal').on('shown.bs.modal', function () {
        $('#editSupplierId:first').focus();
    });

    // Edit And Delete Vaccination Modal
    $('#editVaccinationDoesGivenDate').flatpickr({
        enableTime: true,
        defaultDate: new Date(),
        locale : $('.userCurrentLanguage').val(),
        dateFormat: 'Y-m-d H:i',
    });

    listenShownBsModal('#editVaccinationModal', function () {
        $('#editSupplierVaccinationName, #editVaccinationSupplierName').select2({
            width: '100%',
            dropdownParent: $('#editVaccinationModal'),
        });
    });

    loadDeleteFunction()
}


listen('click', '.edit-advancedPayment-btn', function (event) {
    if ($('.ajaxCallIsRunning').val()) {
        return;
    }
    ajaxCallInProgress();
    let advancedPaymentId = $(event.currentTarget).attr('data-id');
    renderSupplierListingData(advancedPaymentId);
});

window.renderSupplierListingData = function (id) {
    $.ajax({
        url: $('#showSupplierAdvancedPaymentUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#supplierAdvancePaymentId').val(result.data.id);
                $('#editSupplierPaymentId').val(result.data.supplier_id).trigger('change.select2');
                $('#editSupplierPaymentReceiptNo').val(result.data.receipt_no);
                $('#editSupplierPaymentAmount').val(result.data.amount);
                $('.price-input').trigger('input');
                document.querySelector('#editSupplierPaymentDate')._flatpickr.setDate(moment(result.data.date).format());
                $('#editAdvancedPaymentModal').modal('show');
                ajaxCallCompleted();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
};

listenSubmit('#editAdvancedPaymentForm', function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find('#editSupplierPaymentSave');
    loadingButton.button('loading');
    let id = $('#supplierAdvancePaymentId').val();
    $.ajax({
        url: $('#showSupplierAdvancedPaymentUrl').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editAdvancedPaymentModal').modal('hide');
                location.reload();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenHiddenBsModal('#editAdvancedPaymentModal', function () {
    resetModalForm('#editAdvancedPaymentForm', '#editSupplierPaymentErrorsBox');
});


listen('click', '.edit-vaccination-btn', function (event) {
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress();
    let vaccinatedSupplierId = $(event.currentTarget).attr('data-id');
    renderVaccinationData(vaccinatedSupplierId);
});

window.renderVaccinationData = function (id) {
    $.ajax({
        url: $('#showVaccinatedSupplierUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                let vaccinatedSupplier = result.data;
                $('#vaccinatedSupplierId').val(vaccinatedSupplier.id);
                $('#editVaccinationSupplierName').val(vaccinatedSupplier.supplier_id).trigger('change.select2');
                $('#editSupplierVaccinationName').val(vaccinatedSupplier.vaccination_id).trigger('change.select2');
                $('#editVaccinationSerialNo').val(vaccinatedSupplier.vaccination_serial_number);
                $('#editVaccinationDoseNumber').val(vaccinatedSupplier.dose_number);
                document.querySelector('#editVaccinationDoesGivenDate')._flatpickr.setDate(moment(vaccinatedSupplier.dose_given_date).format());
                $('#editVaccinationDescription').val(vaccinatedSupplier.description);
                $('#editVaccinationModal').modal('show');
                ajaxCallCompleted();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
};

listenSubmit('#editVaccinationForm', function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find('#editVaccinationSave');
    loadingButton.button('loading');
    let id = $('#vaccinatedSupplierId').val();
    $.ajax({
        url: $('#showVaccinatedSupplierUrl').val() + '/' + id + '/update',
        type: 'post',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editVaccinationModal').modal('hide');
                location.reload();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenHiddenBsModal('#editVaccinationModal', function () {
    resetModalForm('#editVaccinationForm', '#editSupplierVaccinationErrorsBox1');
});

function loadDeleteFunction() {
    if (!$('#showSupplierUrl').length) {
        return
    }

    listen('click', '.layout-delete-btn', function (event) {
        let Ele = $(this);
        let id = $(event.currentTarget).attr('data-id');
        let url = $(this).data('url');
        let message = $(this).data('message');
        deleteItem(url + '/' + id, '', message);
    });
}
