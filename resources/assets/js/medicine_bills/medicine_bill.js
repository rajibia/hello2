document.addEventListener("turbo:load", loadSaleMedicineCreate);
let uniquePrescriptionId = "";
Lang.setLocale($(".userCurrentLanguage").val());
function loadSaleMedicineCreate() {
    console.log('loadSaleMedicineCreate called');

    // Check if we're on a medicine bills page
    const isMedicineBillPage = $("#medicineUniqueId").length > 0 ||
                              $(".medicine_bill_date").length > 0 ||
                              $(".medicineBillCategoriesId").length > 0;

    if (!isMedicineBillPage) {
        console.log('Not on medicine bills page, skipping initialization');
        return;
    }

    console.log('Initializing medicine bills functionality...');

    $(".medicine_bill_date").flatpickr({
        enableTime: true,
        defaultDate: new Date(),
        dateFormat: "Y-m-d H:i",
    });

    $(".edit_medicine_bill_date").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
    });
    $(".medicineBillExpiryDate").flatpickr({
        minDate: new Date(),
        dateFormat: "Y-m-d",
    });

    $(".medicine-payment-mode").select2({
        width: "100%",
    });

    // Initialize Select2 for medicine categories with delay to ensure DOM is ready
    setTimeout(function() {
        $(".medicineBillCategoriesId").each(function() {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                $(this).select2({
                    width: "100%",
                });
                console.log('Select2 initialized for category dropdown');
            }
        });

        $(".medicinePurchaseId, .purchaseMedicineId").each(function() {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                $(this).select2({
                    width: "100%",
                });
                console.log('Select2 initialized for medicine dropdown');
            }
        });
    }, 500);

    console.log('Medicine bills functionality initialized');
}

listenChange(".medicineBillCategoriesId", function () {
    console.log('Category change detected:', $(this).val());
    let categoryId = $(this).val();

    let currentRow = $(this).closest("tr");
    let medicineId = currentRow.find(".medicinePurchaseId, .purchaseMedicineId");
    let availableQTY = currentRow.find(".available_qty");

    console.log('Current row:', currentRow.length);
    console.log('Medicine dropdown found:', medicineId.length);
    console.log('Available QTY span found:', availableQTY.length);

    $(availableQTY).text(0);

    if (categoryId == "") {
        console.log('Empty category, clearing medicine dropdown');
        $(medicineId).find("option").remove();
        $(medicineId).append(
            $("<option></option>")
                .attr("value", "")
                .text(Lang.get("messages.medicine_bills.select_medicine"))
        );

        // Refresh Select2 if initialized
        if ($(medicineId).hasClass('select2-hidden-accessible')) {
            $(medicineId).select2('destroy').select2({
                width: "100%",
            });
        }

        return false;
    }

    console.log('Making AJAX call for category:', categoryId);
    $.ajax({
        type: "get",
        url: route("get-medicine-category", categoryId),
        beforeSend: function() {
            console.log('AJAX request starting...');
        },
        success: function (result) {
            console.log('AJAX success:', result);
            let array = result.data.medicine;
            console.log('Medicines received:', array);
            console.log('Number of medicines:', Object.keys(array).length);

            $(medicineId).find("option").remove();
            $(medicineId).attr("required", true);
            $(medicineId).append(
                $(
                    '<option value="">' +
                        Lang.get("messages.medicine_bills.select_medicine") +
                        "</option>"
                )
            );
            $.each(array, function (key, value) {
                console.log('Adding medicine option:', key, value);
                $(medicineId).append(
                    $("<option></option>").attr("value", key).text(value)
                );
            });

            console.log('Medicine dropdown updated. Total options:', $(medicineId).find("option").length);

            // Refresh Select2 if initialized
            if ($(medicineId).hasClass('select2-hidden-accessible')) {
                console.log('Refreshing Select2 for medicine dropdown');
                $(medicineId).select2('destroy').select2({
                    width: "100%",
                });
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX error:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);
        }
    });
});

