document.addEventListener('turbo:load', loadProcedureEditData)

function loadProcedureEditData() {
    if (!$('#createProcedureForm').length && !$('#editProcedureForm').length) {
        return
    }
    $('#procedureStatus').select2({
        width: '100%',
    });

    $('.price-input').trigger('input');

    $(window).on('beforeunload', function () {
        $('input[type=submit]').prop('disabled', 'disabled');
    });

    $('#createProcedureForm, #editProcedureForm').find('input:text:visible:first').focus();
}
    listenSubmit('#createProcedureForm, #editProcedureForm', function () {
        $('#procedureBtnSave').attr('disabled', true);
    });

