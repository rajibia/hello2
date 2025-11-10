document.addEventListener('turbo:load', loadLabEditData)

function loadLabEditData() {
    if (!$('#createLabForm').length && !$('#editLabForm').length) {
        return
    }
    $('#labStatus').select2({
        width: '100%',
    });

    $('.price-input').trigger('input');

    $(window).on('beforeunload', function () {
        $('input[type=submit]').prop('disabled', 'disabled');
    });

    $('#createLabForm, #editLabForm').find('input:text:visible:first').focus();
}
    listenSubmit('#createLabForm, #editLabForm', function () {
        $('#labBtnSave').attr('disabled', true);
    });