listenChange(".medicinePurchaseId, .purchaseMedicineId", function () {
    console.log('Medicine selection change detected:', $(this).val());
    var currentRow = $(this).closest("tr");
    let medicineId = $(this).val();
    let uniqueId = $(this).attr("data-id");
    let salePriceField = currentRow.find(".medicineBill-sale-price");
    let availableQtySpan = currentRow.find(".available_qty");

    console.log('Medicine ID:', medicineId);
    console.log('Unique ID:', uniqueId);
    console.log('Sale price field found:', salePriceField.length);
    console.log('Available QTY span found:', availableQtySpan.length);

    if (
        medicineId == "" ||
        medicineId == Lang.get("messages.medicine_bills.select_medicine")
    ) {
        console.log('Empty medicine selected, resetting fields');
        $(salePriceField).val("0.00");
        $(availableQtySpan).text(0);
        return false;
    }

    console.log('Making AJAX call for medicine:', medicineId);
    $.ajax({
        type: "get",
        url: route("get-medicine", medicineId),
        beforeSend: function() {
            console.log('Medicine AJAX request starting...');
        },
        success: function (result) {
            console.log('Medicine AJAX success:', result);
            $(salePriceField).val(result.data.selling_price.toFixed(2));
            $(availableQtySpan).text(result.data.available_quantity);

            console.log('Set sale price:', result.data.selling_price);
            console.log('Set available quantity:', result.data.available_quantity);

            let currentqty = currentRow.find(".medicineBill-quantity").val();
            let price = currentRow.find(".medicineBill-sale-price").val();
            let currentamount = parseFloat(price * currentqty);

            currentRow
                .find(".medicine-bill-amount")
                .val(currentamount.toFixed(2));
            let taxEle = $(".medicineBill-tax");
            let elements = $(".medicine-bill-amount");
            let total = 0.0;
            let totalTax = 0;
            let netAmount = 0;
            let discount = 0;
            let amount = 0;
            for (let i = 0; i < elements.length; i++) {
                total += parseFloat(elements[i].value);
                discount = $(".medicineBill-discount").val();
                if (taxEle[i].value != 0 && taxEle[i].value != "") {
                    totalTax += (elements[i].value * taxEle[i].value) / 100;
                } else {
                    amount += parseFloat(elements[i].value);
                }
            }
            discount = discount == "" ? 0 : discount;
            netAmount = parseFloat(total) + parseFloat(totalTax);
            netAmount = parseFloat(netAmount) - parseFloat(discount);
            if (discount > total && $(this).hasClass("medicineBill-discount")) {
                discount = discount.slice(0, -1);
                displayErrorMessage(
                    Lang.get("messages.medicine_bills.validate_discount")
                );
                $("#discountAmount").val(discount);
                return false;
            }
            if (discount > total) {
                netAmount = 0;
            }
            $("#total").val(total.toFixed(2));
            $("#medicineTotalTaxId").val(totalTax.toFixed(2));
            $("#netAmount").val(netAmount.toFixed(2));
        },
        error: function (xhr, status, error) {
            console.error('Medicine AJAX error:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);
        }
    });
});

listenClick(".add-medicine-btn-medicine-bill", function () {
    // Check if we're on the edit page
    if (window.location.pathname.includes('/edit')) {
        console.log('Add medicine button clicked on edit page - action blocked');
        return false;
    }

    console.log('Add medicine button clicked');
    uniquePrescriptionId = $("#medicineUniqueId").val();
    console.log('Current unique ID:', uniquePrescriptionId);

    let data = {
        medicinesCategories: JSON.parse(
            $("#showMedicineCategoriesMedicineBill").val()
        ),
        medicines: JSON.parse($(".associatePurchaseMedicines").val()),
        uniqueId: uniquePrescriptionId,
    };

    console.log('Template data:', data);

    let prescriptionMedicineHtml = prepareTemplateRender(
        "#medicineBillTemplate",
        data
    );

    console.log('Generated HTML:', prescriptionMedicineHtml);

    $(".medicine-bill-container").append(prescriptionMedicineHtml);

    // Initialize Select2 for the newly added elements
    console.log('Initializing Select2 for new elements...');
    dropdownToSelecte2(".medicinePurchaseId");
    dropdownToSelecteCategories2(".medicineBillCategoriesId"); // Fixed class name
    expiryDateFlatePicker(".medicineBillCategoriesId"); // Fixed class name

    $(".purchaseMedicineExpiryDate").flatpickr({
        minDate: new Date(),
        dateFormat: "Y-m-d",
    });

    uniquePrescriptionId++;
    $("#medicineUniqueId").val(uniquePrescriptionId);

    console.log('New row added successfully. Next unique ID:', uniquePrescriptionId);
});
const dropdownToSelecte2 = (selector) => {
    $(selector).select2({
        placeholder: Lang.get("messages.medicine_bills.select_medicine"),
        width: "100%",
    });
};
const dropdownToSelecteCategories2 = (selector) => {
    $(selector).select2({
        placeholder: Lang.get("messages.medicine.select_category"),
        width: "100%",
    });
};
const expiryDateFlatePicker = (selector) => {
    $(".medicineBillExpiryDate").flatpickr({
        minDate: new Date(),
        dateFormat: "Y-m-d",
    });
};
listenKeyup(
    ".medicineBill-quantity,.medicineBill-price,.medicineBill-tax,.medicineBill-discount,.medicineBill-sale-price",
    function () {
        let value = $(this).val();
        $(this).val(value.replace(/[^0-9\.]/g, ""));
        var currentRow = $(this).closest("tr");
        let currentqty = currentRow.find(".medicineBill-quantity").val();
        let price = currentRow.find(".medicineBill-sale-price").val();
        let currentamount = parseFloat(price * currentqty);
        currentRow.find(".medicine-bill-amount").val(currentamount.toFixed(2));

        let taxEle = $(".medicineBill-tax");
        let elements = $(".medicine-bill-amount");
        let total = 0.0;
        let totalTax = 0;
        let netAmount = 0;
        let discount = 0;
        let amount = 0;
        var qty = $(".medicineBill-quantity");
        var PreviousQty = $(".previous-quantity");
        for (let i = 0; i < elements.length; i++) {
            total += parseFloat(elements[i].value);
            discount = $(".medicineBill-discount").val();
            if ($("#medicineBillStatus").val() == 1) {
                if (parseInt(qty[i].value) > parseInt(PreviousQty[i].value)) {
                    let qtyRollback = qty[i].value.slice(0, -1);
                    currentRow.find(".medicineBill-quantity").val(qtyRollback);
                    currentqty = currentRow
                        .find(".medicineBill-quantity")
                        .val();
                    price = currentRow.find(".medicineBill-sale-price").val();
                    currentamount = parseFloat(price * currentqty);
                    currentRow
                        .find(".medicine-bill-amount")
                        .val(currentamount.toFixed(2));
                    displayErrorMessage(
                        Lang.get("messages.medicine_bills.update_quantity")
                    );
                    return false;
                }
            }
            if (taxEle[i].value != 0 && taxEle[i].value != "") {
                if (taxEle[i].value > 99) {
                    let taxAmount = taxEle[i].value.slice(0, -1);
                    currentRow.find(".medicineBill-tax").val(taxAmount);
                    displayErrorMessage(
                        Lang.get("messages.medicine_bills.validate_tax")
                    );
                    $("#discountAmount").val(discount);
                    return false;
                }
                totalTax += (elements[i].value * taxEle[i].value) / 100;
            } else {
                amount += parseFloat(elements[i].value);
            }
        }
        discount = discount == "" ? 0 : discount;
        netAmount = parseFloat(total) + parseFloat(totalTax);
        netAmount = parseFloat(netAmount) - parseFloat(discount);
        if (discount > total && $(this).hasClass("medicineBill-discount")) {
            discount = discount.slice(0, -1);
            displayErrorMessage(
                Lang.get("messages.medicine_bills.validate_discount")
            );
            $("#discountAmount").val(discount);
            return false;
        }
        if (discount > total) {
            netAmount = 0;
        }
        $("#total").val(total.toFixed(2));
        $("#medicineTotalTaxId").val(totalTax.toFixed(2));
        $("#netAmount").val(netAmount.toFixed(2));
    }
);

listenSubmit("#CreateMedicineBillForm", function (e) {
    e.preventDefault();
    let netAmount = "#netAmount";

    if ($("#total").val() < $("#discountAmount").val()) {
        displayErrorMessage(
            Lang.get("messages.medicine_bills.validate_discount")
        );
        return false;
    } else if ($(netAmount).val() == null || $(netAmount).val() == "") {
        displayErrorMessage(
            Lang.get("messages.medicine_bills.net_amount_not_empty")
        );
        return false;
    } else if ($(netAmount).val() == 0) {
        displayErrorMessage(
            Lang.get("messages.medicine_bills.net_amount_not_zero")
        );
        return false;
    } else if (
        $(".medicineBill-quantity").val() == 0 ||
        $(".medicineBill-quantity").val() == null ||
        $(".medicineBill-quantity").val() == ""
    ) {
        displayErrorMessage(
            Lang.get("messages.medicine_bills.quantity_cannot_be_zero")
        );
        return false;
    }

    $(this)[0].submit();
});

listenClick(".add-patient-modal", function () {
    $("#addPatientModal").appendTo("body").modal("show");
});

listenSubmit("#addPatientForm", function (e) {
    e.preventDefault();
    processingBtn("#addPatientForm", "#patientBtnSave", "loading");
    $("#patientBtnSave").attr("disabled", true);
    $.ajax({
        url: route("store.patient"),
        type: "POST",
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                $("#prescriptionPatientId").find("option").remove();
                $("#prescriptionPatientId").append(
                    $("<option></option>")
                        .attr("placeholder", "")
                        .text(Lang.get("messages.document.select_patient"))
                );
                $.each(result.data, function (i, v) {
                    $("#prescriptionPatientId").append(
                        $("<option></option>").attr("value", i).text(v)
                    );
                });
                displaySuccessMessage(result.message);
                $("#addPatientModal").modal("hide");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            $("#patientBtnSave").attr("disabled", false);
            processingBtn("#addPatientForm", "#patientBtnSave");
        },
    });
});

