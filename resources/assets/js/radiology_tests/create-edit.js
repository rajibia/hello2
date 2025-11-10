document.addEventListener('turbo:load', loadRadiologyTestData)

function loadRadiologyTestData() {
    if (!$('#createRadiologyTest').length && !$('#editRadiologyTest').length) {
        return
    }

    $('.price-input').trigger('input');
    $('.radiologyCategories,.pChargeCategories').select2({
        width: '100%',
    });

    $('#createRadiologyTest, #editRadiologyTest').find('input:text:visible:first').focus();

}


listenChange('.pChargeCategories', function (event) {
    let chargeCategoryId = $(this).val();
    (chargeCategoryId !== '') ? getRadiologyTestStandardCharge(chargeCategoryId) : $(
        '.radiologyStandardCharge').val('');
});

function getRadiologyTestStandardCharge(id) {
    $.ajax({
        url: $('.radiologyTestActionURL').val() + '/get-standard-charge' + '/' + id,
        method: 'get',
        cache: false,
        success: function (result) {
            if (result !== '') {
                $('.radiologyStandardCharge').val(result.data);
                $('.price-input').trigger('input');
            }
        },
    });
}
