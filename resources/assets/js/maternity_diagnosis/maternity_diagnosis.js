import moment from "moment";

document.addEventListener("turbo:load", loadMaternityDiagnosisData);

function loadMaternityDiagnosisData() {
    if (
        !$("#maternityDiagnosisReportDate").length &&
        !$("#editMaternityDiagnosisReportDate").length
    ) {
        return;
    }
    $("#maternityDiagnosisReportDate, #editMaternityDiagnosisReportDate").flatpickr({
        enableTime: true,
        defaultDate: new Date(),
        dateFormat: "Y-m-d H:i",
        useCurrent: true,
        sideBySide: true,
        minDate: moment($("#showMaternityAppointmentDate").val()).format(
            "YYYY-MM-DD"
        ),
        locale: $(".userCurrentLanguage").val(),
        widgetPositioning: {
            horizontal: "left",
            vertical: "bottom",
        },
    });
}

listenClick(".deleteMaternityDiagnosisBtn", function (event) {
    let id = $(event.currentTarget).attr("data-id");
    deleteItem(
        $("#showMaternityDiagnosisUrl").val() + "/" + id,
        null,
        $("#maternityDiagnosisDeleteBtn").val()
    );
});

listenSubmit("#addMaternityDiagnosisForm", function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find("#btnMaternityDiagnosisSave");
    loadingButton.button("loading");
    let data = {
        formSelector: $(this),
        url: $("#showMaternityDiagnosisCreateUrl").val(),
        type: "POST",
    };
    newRecord(data, loadingButton, "#add_maternity_diagnoses_modal");
    loadingButton.attr("disabled", false);
});

listenClick(".editMaternityDiagnosisBtn", function (event) {
    if ($(".ajaxCallIsRunning").val()) {
        return;
    }
    ajaxCallInProgress();
    let maternityDiagnosisId = $(event.currentTarget).attr("data-id");
    renderMaternityDiagnosisData(maternityDiagnosisId);
});

window.renderMaternityDiagnosisData = function (id) {
    $.ajax({
        url: $("#showMaternityDiagnosisUrl").val() + "/" + id + "/edit",
        type: "GET",
        success: function (result) {
            if (result.success) {
                let ext = result.data.maternity_diagnosis_document_url
                    .split(".")
                    .pop()
                    .toLowerCase();
                if (ext == "pdf") {
                    $("#editMaternityDiagnosisPreviewImage").css(
                        "background-image",
                        'url("' + $(".pdfDocumentImageUrl").val() + '")'
                    );
                } else if (ext == "docx" || ext == "doc") {
                    $("#editMaternityDiagnosisPreviewImage").css(
                        "background-image",
                        'url("' + $(".docxDocumentImageUrl").val() + '")'
                    );
                } else {
                    if (result.data.maternity_diagnosis_document_url != "") {
                        $("#editMaternityDiagnosisPreviewImage").css(
                            "background-image",
                            'url("' +
                                result.data.maternity_diagnosis_document_url +
                                '")'
                        );
                    }
                }
                $("#maternityDiagnosisId").val(result.data.id);
                $("#editMaternityDiagnosisReportType").val(result.data.report_type);
                $("#editMaternityDiagnosisReportBp").val(result.data.bp);
                $("#editMaternityDiagnosisReportPulse").val(result.data.pulse);
                $("#editMaternityDiagnosisReportRespiration").val(result.data.respiration);
                $("#editMaternityDiagnosisReportTemperature").val(result.data.temperature);
                $("#editMaternityDiagnosisReportOxygenSaturation").val(result.data.oxygen_saturation);
                document
                    .querySelector("#editMaternityDiagnosisReportDate")
                    ._flatpickr.setDate(
                        moment(result.data.report_date).format()
                    );
                $("#editMaternityDiagnosisDescription").val(result.data.description);
                if (result.data.maternity_diagnosis_document_url != "") {
                    $("#editMaternityDiagnosisDocumentViewUrl").show();
                    $(".btn-view").show();
                    $("#editMaternityDiagnosisDocumentViewUrl").attr(
                        "href",
                        result.data.maternity_diagnosis_document_url
                    );
                } else {
                    $("#editMaternityDiagnosisDocumentViewUrl").hide();
                    $(".btn-view").hide();
                }
                $("#edit_maternity_diagnoses_modal").modal("show");
                ajaxCallCompleted();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
};

listenSubmit("#editMaternityDiagnosisForm", function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find("#btnEditMaternityDiagnosisSave");
    loadingButton.button("loading");
    let id = $("#maternityDiagnosisId").val();
    let url = $("#showMaternityDiagnosisUrl").val() + "/" + id;
    let data = {
        formSelector: $(this),
        url: url,
        type: "POST",
        tableSelector: null,
    };
    editRecord(data, loadingButton, "#edit_maternity_diagnoses_modal");
});
