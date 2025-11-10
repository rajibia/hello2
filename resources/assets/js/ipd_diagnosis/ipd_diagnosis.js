document.addEventListener("turbo:load", loadIpdDiagnosisData);

function loadIpdDiagnosisData() {
    if (
        !$("#ipdDiagnosisReportDate").length &&
        !$("#editIpdDiagnosisReportDate").length
    ) {
        return;
    }
    $("#ipdDiagnosisReportDate, #editIpdDiagnosisReportDate").flatpickr({
        enableTime: true,
        defaultDate: new Date(),
        dateFormat: "Y-m-d H:i",
        minDate: $("#showIpdPatientCaseDate").val(),
        locale: $(".userCurrentLanguage").val(),
        widgetPositioning: {
            horizontal: "left",
            vertical: "bottom",
        },
    });
}

listen("click", ".ipdDignosis-delete-btn", function (event) {
    let id = $(event.currentTarget).attr("data-id");
    deleteItem(
        $("#showIpdDiagnosisUrl").val() + "/" + id,
        "",
        $("#ipdDiagnosisDelete").val()
    );
});

listenSubmit("#addIpdDiagnosisForm", function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find("#btnSave");
    loadingButton.button("loading");
    let data = {
        formSelector: $(this),
        url: $("#showIpdDiagnosisCreateUrl").val(),
        type: "POST",
    };
    newRecord(data, loadingButton, "#add_ipd_diagnosis_modal");
});

listen("click", ".ipdDignosis-edit-btn", function (event) {
    if ($(".ajaxCallIsRunning").val()) {
        return;
    }
    ajaxCallInProgress();
    let ipdDiagnosisId = $(event.currentTarget).attr("data-id");
    renderDataIpdDiagnosis(ipdDiagnosisId);
});

