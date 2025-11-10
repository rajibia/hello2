'use strict';
listenSubmit('#addRadiologyUnitForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#radiologyUnitSave');
    loadingButton.button('loading');
    $('#radiologyUnitSave').attr('disabled', true);
    $.ajax({
        url: $('#createRadiologyUnitURL').val(),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addRadiologyUnitsModal').modal('hide');
                window.livewire.emit('refresh')
                $('#radiologyUnitSave').attr('disabled', false);
            }
        },
        error: function (result) {
            printErrorMessage('#pUniValidationErrorsBox', result);
            $('#radiologyUnitSave').attr('disabled', false);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenSubmit('#editRadiologyUnitsForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#editRadiologyUnitSaveBtn');
    loadingButton.button('loading');
    var id = $('#radiologyUnitId').val();
    $('#editRadiologyUnitSaveBtn').attr('disabled', true);
    $.ajax({
        url: $('#radiologyUnitURL').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editRadiologyUnitsModal').modal('hide');
                window.livewire.emit('refresh')
                $('#editRadiologyUnitSaveBtn').attr('disabled', false);
            }
        },
        error: function (result) {
            UnprocessableInputError(result);
            $('#editRadiologyUnitSaveBtn').attr('disabled', false);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenHiddenBsModal('#addRadiologyUnitsModal', function () {
    resetModalForm('#addRadiologyUnitForm', '#pUniValidationErrorsBox');
    $('#radiologyUnitSave').attr('disabled', false);
});

listenHiddenBsModal('#editRadiologyUnitsModal', function () {
    resetModalForm('#editRadiologyUnitsForm', '#editPUniValidationErrorsBox');
    $('#editRadiologyUnitSaveBtn').attr('disabled', false);
});

window.renderRadiologyUnitData = function (id) {
    $.ajax({
        url: $('#radiologyUnitURL').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                let radiologyCategory = result.data
                $('#radiologyUnitId').val(radiologyCategory.id)
                $('#editRadiologyUnitName').val(radiologyCategory.name)
                $('#editRadiologyUnitsModal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
};

listenClick('.edit-radiology-unit-btn', function (event) {
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress();
    let radiologyUnitId = $(event.currentTarget).attr('data-id');
    renderRadiologyUnitData(radiologyUnitId)
});

listenClick('.delete-radiology-unit-btn', function (event) {
    let radiologyUnitId = $(event.currentTarget).attr('data-id');
    deleteItem($('#radiologyUnitURL').val() + '/' + radiologyUnitId,
        '#radiologyUnitTable', $('#radiologyUnitLang').val());
});
