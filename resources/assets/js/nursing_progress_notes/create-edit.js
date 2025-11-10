import moment from 'moment'

// document.addEventListener('turbo:load', loadNursingNoteData)


listenClick('.deleteNursingNoteBtn', function (event) {
    let id = $(event.currentTarget).attr('data-id')
    deleteItem(
        $('#showNursingNoteUrl').val() + '/' + id,
        null,
        $('#nursingNoteDeleteBtn').val()
    )
})

listenSubmit('#addNursingNoteForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnNursingNoteSave')
    loadingButton.button('loading')
    let data = {
        formSelector: $(this),
        url: $('#showNursingNoteCreateUrl').val(),
        type: 'POST'
    }
    newRecord(data, loadingButton, '#add_nursing_note_modal')
    loadingButton.attr('disabled', false)
})

listenClick('.editNursingNoteBtn', function (event) {
    
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress()
    let noteId = $(event.currentTarget).attr('data-id')
    
    renderNursingNoteData(noteId)
})

window.renderNursingNoteData = function (id) {
    console.log($('#showNursingNoteUrl').val() + '/' + id + '/edit');
    $.ajax({
        url: $('#showNursingNoteUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#editNursingNoteId').val(result.data.id)
                $('#editNursingNoteValue').val(result.data.notes)
                $('#edit_nursing_note_modal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result)
        }
    })
}

listenClick('.viewNursingNoteBtn', function (event) {
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress()
    let opdDiagnosisId = $(event.currentTarget).attr('data-id')
    renderNursingNoteDataView(opdDiagnosisId)
})

window.renderNursingNoteDataView = function (id) {
    $.ajax({
        url: $('#showNursingNoteUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                

                if (
                    result.data.notes != '' &&
                    result.data.notes != null
                ) {
                    $('#opdNursingNoteView').text(
                        result.data.notes
                    )
                } else {
                    $('#opdNursingNoteView').text('N/A')
                }
                $('#show_nursing_note_modal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result)
        }
    })
}

listenSubmit('#editNursingNoteForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnEditNursingNoteSave')
    loadingButton.button('loading')
    let id = $('#editNursingNoteId').val()
    $.ajax({
        url: $('#showNursingNoteUrl').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                $('#edit_nursing_note_modal').modal('hide')
                
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

listenHiddenBsModal('#add_nursing_note_modal', function () {
    resetModalForm('#addNursingNoteForm', '#addNursingNoteErrorsBox')
})

listenHiddenBsModal('#edit_nursing_note_modal', function () {
    resetModalForm('#editNursingNoteForm', '#editNursingNoteErrorsBox')
})


