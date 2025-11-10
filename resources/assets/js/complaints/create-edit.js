import moment from 'moment'

// document.addEventListener('turbo:load', loadComplaintData)


listenClick('.deleteComplaintBtn', function (event) {
    let id = $(event.currentTarget).attr('data-id')
    deleteItem(
        $('#showComplaintUrl').val() + '/' + id,
        null,
        $('#complaintDeleteBtn').val()
    )
})

listenSubmit('#addComplaintForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnComplaintSave')
    loadingButton.button('loading')
    let data = {
        formSelector: $(this),
        url: $('#showComplaintCreateUrl').val(),
        type: 'POST'
    }
    // console.log(url);
    newRecord(data, loadingButton, '#add_complaint_modal')
    loadingButton.attr('disabled', false)
})

listenClick('.editComplaintBtn', function (event) {
    
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress()
    let complaintId = $(event.currentTarget).attr('data-id')
    
    renderComplaintData(complaintId)
})

window.renderComplaintData = function (id) {
    console.log($('#showComplaintUrl').val() + '/' + id + '/edit');
    $.ajax({
        url: $('#showComplaintUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#editComplaintId').val(result.data.id)
                $('#editMainComplaintValue').val(result.data.main_complaint)
                $('#editMainComplaintProgressionValue').val(result.data.main_complaint_progression)
                $('#editDirectQuestioningValue').val(result.data.direct_questioning)
                $('#editDrugHistoryValue').val(result.data.drug_history)
                $('#edit_complaint_modal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result)
        }
    })
}

listenClick('.viewComplaintBtn', function (event) {
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress()
    let opdDiagnosisId = $(event.currentTarget).attr('data-id')
    renderComplaintDataView(opdDiagnosisId)
})

window.renderComplaintDataView = function (id) {
    $.ajax({
        url: $('#showComplaintUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                

                if (
                    result.data.main_complaint != '' &&
                    result.data.main_complaint != null
                ) {
                    $('#opdMainComplaintView').text(
                        result.data.main_complaint
                    )
                } else {
                    $('#opdMainComplaintView').text('N/A')
                }
                if (
                    result.data.main_complaint_progression != '' &&
                    result.data.main_complaint_progression != null
                ) {
                    $('#opdMainComplaintProgressionView').text(
                        result.data.main_complaint_progression
                    )
                } else {
                    $('#opdMainComplaintProgressionView').text('N/A')
                }
                if (
                    result.data.direct_questioning != '' &&
                    result.data.direct_questioning != null
                ) {
                    $('#opdDirectQuestioningView').text(
                        result.data.direct_questioning
                    )
                } else {
                    $('#opdDirectQuestioningView').text('N/A')
                }
                if (
                    result.data.drug_history != '' &&
                    result.data.drug_history != null
                ) {
                    $('#opdDrugHistoryView').text(
                        result.data.drug_history
                    )
                } else {
                    $('#opdDrugHistoryView').text('N/A')
                }
                $('#show_complaint_modal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result)
        }
    })
}

listenSubmit('#editComplaintForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnEditComplaintSave')
    loadingButton.button('loading')
    let id = $('#editComplaintId').val()
    // let url = $('#showComplaintUrl').val() + '/' + id
    // let data = {
    //     formSelector: $(this),
    //     url: url,
    //     type: 'PUT',
    //     tableSelector: null
    // }
    // editRecord(data, loadingButton, '#edit_complaint_modal')

    $.ajax({
        url: $('#showComplaintUrl').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                $('#edit_complaint_modal').modal('hide')
                
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

listenHiddenBsModal('#add_complaint_modal', function () {
    resetModalForm('#addComplaintForm', '#addComplaintErrorsBox')
})

listenHiddenBsModal('#edit_complaint_modal', function () {
    resetModalForm('#editComplaintForm', '#editComplaintErrorsBox')
})


