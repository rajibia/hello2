'use strict';

document.addEventListener('turbo:load', loadChargeTypeCreateEdit)

function loadChargeTypeCreateEdit () {

    if (!$('#addChargeTypeForm').length && !$('#editChargeTypeForm').length) {
        return false;
    }
    
    const chargeTypeTypeIdElement = $('#chargeTypeTypeId')
    const editChargeTypeTypeIdElement = $('#editChargeTypeTypeId')
    
    if(chargeTypeTypeIdElement.length){
        $('#chargeTypeTypeId').select2({
            width: '100%',
            dropdownParent: $('#add_charge_types_modal')
        });
    }

    if(editChargeTypeTypeIdElement.length){
        $('#editChargeTypeTypeId').select2({
            width: '100%',
            dropdownParent: $('#edit_charge_types_modal')
        });
    }

}

listenSubmit('#addChargeTypeForm', function (event) {
        event.preventDefault();
        var loadingButton = jQuery(this).find('#chargeTypeSave');
        loadingButton.button('loading');
        $.ajax({
            url: $('.chargeTypeCreateURLID').val(),
            type: 'POST',
            data: $(this).serialize(),
            success: function (result) {
                if (result.success) {
                    displaySuccessMessage(result.message);
                    $('#add_charge_types_modal').modal('hide');
                    livewire.emit('refresh');
                }
            },
            error: function (result) {
                printErrorMessage('#chargeTypeErrorsBox', result);
            },
            complete: function () {
                loadingButton.button('reset');
            },
        });
});

listenClick('.charge-type-edit-btn', function (event) {
        if ($('.ajaxCallIsRunning').val()) {
            return;
        }
        ajaxCallInProgress();
        let chargeTypeId = $(event.currentTarget).attr('data-id');
        renderChargeTypeData(chargeTypeId);
});

function renderChargeTypeData(id) {
    $.ajax({
            url: $('#chargeTypeURLID').val() + '/' + id + '/edit',
            type: 'GET',
            success: function (result) {
                if (result.success) {
                    $('#chargeCatId').val(result.data.id);
                    $('#editChargeTypeName').val(result.data.name);
                    $('#edit_charge_types_modal').modal('show');
                    ajaxCallCompleted();
                }
            },
            error: function (result) {
                manageAjaxErrors(result);
            },
        });
}

listenSubmit('#editChargeTypeForm', function (event) {
        event.preventDefault();
        var loadingButton = jQuery(this).find('#editChargeTypeSave');
        loadingButton.button('loading');
        let id = $('#chargeCatId').val();
        $.ajax({
            url: $('#chargeTypeURLID').val() + '/' + id,
            type: 'patch',
            data: $(this).serialize(),
            success: function (result) {
                if (result.success) {
                    displaySuccessMessage(result.message);
                    $('#edit_charge_types_modal').modal('hide');
                    livewire.emit('refresh');
                }
            },
            error: function (result) {
                UnprocessableInputError(result);
            },
            complete: function () {
                loadingButton.button('reset');
            },
        });
});

listenHiddenBsModal('#add_charge_types_modal', function () {
        resetModalForm('#addChargeTypeForm', '#chargeTypeErrorsBox');
        $('#chargeTypeTypeId').val('').trigger('change.select2');
});

listenHiddenBsModal('#edit_charge_types_modal', function () {
            resetModalForm('#editChargeTypeForm', '#editChargeTypeErrorsBox');
        $('#editChargeTypeTypeId').val('').trigger('change.select2');
});
 
