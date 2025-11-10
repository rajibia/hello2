document.addEventListener("turbo:load", loadUpdateInvoiceData);

function loadUpdateInvoiceData() {
    if (!$(".invoiceSaveUrl")) {
        return;
    }

    $('input:text:not([readonly="readonly"])').first().blur();

    $("#invoicePatientId").focus();

    $(".chargeId").select2({
        width: "100%",
    });

    $("#invoice_date").flatpickr({
        defaultDate: new Date(),
        dateFormat: "Y-m-d",
        locale: $(".userCurrentLanguage").val(),
    });

    $("#editInvoiceDate").flatpickr({
        dateFormat: "Y-m-d",
        locale: $(".userCurrentLanguage").val(),
    });

    window.isNumberKey = (evt, element) => {
        let charCode = evt.which ? evt.which : event.keyCode;

        return !(
            (charCode !== 46 || $(element).val().indexOf(".") !== -1) &&
            (charCode < 48 || charCode > 57)
        );
    };

    listenClick(".deleteInvoiceItem", function () {
        $(this).parents("tr").remove();
        resetInvoiceItemIndex();
        calculateAndSetInvoiceAmount();
    });

    listenKeyup(".qty", function () {
        let qty = parseInt($(this).val());
        let rate = $(this).parent().siblings().find(".price").val();
        rate = parseInt(removeCommas(rate));
        let amount = calculateAmount(qty, rate);
        $(this).parent().siblings(".amount").text(addCommas(amount.toString()));
        calculateAndSetInvoiceAmount();
    });

    listenKeyup(".price", function () {
        let rate = $(this).val();
        rate = parseInt(removeCommas(rate));
        let qty = parseInt($(this).parent().siblings().find(".qty").val());
        let amount = calculateAmount(qty, rate);
        $(this).parent().siblings(".amount").text(addCommas(amount.toString()));
        calculateAndSetInvoiceAmount();
    });

    const calculateAmount = (qty, rate) => {
        if (qty > 0 && rate > 0) {
            return qty * rate;
        } else {
            return 0;
        }
    };

    const calculateAndSetInvoiceAmount = () => {
        let totalAmount = 0;
        $(".invoice-item-container>tr").each(function () {
            let itemTotal = $(this).find(".item-total").text();
            itemTotal = removeCommas(itemTotal);
            itemTotal = isEmpty($.trim(itemTotal)) ? 0 : parseInt(itemTotal);
            totalAmount += itemTotal;
        });
        totalAmount = parseFloat(totalAmount);

        $("#total").text(addCommas(totalAmount.toFixed(2)));

        //set hidden input value
        $("#total_amount").val(totalAmount);

        calculateDiscount();
    };

    const calculateDiscount = () => {
        let discount = $("#discount").val();
        let totalAmount = removeCommas($("#total").text());

        if (isEmpty(discount) || isEmpty(totalAmount)) {
            discount = 0;
        }

        let discountAmount = (totalAmount * discount) / 100;
        let finalAmount = totalAmount - discountAmount;

        $("#finalAmount").text(addCommas(finalAmount.toFixed(2)));
        $("#total_amount").val(finalAmount.toFixed(2));
        $("#discountAmount").text(addCommas(discountAmount.toFixed(2)));
    };

    listenKeyup("#discount", function (e) {
        calculateDiscount();
    });

    listenChange(".chargeId", function () {
        let allCharges = JSON.parse($(".allCharges").val());
        console.log(allCharges);
        // Get the selected charge ID
        var selectedChargeId = $(this).val();

        console.log(selectedChargeId);

        // Find the corresponding charge in the allCharges array
        var selectedCharge = allCharges.find(function (charge) {
            return charge.id == selectedChargeId;
        });

        // Update the price field for the selected charge
        if (selectedCharge) {
            // Assuming the price input field has a specific class like '.price'
            $(this).closest("tr").find(".price").val(selectedCharge.standard_charge);
        }

        let qty = $(this).parent().siblings().find(".qty").val();
        let rate = $(this).parent().siblings().find(".price").val();
        rate = parseInt(removeCommas(rate));
        let amount = calculateAmount(qty, rate);
        $(this).parent().siblings(".amount").text(addCommas(amount.toString()));
        calculateAndSetInvoiceAmount();
    });
    // $(".chargeId").on("change", function () {
    //     let allCharges = JSON.parse($(".allCharges").val());
    //     console.log(allCharges);
    //     // Get the selected charge ID
    //     var selectedChargeId = $(this).val();

    //     console.log(selectedChargeId);

    //     // Find the corresponding charge in the allCharges array
    //     var selectedCharge = allCharges.find(function (charge) {
    //         return charge.id == selectedChargeId;
    //     });

    //     // Update the price field for the selected charge
    //     if (selectedCharge) {
    //         // Assuming the price input field has a specific class like '.price'
    //         $(this).closest("tr").find(".price").val(selectedCharge.standard_charge);
    //     }

    //     let qty = $(this).parent().siblings().find(".qty").val();
    //     let rate = $(this).parent().siblings().find(".price").val();
    //     rate = parseInt(removeCommas(rate));
    //     let amount = calculateAmount(qty, rate);
    //     $(this).parent().siblings(".amount").text(addCommas(amount.toString()));
    //     calculateAndSetInvoiceAmount();
    // });

    listenKeyup("#invoicePaidAmount", function (e) {
        calculateChange();
    });
    
    const calculateChange = () => {
        let expectedAmount = $("#total_amount").val();
        let paidAmount = $('#invoicePaidAmount').val(); 
        let initialChange = $('#invoiceChange').val(); // Use a different variable to store the initial value
    
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
        
        $("#invoiceChange").val(change.toFixed(2));
        $("#invoiceChangeText").text(change.toFixed(2));
    
        
    };

    listenChange(".status", function () {
        var status = $(this).val();
        if(status == 0) {
            $('.payment-change-tr').show();
        } else {
            $('.payment-change-tr').hide();
        }
    });
}

