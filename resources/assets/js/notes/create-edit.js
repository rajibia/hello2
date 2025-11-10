import moment from 'moment'

// document.addEventListener('turbo:load', loadNoteData)


listenClick('.deleteNoteBtn', function (event) {
    let id = $(event.currentTarget).attr('data-id')
    deleteItem(
        $('#showNoteUrl').val() + '/' + id,
        null,
        $('#noteDeleteBtn').val()
    )
})

listenSubmit('#addNoteForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnNoteSave')
    loadingButton.button('loading')
    let data = {
        formSelector: $(this),
        url: $('#showNoteCreateUrl').val(),
        type: 'POST'
    }
    newRecord(data, loadingButton, '#add_note_modal')
    loadingButton.attr('disabled', false)
})

listenClick('.editNoteBtn', function (event) {
    
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress()
    let noteId = $(event.currentTarget).attr('data-id')
    
    renderNoteData(noteId)
})

window.renderNoteData = function (id) {
    console.log($('#showNoteUrl').val() + '/' + id + '/edit');
    $.ajax({
        url: $('#showNoteUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#editNoteId').val(result.data.id)
                $('#editNoteValue').val(result.data.notes)
                $('#edit_note_modal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result)
        }
    })
}

listenClick('.viewNoteBtn', function (event) {
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress()
    let opdDiagnosisId = $(event.currentTarget).attr('data-id')
    renderNoteDataView(opdDiagnosisId)
})

window.renderNoteDataView = function (id) {
    $.ajax({
        url: $('#showNoteUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                

                if (
                    result.data.notes != '' &&
                    result.data.notes != null
                ) {
                    $('#opdNoteView').text(
                        result.data.notes
                    )
                } else {
                    $('#opdNoteView').text('N/A')
                }
                $('#show_note_modal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result)
        }
    })
}

listenSubmit('#editNoteForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnEditNoteSave')
    loadingButton.button('loading')
    let id = $('#editNoteId').val()
    $.ajax({
        url: $('#showNoteUrl').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                $('#edit_note_modal').modal('hide')
                
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

listenHiddenBsModal('#add_note_modal', function () {
    resetModalForm('#addNoteForm', '#addNoteErrorsBox')
})

listenHiddenBsModal('#edit_note_modal', function () {
    resetModalForm('#editNoteForm', '#editNoteErrorsBox')
})


