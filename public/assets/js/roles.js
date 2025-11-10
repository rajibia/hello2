'use strict';

listenSubmit('#addRoleForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#roleSave');
    loadingButton.button('loading');
    $.ajax({
        url: $('#operationCategoryCreateUrl').val(),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#add_role_modal').modal('hide');
                livewire.emit('refresh')
            }
        },
        error: function (result) {
            printErrorMessage('#operationCatErrorsBox', result);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenClick('.role-delete-btn', function (event) {
    let operationCategoryId = $(event.currentTarget).attr('data-id');
    deleteItem($('#roleUrl').val() + '/' + operationCategoryId, '', $('#role').val());
});

listenClick('.role-edit-btn', function (event) {
    let operationCategoryId = $(event.currentTarget).attr('data-id');	
    renderOperationCategoryData(operationCategoryId);
});

function renderOperationCategoryData(id) {
    $.ajax({
        url: $('#roleUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#editOperationCategoryIdText').val(result.data.id);
                $('#editOperationCatName').val(result.data.name);
                $('#edit_role_modal').modal('show');
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
}

listenSubmit('#editRoleForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#editRoleSave');
    loadingButton.button('loading');
    var id = $('#editOperationCategoryIdText').val();
	
    $.ajax({
        url: $('#roleUrl').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                $('#edit_role_modal').modal('hide')
                if ($('#roleUrl').length) {
                    window.location.href = $('#roleUrl').val()
                } else {
                    livewire.emit('refresh')
                }

            }
        },
        error: function (result) {
            UnprocessableInputError(result);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenHiddenBsModal('#add_role_modal', function () {
    resetModalForm('#addRoleForm', '#operationCatErrorsBox');
    $('#operationCategoryId').val('').trigger('change.select2');
});

listenHiddenBsModal('#edit_role_modal', function () {
    resetModalForm('#editOperationCatForm', '#editOperationCatErrorsBox');
});
