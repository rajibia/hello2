"use strict";

document.addEventListener("turbo:load", loadMaternityAppointmentTable);

function loadMaternityAppointmentTable() {
    let appointmentTimeRange = $("#time_range-maternity");
    var now = moment();

// Set the start of the appointment to the current moment
var appointmentStart = now;

// Set the end of the appointment to tomorrow at the same time
var appointmentEnd = now.clone().add(1, 'day');
    let appointmentStartTime = "";
    let appointmentEndTime = "";

    if ($("#appointmentStatus").length) {
        $("#appointmentStatus").select2();
    }

    function cb(appointmentStart, appointmentEnd) {
        $("#time_range-maternity").val(appointmentStart.format("MM/DD/YYYY") + " - " + appointmentEnd.format("MM/DD/YYYY"));
    }

    if (appointmentTimeRange.length) {
        Lang.setLocale($(".userCurrentLanguage").val());
        appointmentTimeRange.daterangepicker(
            {
                startDate: appointmentStart,
                endDate: appointmentEnd,
                locale: {
                    customRangeLabel: Lang.get("messages.common.custom"),
                    applyLabel: Lang.get("messages.common.apply"),
                    cancelLabel: Lang.get("messages.common.cancel"),
                    fromLabel: Lang.get("messages.common.from"),
                    toLabel: Lang.get("messages.common.to"),
                    monthNames: [
                        Lang.get("messages.months.jan"),
                        Lang.get("messages.months.feb"),
                        Lang.get("messages.months.mar"),
                        Lang.get("messages.months.apr"),
                        Lang.get("messages.months.may"),
                        Lang.get("messages.months.jun"),
                        Lang.get("messages.months.july"),
                        Lang.get("messages.months.aug"),
                        Lang.get("messages.months.sep"),
                        Lang.get("messages.months.oct"),
                        Lang.get("messages.months.nov"),
                        Lang.get("messages.months.dec"),
                    ],
                    daysOfWeek: [
                        Lang.get("messages.weekdays.sun"),
                        Lang.get("messages.weekdays.mon"),
                        Lang.get("messages.weekdays.tue"),
                        Lang.get("messages.weekdays.wed"),
                        Lang.get("messages.weekdays.thu"),
                        Lang.get("messages.weekdays.fri"),
                        Lang.get("messages.weekdays.sat"),
                    ],
                },
                ranges: {
                    [Lang.get("messages.appointment.today")]: [
                        moment(),
                        moment(),
                    ],
                    [Lang.get("messages.appointment.yesterday")]: [
                        moment().subtract(1, "days"),
                        moment().subtract(1, "days"),
                    ],
                    [Lang.get("messages.appointment.this_week")]: [
                        moment().startOf("week"),
                        moment().endOf("week"),
                    ],
                    [Lang.get("messages.appointment.last_7_days")]: [
                        moment().subtract(6, "days"),
                        moment(),
                    ],
                    [Lang.get("messages.appointment.last_30_days")]: [
                        moment().subtract(29, "days"),
                        moment(),
                    ],
                    [Lang.get("messages.appointment.this_month")]: [
                        moment().startOf("month"),
                        moment().endOf("month"),
                    ],
                    [Lang.get("messages.appointment.last_month")]: [
                        moment().subtract(1, "month").startOf("month"),
                        moment().subtract(1, "month").endOf("month"),
                    ],
                },
            },
            cb
        );
        cb(appointmentStart, appointmentEnd);
        appointmentTimeRange.on("apply.daterangepicker", function (ev, picker) {
            appointmentStartTime =
                picker.startDate.format("YYYY-MM-D  H:mm:ss");
            appointmentEndTime = picker.endDate.format("YYYY-MM-D  H:mm:ss");
            window.livewire.emit("changeDateFilter", "statusFilter", [
                appointmentStartTime,
                appointmentEndTime,
            ]);
        });
    }


    listenClick("#maternityResetFilter", function () {
        let appointmentTimeRange = $("#time_range-maternity");
        appointmentStartTime = appointmentTimeRange
            .data("daterangepicker")
            .setStartDate(moment().startOf("week").format("MM/DD/YYYY"));
        appointmentEndTime = appointmentTimeRange
            .data("daterangepicker")
            .setEndDate(moment().endOf("week").format("MM/DD/YYYY"));
        $("#appointmentStatus").val(2).trigger("change");
        hideDropdownManually($("#maternityFilterBtn"), $(".dropdown-menu"));
    });

    listenClick(".maternity-complete-status", function (event) {
        let maternityId = $(event.currentTarget).attr("data-id");
        completeAppointment(
            $(".maternityURL").val() + "/" + maternityId + "/status",
            "#maternityTbl",
            Lang.get("messages.web_menu.maternity") +
                " " +
                Lang.get("messages.user.status")
        );
    });
}
