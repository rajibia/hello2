document.addEventListener("turbo:load", loadSupplierData);

function loadSupplierData() {
    if (!$("#createSupplierForm").length && !$("#editSupplierForm").length) {
        return;
    }
    // $(".supplierBirthDate").flatpickr({
    //     maxDate: new Date(),
    //     locale: $(".userCurrentLanguage").val(),
    // });
}

// listenKeyup(".supplierFacebookUrl", function () {
//     this.value = this.value.toLowerCase();
// });
// listenKeyup(".supplierTwitterUrl", function () {
//     this.value = this.value.toLowerCase();
// });
// listenKeyup(".supplierInstagramUrl", function () {
//     this.value = this.value.toLowerCase();
// });
// listenKeyup(".supplierLinkedInUrl", function () {
//     this.value = this.value.toLowerCase();
// });

listenSubmit("#createSupplierForm, #editSupplierForm", function () {
    if ($(".error-msg").text() !== "") {
        $(".phoneNumber").focus();
        return false;
    }

    // let facebookUrl = $(".supplierFacebookUrl").val();
    // let twitterUrl = $(".supplierTwitterUrl").val();
    // let instagramUrl = $(".supplierInstagramUrl").val();
    // let linkedInUrl = $(".supplierLinkedInUrl").val();

    // let facebookExp = new RegExp(
    //     /^(https?:\/\/)?((m{1}\.)?)?((w{2,3}\.)?)facebook.[a-z]{2,3}\/?.*/i
    // );
    // let twitterExp = new RegExp(
    //     /^(https?:\/\/)?((m{1}\.)?)?((w{2,3}\.)?)twitter\.[a-z]{2,3}\/?.*/i
    // );
    // let instagramUrlExp = new RegExp(
    //     /^(https?:\/\/)?((w{2,3}\.)?)instagram.[a-z]{2,3}\/?.*/i
    // );
    // let linkedInExp = new RegExp(
    //     /^(https?:\/\/)?((w{2,3}\.)?)linkedin\.[a-z]{2,3}\/?.*/i
    // );

    // let facebookCheck =
    //     facebookUrl == ""
    //         ? true
    //         : facebookUrl.match(facebookExp)
    //         ? true
    //         : false;
    // if (!facebookCheck) {
    //     displayErrorMessage(Lang.get("messages.user.validate_facebook_url"));
    //     return false;
    // }
    // let twitterCheck =
    //     twitterUrl == "" ? true : twitterUrl.match(twitterExp) ? true : false;
    // if (!twitterCheck) {
    //     displayErrorMessage(Lang.get("messages.user.validate_twitter_url"));
    //     return false;
    // }
    // let instagramCheck =
    //     instagramUrl == ""
    //         ? true
    //         : instagramUrl.match(instagramUrlExp)
    //         ? true
    //         : false;
    // if (!instagramCheck) {
    //     displayErrorMessage(Lang.get("messages.user.validate_instagram_url"));
    //     return false;
    // }
    // let linkedInCheck =
    //     linkedInUrl == ""
    //         ? true
    //         : linkedInUrl.match(linkedInExp)
    //         ? true
    //         : false;
    // if (!linkedInCheck) {
    //     displayErrorMessage(Lang.get("messages.user.validate_linkedin_url"));
    //     return false;
    // }
});
$("#createSupplierForm, #editSupplierForm")
    .find("input:text:visible:first")
    .focus();

// listenClick(".remove-supplier-image", function () {
//     defaultImagePreview(".previewImage", 1);
// });

// listenChange(".supplierProfileImage", function () {
//     let extension = isValidImage($(this), "#supplierErrorBox");
//     console.log(extension);
//     if (!isEmpty(extension) && extension != false) {
//         $("#supplierErrorBox").html("").hide();
//         displayDocument(this, "#supplierErrorBox", extension);
//     } else {
//         $(this).val("");
//         $("#supplierErrorBox").removeClass("d-none hide");
//         $("#supplierErrorBox")
//             .text(Lang.get("messages.user.validate_image_type"))
//             .show();
//         $("[id=supplierErrorBox]").focus();
//         $("html, body").animate({ scrollTop: "0" }, 500);
//         $(".alert").delay(5000).slideUp(300);
//     }
// });

// listenChange(".editSupplierImage", function () {
//     let extension = isValidImage($(this), "#editSupplierErrorsBox");
//     console.log(extension);
//     if (!isEmpty(extension) && extension != false) {
//         $("#editSupplierErrorsBox").html("").hide();
//         displayDocument(this, "#supplierErrorBox", extension);
//     } else {
//         $(this).val("");
//         $("#editSupplierErrorsBox").removeClass("d-none hide");
//         $("#editSupplierErrorsBox")
//             .text(Lang.get("messages.user.validate_image_type"))
//             .show();
//         $("[id=editSupplierErrorsBox]").focus();
//         $("html, body").animate({ scrollTop: "0" }, 500);
//         $(".alert").delay(5000).slideUp(300);
//     }
// });

// function isValidImage(inputSelector, validationMessageSelector) {
//     let ext = $(inputSelector).val().split(".").pop().toLowerCase();
//     if ($.inArray(ext, ["jpg", "png", "jpeg"]) == -1) {
//         return false;
//     }
//     $(validationMessageSelector).hide();
//     return true;
// }
