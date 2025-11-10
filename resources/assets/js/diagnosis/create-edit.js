document.addEventListener('turbo:load', loadDiagnosisEditData)

function loadDiagnosisEditData() {
    if (!$('#createDiagnosisForm').length && !$('#editDiagnosisForm').length) {
        return
    }
    $('#diagnosisStatus').select2({
        width: '100%',
    });

    $('.price-input').trigger('input');

    $(window).on('beforeunload', function () {
        $('input[type=submit]').prop('disabled', 'disabled');
    });

    $('#createDiagnosisForm, #editDiagnosisForm').find('input:text:visible:first').focus();
}
    listenSubmit('#createDiagnosisForm, #editDiagnosisForm', function () {
        $('#diagnosisBtnSave').attr('disabled', true);
    });

