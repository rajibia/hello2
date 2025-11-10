'use strict';

$(document).ready(function () {
    $('#timelineDate, #editTimelineDate').flatpickr({
        format: 'YYYY-MM-DD',
        useCurrent: true,
        sideBySide: true,
    });

    $('#maternityTimelineTable').DataTable({
        processing: true,
        serverSide: true,
        'order': [[0, 'desc']],
        ajax: {
            url: maternityTimelineUrl,
        },
        columnDefs: [
            {
                'targets': [0],
                'width': '10%',
            },
            {
                'targets': [1],
                'width': '10%',
            },
            {
                'targets': [2],
                'width': '12%',
            },
            {
                'targets': [3],
                'width': '65%',
            },
            {
                targets: '_all',
                defaultContent: 'N/A',
                'className': 'text-start align-middle text-nowrap',
            },
        ],
        columns: [
            {
                data: function (row) {
                    return moment(row.created_at).format('Do MMM, Y');
                },
                name: 'created_at',
            },
            {
                data: function (row) {
                    return moment(row.date).format('Do MMM, Y');
                },
                name: 'date',
            },
            {
                data: 'title',
                name: 'title',
            },
            {
                data: 'description',
                name: 'description',
            },
            {
                data: function (row) {
                    let url = maternityTimelineUrl + '/' + row.id;
                    let data = [
                        {
                            'id': row.id,
                            'url': url + '/edit',
                        }];
                    return prepareTemplateRender('#maternityTimelineActionTemplate',
                        data);
                }, name: 'id',
            },
        ],
    });

    $(document).on('click', '.delete-maternity-timeline-btn', function (event) {
        let maternityTimelineId = $(event.currentTarget).data('id');
        deleteItem(maternityTimelineUrl + '/' + maternityTimelineId,
            '#maternityTimelineTable', 'Maternity Timeline');
    });
});
