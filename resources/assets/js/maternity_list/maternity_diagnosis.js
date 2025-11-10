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
