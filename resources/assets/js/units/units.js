document.addEventListener("turbo:load", loadUnitData);

function loadUnitData() {
    listenClick(".editUnitBtn", function (event) {
        if ($(".ajaxCallIsRunning").val()) {
            return;
        }
        ajaxCallInProgress();
        let unitId = $(event.currentTarget).attr("data-id");
        renderUnitData(unitId);
    });

    listenClick(".deleteUnitBtn", function (event) {
        let unitId = $(event.currentTarget).attr("data-id");
        deleteItem(
            $("#indexUnitsUrl").val() + "/" + unitId,
            "",
            $("#localUnit").val()
        );
    });

    function renderUnitData(id) {
        $.ajax({
            url: $("#indexUnitsUrl").val() + "/" + id + "/edit",
            type: "GET",
            success: function (result) {
                if (result.success) {
                    let unit = result.data;
                    $("#unitId").val(unit.id);
                    $("#editName").val(unit.name);
                    $("#edit_units_modal").modal("show");
                    ajaxCallCompleted();
                }
            },
            error: function (result) {
                manageAjaxErrors(result);
            },
        });
    }

    listenHiddenBsModal("#add_units_modal", function () {
        resetModalForm("#addNewForm", "#validationErrorsBox");
    });

    listenHiddenBsModal("#edit_units_modal", function () {
        resetModalForm("#editForm", "#editValidationErrorsBox");
    });

    listenChange(".is-active", function (event) {
        let unitId = $(event.currentTarget).data("id");
        $.ajax({
            url: $("#indexUnitsUrl").val() + "/" + unitId + "/status",
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

listenSubmit("#addNewUnitForm", function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find("#btnSave");
    loadingButton.button("loading");
    $.ajax({
        url: $("#indexUnitCreateUrl").val(),
        type: "POST",
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#add_units_modal").modal("hide");
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

listenSubmit("#editUnitForm", function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find("#btnEditSave");
    loadingButton.button("loading");
    var id = $("#unitId").val();
    $.ajax({
        url: $("#indexUnitsUrl").val() + "/" + id,
        type: "put",
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#edit_units_modal").modal("hide");
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
