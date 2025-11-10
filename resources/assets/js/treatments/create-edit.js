import moment from 'moment'

// document.addEventListener('turbo:load', loadTreatmentData)


listenClick('.deleteTreatmentBtn', function (event) {
    let id = $(event.currentTarget).attr('data-id')
    deleteItem(
        $('#showTreatmentUrl').val() + '/' + id,
        null,
        $('#treatmentDeleteBtn').val()
    )
})

listenSubmit('#addTreatmentForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnTreatmentSave')
    loadingButton.button('loading')
    let data = {
        formSelector: $(this),
        url: $('#showTreatmentCreateUrl').val(),
        type: 'POST'
    }
    newRecord(data, loadingButton, '#add_treatment_modal')
    loadingButton.attr('disabled', false)
})

listenClick('.editTreatmentBtn', function (event) {
    
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress()
    let treatmentId = $(event.currentTarget).attr('data-id')
    
    renderTreatmentData(treatmentId)
})

window.renderTreatmentData = function (id) {
    console.log($('#showTreatmentUrl').val() + '/' + id + '/edit');
    $.ajax({
        url: $('#showTreatmentUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#editTreatmentId').val(result.data.id)
                $('#editTreatmentValue').val(result.data.treatment)
                $('#edit_treatment_modal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result)
        }
    })
}

listenClick('.viewTreatmentBtn', function (event) {
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress()
    let opdDiagnosisId = $(event.currentTarget).attr('data-id')
    renderTreatmentDataView(opdDiagnosisId)
})

window.renderTreatmentDataView = function (id) {
    $.ajax({
        url: $('#showTreatmentUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                

                if (
                    result.data.treatment != '' &&
                    result.data.treatment != null
                ) {
                    $('#opdTreatmentView').text(
                        result.data.treatment
                    )
                } else {
                    $('#opdTreatmentView').text('N/A')
                }
                $('#show_treatment_modal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result)
        }
    })
}

listenSubmit('#editTreatmentForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnEditTreatmentSave')
    loadingButton.button('loading')
    let id = $('#editTreatmentId').val()
    $.ajax({
        url: $('#showTreatmentUrl').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                $('#edit_treatment_modal').modal('hide')
                
                livewire.emit('refresh')
            }
        },
        error: function (result) {
            UnprocessableInputError(result);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
})

listenHiddenBsModal('#add_treatment_modal', function () {
    resetModalForm('#addTreatmentForm', '#addTreatmentErrorsBox')
})

listenHiddenBsModal('#edit_treatment_modal', function () {
    resetModalForm('#editTreatmentForm', '#editTreatmentErrorsBox')
})


