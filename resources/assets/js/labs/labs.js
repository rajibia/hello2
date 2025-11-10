'use strict';

function updateLabStatus(id) {
    $.ajax({
        url: $('#showLabReportUrl').val() + '/' + id + '/active-deactive',
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
            }
        },
    });
}

listenClick('.delete-lab-btn', function (event) {
    let labId = $(event.currentTarget).attr('data-id');
    
    deleteItem(
        $('#showLabReportUrl').val() + '/' + labId,
        '',
        $('#Lab').val(),
    );
});

listenChange('.labStatus', function (event) {
    let labId = $(event.currentTarget).attr('data-id');
    updateLabStatus(labId);
});

listenChange('#lab_filter_status', function () {
    window.livewire.emit('changeFilter', 'statusFilter', $(this).val())
    hideDropdownManually($('#incomeFilterBtn'), $('#incomeFilter'));
});

listenClick('#labResetFilter', function () {
    $('#lab_filter_status').val(0).trigger('change');
    hideDropdownManually($('#labFilterBtn'), $('.dropdown-menu'));
});
