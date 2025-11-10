document.addEventListener('turbo:load', loadPatientMaternityTimelineData)

function loadPatientMaternityTimelineData() {
    if (!$('#showMaternityListPatientDepartmentId').length) {
        return
    }

    getMaternityTimelines($('#showMaternityListPatientDepartmentId').val());
}

function getMaternityTimelines(maternityPatientDepartmentId) {
    $.ajax({
        url: $('#showMaternityListTimelinesUrl').val(),
        type: 'get',
        data: {id: maternityPatientDepartmentId},
        success: function (data) {
            $('#maternityTimelines').html(data);
        },
    });
};
