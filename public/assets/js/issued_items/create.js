/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************************!*\
  !*** ./resources/assets/js/issued_items/create.js ***!
  \****************************************************/
document.addEventListener("turbo:load", loadIssuedItems);
function loadIssuedItems() {
  $("#issueItemCategory, #issueUserType").select2({
    width: "100%"
  });
  $("#issueTo, #issuedBy").select2({
    placeholder: Lang.get("messages.message.select_user"),
    width: "100%"
  });
  $("#issueItems").select2({
    placeholder: Lang.get("messages.common.choose") + " " + Lang.get("messages.item.item"),
    width: "100%"
  });
  var returnDate = $("#issueReturnDate").flatpickr({
    format: "Y-m-d",
    useCurrent: false,
    sideBySide: true,
    locale: $(".userCurrentLanguage").val(),
    allowInput: true,
    allowClear: true,
    clickOpens: true
  });
  $("#issueDate").flatpickr({
    format: "Y-m-d",
    useCurrent: true,
    sideBySide: true,
    locale: $(".userCurrentLanguage").val(),
    onChange: function onChange(selectedDates, dateStr, instance) {
      var minDate = moment($("#issueDate").val()).add(1, "days").format();
      // returnDate.set("minDate", minDate);
    }
  });
  $("#issueDate").on("dp.change", function (e) {
    var minDate = moment($("#issueDate").val()).add(1, "days");
    // $("#issueReturnDate").data("DateTimePicker").minDate(minDate);
  });

  // Load all users for "Issued By" field on page load
  loadAllUsersForIssuedBy();
  setTimeout(function () {
    $("#issueItemCategory, #issueUserType").trigger("change");
  }, 300);
}
listenChange("#issueItemCategory", function () {
  if ($(this).val() !== "") {
    $.ajax({
      url: $("#issuedItemsUrl").val(),
      type: "get",
      dataType: "json",
      data: {
        id: $(this).val()
      },
      success: function success(data) {
        if (data.data.length !== 0) {
          $("#issueItems").empty();
          $("#issueItems").removeAttr("disabled");
          $.each(data.data, function (i, v) {
            $("#issueItems").append($("<option></option>").attr("value", i).text(v));
          });
          $("#issueItems").trigger("change");
        } else {
          $("#issueItems").prop("disabled", true);
          $("#itemQuantity").prop("disabled", true);
          $("#itemQuantity").val("");
          $("#showAvailableQuantity").text("0");
          $("#itemAvailableQuantity").val(0);
        }
      }
    });
  }
  $("#issueItems").empty();
  $("#issueItems").append("<option>" + Lang.get("messages.common.choose") + " " + Lang.get("messages.item.item") + "</option>");
  $("#issueItems").prop("disabled", true);
});
listenChange("#issueUserType", function () {
  if ($(this).val() !== "") {
    $.ajax({
      url: $("#itemIssuedUsersUrl").val(),
      type: "get",
      dataType: "json",
      data: {
        id: $(this).val()
      },
      success: function success(data) {
        if (data.data.length !== 0) {
          // Clear and enable ONLY the "Issue To" dropdown
          $("#issueTo").empty();
          $("#issueTo").removeAttr("disabled");

          // Populate ONLY the "Issue To" dropdown with department users
          $.each(data.data, function (i, v) {
            $("#issueTo").append($("<option></option>").attr("value", i).text(v));
          });

          // Keep "Issued By" field independent - don't touch it
        } else {
          $("#issueTo").prop("disabled", true);
          // Don't disable "Issued By" - keep it independent
        }
      }
    });
  } else {
    // Reset ONLY the "Issue To" dropdown
    $("#issueTo").empty();
    $("#issueTo").append("<option>" + Lang.get("messages.message.select_user") + "</option>");
    $("#issueTo").prop("disabled", true);

    // Don't touch "Issued By" dropdown - keep it independent
  }
});
listenChange("#issueItems", function () {
  $.ajax({
    url: $("#issuedItemAvailableQtyUrl").val(),
    type: "get",
    dataType: "json",
    data: {
      id: $(this).val()
    },
    success: function success(data) {
      $("#itemAvailableQuantity").val(data);
      $("#showAvailableQuantity").text(data);
      $("#itemQuantity").attr("max", data);
      $("#itemQuantity").attr("disabled", false);
    }
  });
});

