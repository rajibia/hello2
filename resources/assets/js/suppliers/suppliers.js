listen('click', '.delete-supplier-btn', function (event) {
    let supplierId = $(event.currentTarget).attr('data-id');
    deleteItem($('#indexSupplierUrl').val() + '/' + supplierId, '', $('#Suppliers').val());
});
listenChange('.purchaseMedicinePaymentStatus', function (event) {
    let purchaseMedicineId = $(event.currentTarget).attr('data-id');
    updatePurchaseMedicinePaymentStatus(purchaseMedicineId);
});

window.updatePurchaseMedicinePaymentStatus = function (id) {
    $.ajax({
        url: $('#updatePaymentStatusUrl').val() + '/' + +id + '/pay-unpay',
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                livewire.emit('refresh');
            }
        },
    });
};

listenChange('.supplierStatus', function (event) {
    let supplierId = $(event.currentTarget).attr('data-id');
    updateSupplierStatus(supplierId);
});

window.updateSupplierStatus = function (id) {
    $.ajax({
        url: $('#indexSupplierUrl').val() + '/' + +id + '/active-deactive',
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                livewire.emit('refresh');
            }
        },
    });
};

listenChange('#supplier_filter_status', function () {
    window.livewire.emit('changeFilter', 'statusFilter', $(this).val())
});

listenClick('#supplierResetFilter', function () {
    $('#supplier_filter_status').val(0).trigger('change');
    hideDropdownManually($('#supplierFilterBtn'), $('.dropdown-menu'));
});
