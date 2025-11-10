'use strict';

function updateDiagnosisStatus(id) {
    $.ajax({
        url: $('#showDiagnosisReportUrl').val() + '/' + id + '/active-deactive',
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
            }
        },
    });
}

listenClick('.delete-diagnosis-btn', function (event) {
    let diagnosisId = $(event.currentTarget).attr('data-id');
    
    deleteItem(
        $('#showDiagnosisReportUrl').val() + '/' + diagnosisId,
        '',
        $('#Diagnosis').val(),
    );
});

listenChange('.diagnosisStatus', function (event) {
    let diagnosisId = $(event.currentTarget).attr('data-id');
    updateDiagnosisStatus(diagnosisId);
});

listenChange('#diagnosis_filter_status', function () {
    window.livewire.emit('changeFilter', 'statusFilter', $(this).val())
    hideDropdownManually($('#incomeFilterBtn'), $('#incomeFilter'));
});

listenClick('#diagnosisResetFilter', function () {
    $('#diagnosis_filter_status').val(0).trigger('change');
    hideDropdownManually($('#diagnosisFilterBtn'), $('.dropdown-menu'));
});