// Handle form submission to properly handle empty return dates
listenSubmit('#createIssuedItemForm', function () {
  // Check if return date is empty and set it to null
  if ($("#issueReturnDate").val() === '') {
    $("#issueReturnDate").val(null);
  }
  return true;
});
listenChange("#itemQuantity", function () {
  var availableQuantity = parseInt($("#itemAvailableQuantity").val());
  var quantity = parseInt($(this).val());
  if (quantity <= availableQuantity) {
    $("#issuedItemSave").prop("disabled", false);
  } else if (quantity === 0) showIssueItemError(Lang.get("messages.issued_item.quantity_cannot_be_zero"));else showIssueItemError(Lang.get("messages.issued_item.quantity_must_be_less_than_available_quantity"));
});
function showIssueItemError(message) {
  toastr.error(message);
  $("#issuedItemSave").prop("disabled", true);
}

// "Issued By" field is now completely independent - no need to update it based on "Issue To" selection

listenSubmit("#createIssuedItemForm, #editIssuedItemForm", function () {
  $("#issuedItemSave").attr("disabled", true);
});

// Function to load all users for "Issued By" field regardless of department
function loadAllUsersForIssuedBy() {
  console.log("Loading all users for Issued By field...");

  // Clear the dropdown first
  $("#issuedBy").empty();
  $("#issuedBy").append('<option value="">Select User</option>');
  $.ajax({
    url: $("#itemIssuedUsersUrl").val(),
    type: "get",
    dataType: "json",
    data: {
      all_users: true
    },
    success: function success(data) {
      console.log("AJAX Success - All users response:", data);
      if (data.success && data.data && Object.keys(data.data).length > 0) {
        $("#issuedBy").removeAttr("disabled");

        // Add all users to Issued By dropdown
        $.each(data.data, function (i, v) {
          console.log("Adding user:", i, v);
          $("#issuedBy").append($("<option></option>").attr("value", i).text(v));
        });
        console.log("Total users added:", Object.keys(data.data).length);
      } else {
        console.log("No users found in response or request failed");
        console.log("Response data:", data);
        // Try fallback method
        loadUsersFromAllDepartments();
      }
    },
    error: function error(xhr, status, _error) {
      console.log("AJAX Error:", status, _error);
      console.log("Response text:", xhr.responseText);
      // Fallback: try to get users from all departments
      loadUsersFromAllDepartments();
    }
  });
}

// Fallback function to load users from all departments
function loadUsersFromAllDepartments() {
  console.log("Using fallback method to load users...");

  // Try to get department data from the form
  var departmentSelect = $("#issueUserType");
  var departmentOptions = departmentSelect.find('option');
  if (departmentOptions.length <= 1) {
    console.log("No departments found, cannot load users");
    return;
  }
  var allUsers = {};
  var completedRequests = 0;
  var totalRequests = 0;

  // Count valid department options (skip empty option)
  departmentOptions.each(function () {
    if ($(this).val() !== '') {
      totalRequests++;
    }
  });
  if (totalRequests === 0) {
    console.log("No valid departments found");
    return;
  }
  console.log("Loading users from", totalRequests, "departments");
  departmentOptions.each(function () {
    var deptId = $(this).val();
    if (deptId !== '') {
      $.ajax({
        url: $("#itemIssuedUsersUrl").val(),
        type: "get",
        dataType: "json",
        data: {
          id: deptId
        },
        success: function success(data) {
          console.log("Department", deptId, "users:", data);
          if (data.success && data.data) {
            // Merge users from this department
            Object.assign(allUsers, data.data);
          }
          completedRequests++;
          if (completedRequests === totalRequests) {
            // All requests completed, populate the dropdown
            console.log("All departments loaded, total users:", Object.keys(allUsers).length);
            $("#issuedBy").empty();
            $("#issuedBy").append('<option value="">Select User</option>');
            $("#issuedBy").removeAttr("disabled");
            $.each(allUsers, function (i, v) {
              $("#issuedBy").append($("<option></option>").attr("value", i).text(v));
            });
          }
        },
        error: function error(xhr, status, _error2) {
          console.log("Error loading users from department", deptId, ":", _error2);
          completedRequests++;
          if (completedRequests === totalRequests) {
            // Still populate with whatever we got
            $("#issuedBy").empty();
            $("#issuedBy").append('<option value="">Select User</option>');
            $("#issuedBy").removeAttr("disabled");
            $.each(allUsers, function (i, v) {
              $("#issuedBy").append($("<option></option>").attr("value", i).text(v));
            });
          }
        }
      });
    }
  });
}
/******/ })()
;