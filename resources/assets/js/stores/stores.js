document.addEventListener("turbo:load", loadStoreData);

function loadStoreData() {
    listenClick(".editStoreBtn", function (event) {
        if ($(".ajaxCallIsRunning").val()) {
            return;
        }
        ajaxCallInProgress();
        let storeId = $(event.currentTarget).attr("data-id");
        renderStoreData(storeId);
    });

    listenClick(".deleteStoreBtn", function (event) {
        let storeId = $(event.currentTarget).attr("data-id");
        deleteItem(
            $("#indexStoresUrl").val() + "/" + storeId,
            "",
            $("#localStore").val()
        );
    });

    function renderStoreData(id) {
        $.ajax({
            url: $("#indexStoresUrl").val() + "/" + id + "/edit",
            type: "GET",
            success: function (result) {
                if (result.success) {
                    let store = result.data;
                    $("#storeId").val(store.id);
                    $("#edit_name").val(store.name);
                    $("#editModal").modal("show");
                    ajaxCallCompleted();
                }
            },
            error: function (result) {
                manageAjaxErrors(result);
            },
        });
    }

    listenHiddenBsModal("#addModal", function () {
        resetModalForm("#addNewForm", "#validationErrorsBox");
    });

    listenHiddenBsModal("#editModal", function () {
        resetModalForm("#editForm", "#editValidationErrorsBox");
    });

    listenChange(".is-active", function (event) {
        let storeId = $(event.currentTarget).data("id");
        $.ajax({
            url: $("#indexStoresUrl").val() + "/" + storeId + "/status",
            method: "post",
            cache: false,
            success: function (result) {
                if (result.success) {
                    displaySuccessMessage(result.message);
                    Livewire.emit("refresh");
                }
            },
        });
    });
}

listenSubmit("#addNewForm", function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find("#btnSave");
    loadingButton.button("loading");
    $.ajax({
        url: $("#indexStoreCreateUrl").val(),
        type: "POST",
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#addModal").modal("hide");
                Livewire.emit("refresh");
            }
        },
        error: function (result) {
            printErrorMessage("#validationErrorsBox", result);
        },
        complete: function () {
            loadingButton.button("reset");
        },
    });
});

listenSubmit("#editForm", function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find("#btnEditSave");
    loadingButton.button("loading");
    var id = $("#storeId").val();
    $.ajax({
        url: $("#indexStoresUrl").val() + "/" + id,
        type: "put",
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#editModal").modal("hide");
                Livewire.emit("refresh");
            }
        },
        error: function (result) {
            UnprocessableInputError(result);
        },
        complete: function () {
            loadingButton.button("reset");
        },
    });
});
