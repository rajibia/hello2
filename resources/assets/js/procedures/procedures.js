'use strict';

function updateProcedureStatus(id) {
    $.ajax({
        url: $('#showProcedureReportUrl').val() + '/' + id + '/active-deactive',
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
            }
        },
    });
}

listenClick('.delete-procedure-btn', function (event) {
    let procedureId = $(event.currentTarget).attr('data-id');
    
    deleteItem(
        $('#showProcedureReportUrl').val() + '/' + procedureId,
        '',
        $('#Procedure').val(),
    );
});

listenChange('.procedureStatus', function (event) {
    let procedureId = $(event.currentTarget).attr('data-id');
    updateProcedureStatus(procedureId);
});

listenChange('#procedure_filter_status', function () {
    window.livewire.emit('changeFilter', 'statusFilter', $(this).val())
    hideDropdownManually($('#incomeFilterBtn'), $('#incomeFilter'));
});

listenClick('#procedureResetFilter', function () {
    $('#procedure_filter_status').val(0).trigger('change');
    hideDropdownManually($('#procedureFilterBtn'), $('.dropdown-menu'));
});