listenHiddenBsModal("#addPatientModal", function () {
    resetModalForm("#addPatientForm", "#patientErrorsBox");
});

listenClick(".medicine-bill-delete-btn", function (event) {
    let id = $(event.currentTarget).attr("data-id");

    deleteItem(
        route("medicine-bills.destroy", id),
        "",
        Lang.get("messages.medicine_bills.medicine_bill")
    );
});

listenSubmit("#MedicinebillForm", function (e) {
    e.preventDefault();

    let netAmount = "#netAmount";
    if (
        parseFloat($("#total").val()) < parseFloat($("#discountAmount").val())
    ) {
        displayErrorMessage(
            Lang.get("messages.medicine_bills.validate_discount")
        );
        return false;
    } else if ($(netAmount).val() == null || $(netAmount).val() == "") {
        displayErrorMessage(
            Lang.get("messages.medicine_bills.net_amount_not_empty")
        );
        return false;
    } else if ($(netAmount).val() == 0) {
        displayErrorMessage(
            Lang.get("messages.medicine_bills.net_amount_not_zero")
        );
        return false;
    } else if (
        $(".medicineBill-quantity").val() == 0 ||
        $(".medicineBill-quantity").val() == null ||
        $(".medicineBill-quantity").val() == ""
    ) {
        displayErrorMessage(
            Lang.get("messages.medicine_bills.quantity_cannot_be_zero")
        );
        return false;
    }
    $medicineBillId = $("#medicineBillId").val();
    $.ajax({
        url: route("medicine-bills.update", $medicineBillId),
        type: "post",
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                setTimeout(function () {
                    Turbo.visit(route("medicine-bills.index")); // true
                }, 2000);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});


listenKeyup("#medBillPaidAmount", function (e) {
    calculateChangeBill();
});

const calculateChangeBill = () => {
    let expectedAmount = $("#netAmount").val();
    let paidAmount = $('#medBillPaidAmount').val();
    let initialChange = $('#medBillChange').val(); // Use a different variable to store the initial value

    if (isNaN(parseFloat(expectedAmount))) {
        expectedAmount = 0;
    }

    if (isNaN(parseFloat(paidAmount))) {
        paidAmount = 0;
    }

    let change = parseFloat(paidAmount) - parseFloat(expectedAmount);

    if (parseFloat(change) < 0) {
        change = 0;
    }

    $("#medBillChange").val(change.toFixed(2));
    $("#medBillChangeText").text(change.toFixed(2));


};


listenSubmit("#AccountMedicinebillForm", function (e) {
    e.preventDefault();

    let netAmount = "#netAmount";
    let paidAmount = $('#medBillPaidAmount').val();
    let changeAmount = $('#medBillChange').val();
    let paymentStatus = $('#medicineBillPaymentStatus').val();
    console.log(paymentStatus);
    console.log(paidAmount);
    console.log($(netAmount).val());
    if (
        parseFloat($("#total").val()) < parseFloat($("#discountAmount").val())
    ) {
        displayErrorMessage(
            Lang.get("messages.medicine_bills.validate_discount")
        );
        return false;
    } else if ($(netAmount).val() == null || $(netAmount).val() == "") {
        displayErrorMessage(
            Lang.get("messages.medicine_bills.net_amount_not_empty")
        );
        return false;
    } else if ($(netAmount).val() == 0) {
        displayErrorMessage(
            Lang.get("messages.medicine_bills.net_amount_not_zero")
        );
        return false;
    } else if (paymentStatus && paidAmount == 0) {
        displayErrorMessage(
            'Paid Amount Cannot be Zero'
        );
        return false;
    } else if (paymentStatus && (parseFloat(paidAmount) < parseFloat($(netAmount).val()))) {
        displayErrorMessage(
            'Paid Amount should be greater than or equal to total net'
        );
        return false;
    } else if (
        $(".medicineBill-quantity").val() == 0 ||
        $(".medicineBill-quantity").val() == null ||
        $(".medicineBill-quantity").val() == ""
    ) {
        displayErrorMessage(
            Lang.get("messages.medicine_bills.quantity_cannot_be_zero")
        );
        return false;
    }
    $medicineBillId = $("#medicineBillId").val();
    $.ajax({
        url: route("accounts-medicine-bills.update", $medicineBillId),
        type: "post",
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                setTimeout(function () {
                    Turbo.visit(route("accounts-medicine-bills.index")); // true
                }, 2000);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenClick(".delete-medicine-bill-item", function () {
    let currentRow = $(this).closest("tr");
    let currentRowAmount = currentRow.find(".medicine-bill-amount").val();
    let currentRowTax = currentRow.find(".medicineBill-tax").val();
    let currentTaxAmount =
        parseFloat(currentRowAmount) * parseFloat(currentRowTax / 100);
    let updatedTax =
        parseFloat($("#medicineTotalTaxId").val()) -
        parseFloat(currentTaxAmount);

    $("#medicineTotalTaxId").val(updatedTax.toFixed(2));
    let updatedTotalAmount =
        parseFloat($("#total").val()) - parseFloat(currentRowAmount);
    $("#total").val(updatedTotalAmount.toFixed(2));
    let amountSubfromNetAmt =
        parseFloat(currentTaxAmount) + parseFloat(currentRowAmount);

    let updateNetAmount =
        parseFloat($("#netAmount").val()) - parseFloat(amountSubfromNetAmt);
    $("#netAmount").val(updateNetAmount.toFixed(2));

    $(this).parents("tr").remove();
});
