'use strict';
document.addEventListener('turbo:load', loadRadiologyParameterData)

function loadRadiologyParameterData() {
    $('#radiologyParameterUnitId,.edit-unit').select2({
        width: '100%',
    });
}

listenSubmit('#addRadiologyParameterForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#radiologyParameterSave');
    loadingButton.button('loading');
    $('#radiologyParameterSave').attr('disabled', true);
    $.ajax({
        url: $('#createRadiologyParameterURL').val(),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addRadiologyParametersModal').modal('hide');
                window.livewire.emit('refresh')
                $('#radiologyParameterSave').attr('disabled', false);
            }
        },
        error: function (result) {
            printErrorMessage('#parameterValidationErrorsBox', result);
            $('#radiologyParameterSave').attr('disabled', false);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenSubmit('#editRadiologyParameterForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#editRadiologyParameterSaveBtn');
    loadingButton.button('loading');
    var id = $('#radiologyParameterId').val();
    $('#editRadiologyCategorySaveBtn').attr('disabled', true);
    $.ajax({
        url: $('#radiologyParameterURL').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editRadiologyParametersModal').modal('hide');
                window.livewire.emit('refresh')
                $('#editRadiologyParameterSaveBtn').attr('disabled', false);
            }
        },
        error: function (result) {
            UnprocessableInputError(result);
            $('#editRadiologyParameterSaveBtn').attr('disabled', false);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenHiddenBsModal('#addRadiologyParametersModal', function () {
    resetModalForm('#addRadiologyParameterForm', '#parameterValidationErrorsBox');
    $('#radiologyParameterSave').attr('disabled', false);
});

listenHiddenBsModal('#editRadiologyParametersModal', function () {
    resetModalForm('#editRadiologyParameterForm', '#editParameterValidationErrorsBox');
    $('#editRadiologyParameterSaveBtn').attr('disabled', false);
});

window.renderRadiologyParameterData = function (id) {
    $.ajax({
        url: $('#radiologyParameterURL').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                let radiologyParameter = result.data
                $('#radiologyParameterId').val(radiologyParameter.id)
                $('#editRadiologyParameterName').val(radiologyParameter.parameter_name)
                $('#editParameterRange').val(radiologyParameter.reference_range)
                $('#editRadiologyUnitId').
                val(radiologyParameter.unit_id).
                trigger('change')
                $('#editParameterDescription').val(radiologyParameter.description)
                $('#editRadiologyParametersModal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
};

listenClick('.edit-radiology-parameter-btn', function (event) {
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress();
    let radiologyParameterId = $(event.currentTarget).attr('data-id');
    renderRadiologyParameterData(radiologyParameterId)
});

listenClick('.delete-radiology-parameter-btn', function (event) {
    let radiologyParameterId = $(event.currentTarget).attr('data-id');
    deleteItem($('#radiologyParameterURL').val() + '/' + radiologyParameterId,
        '#radiologyParameterTable', $('#radiologyParameterLang').val());
});
