listenClick(".delete-complaints-btn", function (event) {
    console.log('delete')
    console.log($("#Complaint").val())
    let complaint = $(event.currentTarget).attr("data-id");
    deleteItem(
        $("#indexComplaintsUrl").val() + "/" + complaint,
        "",
        $("#Complaint").val()
    );
});

