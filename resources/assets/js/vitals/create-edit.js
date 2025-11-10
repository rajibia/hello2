document.addEventListener("turbo:load", loadVitalsCreate);


function loadVitalsCreate() {
    if (
        !$("#vitalsPatientId").length &&
        !$("#vitalsOPDId").length &&
        !$("#editVitalsPatientId").length &&
        !$("#editVitalsOPDId").length

    ) {
        return;
    }
    
    $(
        "#vitalsPatientId,#vitalsOPDId,#editVitalsPatientId,#editVitalsOPDId"
    ).select2({
        width: "100%",
    });

    $("#vitalsPatientId").first().focus();
}
listenSubmit("#createVitals, #editVitals", function () {
    $(".btnVitalsSave").attr("disabled", true);
});