listenSubmit(".invoiceForm", function (event) {
    event.preventDefault();
    // screenLock();
    let expectedAmount = $("#total_amount").val();
    let paidAmount = $('#invoicePaidAmount').val();  
    let status = $('.status').val();  
    let formData = new FormData($(this)[0]);
    console.log(formData);
   

    if (status == 0 && (parseFloat(paidAmount) < parseFloat(expectedAmount))) {
        displayErrorMessage('Paid Amount must NOT be less than expected '+ expectedAmount);
    } else {
        $.ajax({
            url: $(".invoiceSaveUrl").val(),
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function (result) {
                displaySuccessMessage(result.message);
                window.location.href =
                    $(".invoiceUrl").val() + "/" + result.data.id;
            },
            error: function (result) {
                printErrorMessage("#validationErrorsBox", result);
            },
            // complete: function () {
            //     screenUnLock();
            // },
        });
    }
    
  
});

listenClick("#addInvoiceItem", function () {
    let uniqueId = $(".uniqueId").val();

    let data = {
        charges: JSON.parse($(".invoiceCharges").val()),
        uniqueId: uniqueId,
    };
    let invoiceItemHtml = prepareTemplateRender("#invoiceItemTemplate", data);
    $(".invoice-item-container").append(invoiceItemHtml);
    dropdownToSelect2Charge(".chargeId");
    uniqueId++;

    resetInvoiceItemIndex();
});

const resetInvoiceItemIndex = () => {
    let index = 1;
    $(".invoice-item-container>tr").each(function () {
        $(this).find(".item-number").text(index);
        index++;
    });
    if (index - 1 == 0) {
        let uniqueId = $(".uniqueId").val();
        let data = {
            charges: JSON.parse($(".invoiceCharges").val()),
            uniqueId: uniqueId,
        };
        let invoiceItemHtml = prepareTemplateRender(
            "#invoiceItemTemplate",
            data
        );
        $(".invoice-item-container").append(invoiceItemHtml);
        dropdownToSelect2Charge(".chargeId");
        uniqueId++;
    }
};

const dropdownToSelect2Charge = (selector) => {
    $(selector).select2({
        placeholder:
            Lang.get("messages.common.choose") +
            " " +
            Lang.get("messages.charges"),
        width: "100%",
    });
}
