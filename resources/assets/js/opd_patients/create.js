document.addEventListener("turbo:load", loadOpdPatientData);

function loadOpdPatientData() {
    if (
        !$("#createOpdPatientForm").length &&
        !$("#editOpdPatientDepartmentForm").length
    ) {
        return;
    }

    $(
        "#opdPatientId, #opdDoctorId,#opdPaymentMode,#editOpdPatientId, #editOpdDoctorId,#editOpdPaymentMode,#opdChargeId"
    ).select2({
        width: "100%",
    });

    $("#opdCaseId ,#editOpdCaseId").select2({
        width: "100%",
        placeholder:
            Lang.get("messages.common.choose") +
            " " +
            Lang.get("messages.case.case"),
    });

    let appointmentDateFlatPicker = $(
        "#opdAppointmentDate,#editOpdAppointmentDate "
    ).flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        locale: $(".userCurrentLanguage").val(),
    });

    if ($(".lastVisit").val()) {
        $("#opdPatientId,#editOpdPatientId")
            .val($(".lastVisit").val())
            .trigger("change");
        $("#opdPatientId,#editOpdPatientId").attr("disabled", true);
    }

    if ($(".isEdit").val()) {
        $("#opdPatientId,#editOpdPatientId").attr("disabled", true);
        $("#opdPatientId,#editOpdPatientId").trigger("change");
        appointmentDateFlatPicker.set(
            "minDate",
            $("#opdAppointmentDate,#editOpdAppointmentDate").val()
        );
    } else {
        appointmentDateFlatPicker.setDate(new Date());
        appointmentDateFlatPicker.set("minDate", new Date());
    }
}

listenSubmit(
    "#createOpdPatientForm, #editOpdPatientDepartmentForm",
    function () {
        $("#btnOpdSave,#btnEditOpdSave").attr("disabled", true);
        // let expectedAmount = $('#opdStandardCharge').val(); 
        // let paidAmount = $('#opdPaidAmount').val(); 

        // // console.log(paidAmount);

        // let change = parseFloat(paidAmount) - parseFloat(expectedAmount);

        // console.log(change);
        // if (change < 0) {
        //     change = 0;
        // }

        // $("#opdChange").val(change);
        // $("#changeText").text(change);

        // if (parseFloat(paidAmount) < parseFloat(expectedAmount)) {
        //     event.preventDefault();
        //     displayErrorMessage('Paid Amount must NOT be less than expected '+ expectedAmount);
        // } 
    }
);

listenChange("#opdPatientId,#editOpdPatientId", function () {
    if ($(this).val() !== "") {
        $.ajax({
            url: $(".opdPatientCasesUrl").val(),
            type: "get",
            dataType: "json",
            data: { id: $(this).val() },
            success: function (data) {
                if (data.data.length !== 0) {
                    $("#opdCaseId,#editOpdCaseId").empty();
                    $("#opdCaseId,#editOpdCaseId").removeAttr("disabled");
                    $.each(data.data, function (i, v) {
                        if ($(".patientCaseId").val() == v) {
                            $("#editOpdCaseId").append(
                                $("<option></option>")
                                    .attr("value", i)
                                    .attr("selected", true)
                                    .text(v)
                            );
                        } else {
                            $("#opdCaseId,#editOpdCaseId").append(
                                $("<option></option>").attr("value", i).text(v)
                            );
                        }
                    });
                } else {
                    $("#opdCaseId,#editOpdCaseId").prop("disabled", true);
                }
            },
        });
    }
    $("#opdCaseId,#editOpdCaseId").empty();
    $("#opdCaseId,#editOpdCaseId").prop("disabled", true);

    $("#opdCaseId ,#editOpdCaseId").select2({
        width: "100%",
        placeholder:
            Lang.get("messages.common.choose") +
            " " +
            Lang.get("messages.case.case"),
    });
});

// listenChange("#opdDoctorId,#editOpdDoctorId", function () {
//     if ($(this).val() !== "") {
//         $.ajax({
//             url: $(".doctorOpdChargeUrl").val(),
//             type: "get",
//             dataType: "json",
//             data: { id: $(this).val() },
//             success: function (data) {
//                 if (data.data.length !== 0) {
//                     $("#opdStandardCharge,#editOpdStandardCharge").val(
//                         data.data[0].standard_charge
//                     );
//                 } else {
//                     $("#opdStandardCharge,#editOpdStandardCharge").val(0);
//                 }
//                 calculateChange();
//             },
//         });
//     }
// });

listenChange("#opdChargeId,#editOpdChargeId", function () {
    if ($(this).val() !== "") {
        $.ajax({
            url: $(".chargeOpdChargeUrl").val(),
            type: "get",
            dataType: "json",
            data: { id: $(this).val() },
            success: function (data) {
                if (data.data.length !== 0) {
                    $("#opdStandardCharge,#editOpdStandardCharge").val(
                        data.data[0].standard_charge
                    );
                } else {
                    $("#opdStandardCharge,#editOpdStandardCharge").val(0);
                }
                // calculateChange();
            },
        });
    }
});

listenKeyup("#opdPaidAmount", function (e) {
    calculateChange();
});

const calculateChange = () => {
    let expectedAmount = $('#opdStandardCharge').val(); 
    let paidAmount = $('#opdPaidAmount').val(); 

    console.log("Expected Amount:", expectedAmount);
    console.log("Paid Amount:", paidAmount);

    if (isNaN(parseFloat(expectedAmount))) {
        expectedAmount = 0;
    }

    if (isNaN(parseFloat(paidAmount))) {
        paidAmount = 0;
    }

    let change = parseFloat(paidAmount) - parseFloat(expectedAmount);

    console.log("change:", change);

    if (parseFloat(change) < 0) {
        change = 0;
    }

    $("#opdChange").val(change.toFixed(2));
    $("#changeText").text(change.toFixed(2));

    
    if (parseFloat(paidAmount) < parseFloat(expectedAmount)) {
        // displayErrorMessage('Paid Amount must NOT be less than expected '+ expectedAmount);
        // $("#btnOpdSave,#btnEditOpdSave").attr("disabled", true);
        $("#createOpdPatientForm, #editOpdPatientDepartmentForm").attr("action", '#');
    } else {
        let initialSubmitUrl = $('#opdAddSubmitRoute').val();
        // $("#btnOpdSave,#btnEditOpdSave").attr("disabled", false);
        $("#createOpdPatientForm, #editOpdPatientDepartmentForm").attr("action", initialSubmitUrl);
    }
    
};
