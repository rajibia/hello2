'use strict';

listenSubmit('#addPermissionForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#permissionSave');
    loadingButton.button('loading');
    $.ajax({
        url: $('#permissionCreateUrl').val(),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#add_permission_modal').modal('hide');
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

listenClick('.permission-delete-btn', function (event) {
    let permissionId = $(event.currentTarget).attr('data-id');
    deleteItem($('#permissionUrl').val() + '/' + permissionId, '', $('#permission').val());
});

listenClick('.permission-edit-btn', function (event) {
    let permissionId = $(event.currentTarget).attr('data-id');		
    renderOperationCategoryData(permissionId);
});

function renderOperationCategoryData(id) {
    $.ajax({
        url: $('#permissionUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#editPermissionIdText').val(result.data.id);
                $('#editPermissionName').val(result.data.name);
				
				$('#edit_perm_add').prop('checked', !!result.data.add);
				$('#edit_perm_edit').prop('checked', !!result.data.edit);
				$('#edit_perm_delete').prop('checked', !!result.data.delete);
				$('#edit_perm_view').prop('checked', !!result.data.view);
				
                $('#edit_permission_modal').modal('show');
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
}

listenSubmit('#editPermissionForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#editPermissionSave');
    loadingButton.button('loading');
    var id = $('#editPermissionIdText').val();
	
    $.ajax({
        url: $('#permissionUrl').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                $('#edit_permission_modal').modal('hide')
                if ($('#permissionUrl').length) {
                    window.location.href = $('#permissionUrl').val()
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

listenHiddenBsModal('#add_permission_modal', function () {
    resetModalForm('#addRoleForm', '#operationCatErrorsBox');
    $('#permissionId').val('').trigger('change.select2');
});

listenHiddenBsModal('#edit_permission_modal', function () {
    resetModalForm('#editOperationCatForm', '#editOperationCatErrorsBox');
});