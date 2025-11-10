listenClick(".delete-vitals-btn", function (event) {
    let vitals = $(event.currentTarget).attr("data-id");
    deleteItem(
        $("#indexVitalsUrl").val() + "/" + vitals,
        "",
        $("#Vitals").val()
    );
});

