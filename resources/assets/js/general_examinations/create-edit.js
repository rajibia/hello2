import moment from 'moment'

// document.addEventListener('turbo:load', loadGeneralExaminationData)


listenClick('.deleteGeneralExaminationBtn', function (event) {
    let id = $(event.currentTarget).attr('data-id')
    deleteItem(
        $('#showGeneralExaminationUrl').val() + '/' + id,
        null,
        $('#generalExaminationDeleteBtn').val()
    )
})

listenSubmit('#addGeneralExaminationForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnGeneralExaminationSave')
    loadingButton.button('loading')
    let data = {
        formSelector: $(this),
        url: $('#showGeneralExaminationCreateUrl').val(),
        type: 'POST'
    }
    newRecord(data, loadingButton, '#add_general_examination_modal')
    loadingButton.attr('disabled', false)
})

listenClick('.editGeneralExaminationBtn', function (event) {
    
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress()
    let generalExaminationId = $(event.currentTarget).attr('data-id')
    
    renderGeneralExaminationData(generalExaminationId)
})

window.renderGeneralExaminationData = function (id) {
    console.log($('#showGeneralExaminationUrl').val() + '/' + id + '/edit');
    $.ajax({
        url: $('#showGeneralExaminationUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#editGeneralExaminationId').val(result.data.id);
                $('#editGeneralExaminationValue').val(result.data.general_examination);
                // Assign values to the radio buttons
                $('input[name="lungs_status"][value="' + result.data.lungs_status + '"]').prop('checked', true);
                $('#edit_lungs_description').val(result.data.lungs_description);

                $('input[name="cardio_status"][value="' + result.data.cardio_status + '"]').prop('checked', true);
                $('#edit_cardio_description').val(result.data.cardio_description);

                $('input[name="abdomen_status"][value="' + result.data.abdomen_status + '"]').prop('checked', true);
                $('#edit_abdomen_description').val(result.data.abdomen_description);

                $('input[name="ear_status"][value="' + result.data.ear_status + '"]').prop('checked', true);
                $('#edit_ear_description').val(result.data.ear_description);

                $('input[name="nose_status"][value="' + result.data.nose_status + '"]').prop('checked', true);
                $('#edit_nose_description').val(result.data.nose_description);

                $('input[name="throat_status"][value="' + result.data.throat_status + '"]').prop('checked', true);
                $('#edit_throat_description').val(result.data.throat_description);

                $('input[name="musco_status"][value="' + result.data.musco_status + '"]').prop('checked', true);
                $('#edit_musco_description').val(result.data.musco_description);

                $('input[name="nervous_status"][value="' + result.data.nervous_status + '"]').prop('checked', true);
                $('#edit_nervous_description').val(result.data.nervous_description);

                $('input[name="skin_status"][value="' + result.data.skin_status + '"]').prop('checked', true);
                $('#edit_skin_description').val(result.data.skin_description);

                $('input[name="eye_status"][value="' + result.data.eye_status + '"]').prop('checked', true);
                
                $('#edit_eye_description').val(result.data.eye_description);
                $('#edit_general_examination_modal').modal('show');
                ajaxCallCompleted();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        }
    });
}

listenClick('.viewGeneralExaminationBtn', function (event) {
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress()
    let opdDiagnosisId = $(event.currentTarget).attr('data-id')
    renderGeneralExaminationDataView(opdDiagnosisId)
})

window.renderGeneralExaminationDataView = function (id) {
    $.ajax({
        url: $('#showGeneralExaminationUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                if (result.data.general_examination != '' && result.data.general_examination != null) {
                    $('#opdGeneralExaminationView').text(result.data.general_examination)
                } else {
                    $('#opdGeneralExaminationView').text('N/A')
                }

                // Assign values to the radio buttons with specific IDs
                $('#opdLungsStatusView' + result.data.lungs_status).prop('checked', true);
                $('#opdLungsDescriptionView').val(result.data.lungs_description);

                $('#cardioStatusView' + result.data.cardio_status).prop('checked', true);
                $('#opdCardioDescriptionView').val(result.data.cardio_description);

                $('#abdomenStatusView' + result.data.abdomen_status).prop('checked', true);
                $('#opdAbdomenDescriptionView').val(result.data.abdomen_description);

                $('#earStatusView' + result.data.ear_status).prop('checked', true);
                $('#opdEarDescriptionView').val(result.data.ear_description);

                $('#noseStatusView' + result.data.nose_status).prop('checked', true);
                $('#opdNoseDescriptionView').val(result.data.nose_description);

                $('#throatStatusView' + result.data.throat_status).prop('checked', true);
                $('#opdThroatDescriptionView').val(result.data.throat_description);

                $('#muscoStatusView' + result.data.musco_status).prop('checked', true);
                $('#opdMuscoDescriptionView').val(result.data.musco_description);

                $('#nervousStatusView' + result.data.nervous_status).prop('checked', true);
                $('#opdNervousDescriptionView').val(result.data.nervous_description);

                $('#skinStatusView' + result.data.skin_status).prop('checked', true);
                $('#opdSkinDescriptionView').val(result.data.skin_description);

                $('#eyeStatusView' + result.data.eye_status).prop('checked', true);
                $('#opdEyeDescriptionView').val(result.data.eye_description);

                $('#show_general_examination_modal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result)
        }
    })
}

listenSubmit('#editGeneralExaminationForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnEditGeneralExaminationSave')
    loadingButton.button('loading')
    let id = $('#editGeneralExaminationId').val()

    $.ajax({
        url: $('#showGeneralExaminationUrl').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                $('#edit_general_examination_modal').modal('hide')
                
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

listenHiddenBsModal('#add_general_examination_modal', function () {
    resetModalForm('#addGeneralExaminationForm', '#addGeneralExaminationErrorsBox')
})

listenHiddenBsModal('#edit_general_examination_modal', function () {
    resetModalForm('#editGeneralExaminationForm', '#editGeneralExaminationErrorsBox')
})


