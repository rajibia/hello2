document.addEventListener('turbo:load', loadMaternityTabActiveData)

function loadMaternityTabActiveData() {
    if (!$('#MaternityTab').length) {
        return
    }
    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#MaternityTab a[href="' + hash + '"]').tab('show');
}
listenClick('#MaternityTab a', function (e) {
    e.preventDefault();
    $(this).tab('show');
});
// store the currently selected tab in the hash value
$('ul.nav-tabs > li > a').on('shown.bs.tab', function (e) {
    var id = $(e.target).attr('href').substr(1);
    window.location.hash = id;
});