function renderDataIpdDiagnosis(id) {
    $.ajax({
        url: $("#showIpdDiagnosisUrl").val() + "/" + id + "/edit",
        type: "GET",
        success: function (result) {
            if (result.success) {
                let ext = result.data.ipd_diagnosis_document_url
                    .split(".")
                    .pop()
                    .toLowerCase();
                if (ext == "pdf") {
                    $("#editIpdDiagnosisPreviewImage").css(
                        "background-image",
                        'url("' + $(".pdfDocumentImageUrl").val() + '")'
                    );
                } else if (ext == "docx" || ext == "doc") {
                    $("#editIpdDiagnosisPreviewImage").css(
                        "background-image",
                        'url("' + $(".docxDocumentImageUrl").val() + '")'
                    );
                } else {
                    if (result.data.ipd_diagnosis_document_url != "") {
                        $("#editIpdDiagnosisPreviewImage").css(
                            "background-image",
                            'url("' +
                                result.data.ipd_diagnosis_document_url +
                                '")'
                        );
                    }
                }
                // Split the date and time from the result
                var dateTimeParts = result.data.report_date.split('T');
                var datePart = dateTimeParts[0]; // The date part (e.g., 2024-10-16)
                var timePart = dateTimeParts[1].split('.')[0]; // The time part without milliseconds (e.g., 19:00:00)

                // Combine the date and time
                var formattedDateTime = datePart + ' ' + timePart;

                $("#ipdDiagnosisId").val(result.data.id);
                $("#editIpdDiagnosisReportType").val(result.data.report_type);
                $("#editIpdDiagnosisReportBp").val(result.data.bp);
                $("#editIpdDiagnosisReportPulse").val(result.data.pulse);
                $("#editIpdDiagnosisReportRespiration").val(result.data.respiration);
                $("#editIpdDiagnosisReportTemperature").val(result.data.temperature);
                $("#editIpdDiagnosisReportOxygenSaturation").val(result.data.oxygen_saturation);
                document
                    .querySelector("#editIpdDiagnosisReportDate")
                    ._flatpickr.setDate(
                        moment(result.data.report_date).format()
                    );
                $("#editIpdDiagnosisName").val(result.data.name);
                $("#editIpdDiagnosisCode").val(result.data.code);
                $("#editIpdDiagnosisDescription").val(result.data.description);
                $("#editIpdDiagnosisReportDate").val(formattedDateTime);
                // $("#editIpdPatientDepartmentId").val(result.data.ipd_patient_department_id);

                if (result.data.ipd_diagnosis_document_url != "") {
                    $("#editIpdDiagnosisDocumentViewUrl").show();
                    $(".btn-view").show();
                    $("#editIpdDiagnosisDocumentViewUrl").attr(
                        "href",
                        result.data.ipd_diagnosis_document_url
                    );
                } else {
                    $("#editIpdDiagnosisDocumentViewUrl").hide();
                    $(".btn-view").hide();
                }
                $("#edit_ipd_diagnosis_modal").modal("show");
                ajaxCallCompleted();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
}

listen("click", ".ipdDignosis-view-btn", function (event) {
    if ($(".ajaxCallIsRunning").val()) {
        return;
    }
    ajaxCallInProgress();
    let ipdDiagnosisId = $(event.currentTarget).attr("data-id");
    renderDataIpdDiagnosisView(ipdDiagnosisId);
});

function renderDataIpdDiagnosisView(id) {
    $.ajax({
        url: $("#showIpdDiagnosisUrl").val() + "/" + id + "/edit",
        type: "GET",
        success: function (result) {
            if (result.success) {
                let ext = result.data.ipd_diagnosis_document_url
                    .split(".")
                    .pop()
                    .toLowerCase();
                if (ext == "pdf") {
                    $("#editIpdDiagnosisPreviewImage").css(
                        "background-image",
                        'url("' + $(".pdfDocumentImageUrl").val() + '")'
                    );
                } else if (ext == "docx" || ext == "doc") {
                    $("#editIpdDiagnosisPreviewImage").css(
                        "background-image",
                        'url("' + $(".docxDocumentImageUrl").val() + '")'
                    );
                } else {
                    if (result.data.ipd_diagnosis_document_url != "") {
                        $("#editIpdDiagnosisPreviewImage").css(
                            "background-image",
                            'url("' +
                                result.data.ipd_diagnosis_document_url +
                                '")'
                        );
                    }
                }
                if (result.data.report_type != "") {
                    $("#ipdDiagnosisReportTypeView").text(result.data.report_type);
                } else {
                    $("#ipdDiagnosisReportTypeView").text('N/A');
                }
                if (result.data.report_date !== "" && result.data.report_date !== null) {
                    let reportDate = new Date(result.data.report_date);
                
                    let timeString = reportDate.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
                    let dateString = reportDate.toLocaleDateString([], { day: 'numeric', month: 'short', year: 'numeric' });
                
                    $("#ipdDiagnosisReportDateView").html(`
                        <div class="badge bg-light-info">
                            <div class="mb-2">${timeString}</div>
                            <div>${dateString}</div>
                        </div>
                    `);
                } else {
                    $("#ipdDiagnosisReportDateView").text('N/A');
                }
                if (result.data.description != "" && result.data.description != null) {
                    $("#ipdDiagnosisDescriptionView").text(result.data.description);
                } else {
                    $("#ipdDiagnosisDescriptionView").text('N/A');
                }
               
                if (result.data.ipd_diagnosis_document_url != "" && result.data.ipd_diagnosis_document_url != null) {
                    $("#ipdDiagnosisDocumentView").attr(
                        "href",
                        result.data.ipd_diagnosis_document_url
                    );
                } 
                $("#show_ipd_diagnosis_modal").modal("show");
                ajaxCallCompleted();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
}


listenSubmit("#editIpdDiagnosisForm", function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find("#editIpdDiagnosisSave");
    loadingButton.button("loading");
    let id = $("#ipdDiagnosisId").val();
    let url = $("#showIpdDiagnosisUrl").val() + "/" + id;
    let data = {
        formSelector: $(this),
        url: url,
        type: "POST",
    };
    editRecord(data, loadingButton, "#edit_ipd_diagnosis_modal");
});

listenHiddenBsModal("#add_ipd_diagnosis_modal", function () {
    resetModalForm("#addIpdDiagnosisForm", "#ipdDiagnosisErrorsBox");
    $("#ipdDiagnosisPreviewImage").attr(
        "src",
        $("#showDefaultDocumentImageUrl").val()
    );
    $("#ipdDiagnosisPreviewImage").css(
        "background-image",
        'url("' + $("#showDefaultDocumentImageUrl").val() + '")'
    );
});

listenHiddenBsModal("#edit_ipd_diagnosis_modal", function () {
    resetModalForm("#editIpdDiagnosisForm", "#editIpdDiagnosisErrorsBox");
    $("#editIpdDiagnosisPreviewImage").attr(
        "src",
        $("#showDefaultDocumentImageUrl").val()
    );
    $("#editIpdDiagnosisPreviewImage").css(
        "background-image",
        'url("' + $("#showDefaultDocumentImageUrl").val() + '")'
    );
});

listenChange("#documentImage", function () {
    let extension = isValidIpdDiagnosisDocument(
        $(this),
        "#ipdDiagnosisErrorsBox"
    );
    if (!isEmpty(extension) && extension != false) {
        $("#ipdDiagnosisErrorsBox").html("").hide();
        displayDocument(this, "#ipdDiagnosisPreviewImage", extension);
    }
});

listenChange("#editDocumentImage", function () {
    let extension = isValidIpdDiagnosisDocument(
        $(this),
        "#editIpdDiagnosisErrorsBox"
    );
    if (!isEmpty(extension) && extension != false) {
        $("#editIpdDiagnosisErrorsBox").html("").hide();
        displayDocument(this, "#editIpdDiagnosisPreviewImage", extension);
    }
});

function isValidIpdDiagnosisDocument(inputSelector, validationMessageSelector) {
    let ext = $(inputSelector).val().split(".").pop().toLowerCase();
    if ($.inArray(ext, ["png", "jpg", "jpeg", "pdf", "doc", "docx"]) == -1) {
        $(inputSelector).val("");
        $(validationMessageSelector)
            .html(Lang.get("messages.incomes.document_error"))
            .show();
        return false;
    }
    return ext;
}

listen("click", ".removeIpdDiagnosisImage", function () {
    defaultImagePreview(".previewImage");
});

listen("click", ".removeIpdDiagnosisImageEdit", function () {
    defaultImagePreview(".previewImage");
});
