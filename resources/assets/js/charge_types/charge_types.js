'use strict';
 
listenClick('.charge-type-delete-btn', function (event) {
    let chargeTypeId = $(event.currentTarget).attr('data-id');
    deleteItem($('#chargeTypeURLID').val() + '/' + chargeTypeId,
        '',
        $('#chargeType').val());
});

document.addEventListener('success', function (data){
    displaySuccessMessage(data.detail)
})
