document.addEventListener('turbo:load', loadPatientMaternityTimelineData)

function loadPatientMaternityTimelineData() {
    if (!$('#showMaternityListPatientDepartmentId').length) {
        return
    }

    getMaternityTimelines($('#showMaternityListPatientDepartmentId').val());
}

function getMaternityTimelines(maternityId) {
    $.ajax({
        url: $('#showMaternityListTimelinesUrl').val(),
        type: 'get',
        data: {id: maternityId},
        success: function (data) {
            $('#maternityTimelines').html(data);
        },
    });
};
