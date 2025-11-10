listen('click', '.deleteMaternityPatientBtn', function (event) {
    let maternityPatientsId = $(this).attr('data-id');
    deleteItem($('#indexMaternityPatientUrl').val() + '/' + maternityPatientsId, null,
        $('#Receptionist').val())
});
