import moment from 'moment'

// document.addEventListener('turbo:load', loadSystemicExaminationData)


listenClick('.deleteSystemicExaminationBtn', function (event) {
    let id = $(event.currentTarget).attr('data-id')
    deleteItem(
        $('#showSystemicExaminationUrl').val() + '/' + id,
        null,
        $('#systemicExaminationDeleteBtn').val()
    )
})

listenSubmit('#addSystemicExaminationForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnSystemicExaminationSave')
    loadingButton.button('loading')
    let data = {
        formSelector: $(this),
        url: $('#showSystemicExaminationCreateUrl').val(),
        type: 'POST'
    }
    newRecord(data, loadingButton, '#add_systemic_examination_modal')
    loadingButton.attr('disabled', false)
})

listenClick('.editSystemicExaminationBtn', function (event) {
    
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress()
    let systemicExaminationId = $(event.currentTarget).attr('data-id')
    
    renderSystemicExaminationData(systemicExaminationId)
})

window.renderSystemicExaminationData = function (id) {
    console.log($('#showSystemicExaminationUrl').val() + '/' + id + '/edit');
    $.ajax({
        url: $('#showSystemicExaminationUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#editSystemicExaminationId').val(result.data.id);
                $('#editSystemicExaminationValue').val(result.data.systemic_examination);
                // Assign values to the radio buttons
                $('input[name="lungs_status"][value="' + result.data.lungs_status + '"]').prop('checked', true);
                $('#edit_lungs_description_systemic').val(result.data.lungs_description);

                $('input[name="cardio_status"][value="' + result.data.cardio_status + '"]').prop('checked', true);
                $('#edit_cardio_description_systemic').val(result.data.cardio_description);

                $('input[name="abdomen_status"][value="' + result.data.abdomen_status + '"]').prop('checked', true);
                $('#edit_abdomen_description_systemic').val(result.data.abdomen_description);

                $('input[name="ear_status"][value="' + result.data.ear_status + '"]').prop('checked', true);
                $('#edit_ear_description_systemic').val(result.data.ear_description);

                $('input[name="nose_status"][value="' + result.data.nose_status + '"]').prop('checked', true);
                $('#edit_nose_description_systemic').val(result.data.nose_description);

                $('input[name="throat_status"][value="' + result.data.throat_status + '"]').prop('checked', true);
                $('#edit_throat_description_systemic').val(result.data.throat_description);

                $('input[name="musco_status"][value="' + result.data.musco_status + '"]').prop('checked', true);
                $('#edit_musco_description').val(result.data.musco_description);

                $('input[name="nervous_status"][value="' + result.data.nervous_status + '"]').prop('checked', true);
                $('#edit_nervous_description_systemic').val(result.data.nervous_description);

                $('input[name="skin_status"][value="' + result.data.skin_status + '"]').prop('checked', true);
                $('#edit_skin_description_systemic').val(result.data.skin_description);

                $('input[name="eye_status"][value="' + result.data.eye_status + '"]').prop('checked', true);
                
                $('#edit_eye_description_systemic').val(result.data.eye_description);
                $('#edit_systemic_examination_modal').modal('show');
                ajaxCallCompleted();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        }
    });
}

listenClick('.viewSystemicExaminationBtn', function (event) {
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress()
    let opdDiagnosisId = $(event.currentTarget).attr('data-id')
    renderSystemicExaminationDataView(opdDiagnosisId)
})

window.renderSystemicExaminationDataView = function (id) {
    $.ajax({
        url: $('#showSystemicExaminationUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                if (result.data.systemic_examination != '' && result.data.systemic_examination != null) {
                    $('#opdSystemicExaminationView').text(result.data.systemic_examination)
                } else {
                    $('#opdSystemicExaminationView').text('N/A')
                }

                // Assign values to the radio buttons
                $('input[name="lungs_status"][value="' + result.data.lungs_status + '"]').prop('checked', true);
                $('#opdLungsDescriptionViewSystemic').val(result.data.lungs_description);

                $('input[name="cardio_status"][value="' + result.data.cardio_status + '"]').prop('checked', true);
                $('#opdCardioDescriptionViewSystemic').val(result.data.cardio_description);

                $('input[name="abdomen_status"][value="' + result.data.abdomen_status + '"]').prop('checked', true);
                $('#opdAbdomenDescriptionViewSystemic').val(result.data.abdomen_description);

                $('input[name="ear_status"][value="' + result.data.ear_status + '"]').prop('checked', true);
                $('#opdEarDescriptionViewSystemic').val(result.data.ear_description);

                $('input[name="nose_status"][value="' + result.data.nose_status + '"]').prop('checked', true);
                $('#opdNoseDescriptionViewSystemic').val(result.data.nose_description);

                $('input[name="throat_status"][value="' + result.data.throat_status + '"]').prop('checked', true);
                $('#opdThroatDescriptionViewSystemic').val(result.data.throat_description);

                $('input[name="musco_status"][value="' + result.data.musco_status + '"]').prop('checked', true);
                $('#opdMuscoDescriptionViewSystemic').val(result.data.musco_description);

                $('input[name="nervous_status"][value="' + result.data.nervous_status + '"]').prop('checked', true);
                $('#opdNervousDescriptionViewSystemic').val(result.data.nervous_description);

                $('input[name="skin_status"][value="' + result.data.skin_status + '"]').prop('checked', true);
                $('#opdSkinDescriptionViewSystemic').val(result.data.skin_description);

                $('input[name="eye_status"][value="' + result.data.eye_status + '"]').prop('checked', true);
                $('#opdEyeDescriptionViewSystemic').val(result.data.eye_description);

                $('#show_systemic_examination_modal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result)
        }
    })
}

listenSubmit('#editSystemicExaminationForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnEditSystemicExaminationSave')
    loadingButton.button('loading')
    let id = $('#editSystemicExaminationId').val()
    // let url = $('#showSystemicExaminationUrl').val() + '/' + id
    // let data = {
    //     formSelector: $(this),
    //     url: url,
    //     type: 'PUT',
    //     tableSelector: null
    // }
    $.ajax({
        url: $('#showSystemicExaminationUrl').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                $('#edit_systemic_examination_modal').modal('hide')
                
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
    // console.log(data.formSelector);
    // editRecord(data, loadingButton, '#edit_systemic_examination_modal')
})

listenHiddenBsModal('#add_systemic_examination_modal', function () {
    resetModalForm('#addSystemicExaminationForm', '#addSystemicExaminationErrorsBox')
})

listenHiddenBsModal('#edit_systemic_examination_modal', function () {
    resetModalForm('#editSystemicExaminationForm', '#editSystemicExaminationErrorsBox')
})


