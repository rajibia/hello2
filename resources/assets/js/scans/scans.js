'use strict';

function updateScanStatus(id) {
    $.ajax({
        url: $('#showScanReportUrl').val() + '/' + id + '/active-deactive',
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
            }
        },
    });
}

listenClick('.delete-scan-btn', function (event) {
    let scanId = $(event.currentTarget).attr('data-id');
    
    deleteItem(
        $('#showScanReportUrl').val() + '/' + scanId,
        '',
        $('#Scan').val(),
    );
});

listenChange('.scanStatus', function (event) {
    let scanId = $(event.currentTarget).attr('data-id');
    updateScanStatus(scanId);
});

listenChange('#scan_filter_status', function () {
    window.livewire.emit('changeFilter', 'statusFilter', $(this).val())
    hideDropdownManually($('#incomeFilterBtn'), $('#incomeFilter'));
});

listenClick('#scanResetFilter', function () {
    $('#scan_filter_status').val(0).trigger('change');
    hideDropdownManually($('#scanFilterBtn'), $('.dropdown-menu'));
});
