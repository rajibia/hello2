document.addEventListener('turbo:load', loadScanEditData)

function loadScanEditData() {
    if (!$('#createScanForm').length && !$('#editScanForm').length) {
        return
    }
    $('#scanStatus').select2({
        width: '100%',
    });

    $('.price-input').trigger('input');

    $(window).on('beforeunload', function () {
        $('input[type=submit]').prop('disabled', 'disabled');
    });

    $('#createScanForm, #editScanForm').find('input:text:visible:first').focus();
}
    listenSubmit('#createScanForm, #editScanForm', function () {
        $('#scanBtnSave').attr('disabled', true);
    });

