// Global fix for Select2 search not working in Bootstrap modal
$(document).on('select2:open', function() {
    setTimeout(function() {
        document.querySelector('.select2-container--open .select2-search__field').focus();
    }, 0);
});

// Fix for the first row
$(document).on('shown.bs.modal', '#createInvoiceModal', function() {
    initializeFirstRowSelect();
});

// Fix for when adding new rows
$(document).on('click', '#addInvoiceItem', function() {
    // After adding a new row, reinitialize the first row's select
    setTimeout(function() {
        initializeFirstRowSelect();
    }, 100);
});

// Function to initialize the first row's select
function initializeFirstRowSelect() {
    // First destroy if already initialized
    if ($('.first-row-select').hasClass('select2-hidden-accessible')) {
        $('.first-row-select').select2('destroy');
    }
    
    // Initialize with specific options
    $('.first-row-select').select2({
        dropdownParent: $('#createInvoiceModal'),
        width: '100%',
        minimumResultsForSearch: 0  // Always show search field
    });
    
    // Add direct event handler for search focus
    $('.first-row-select').off('select2:open').on('select2:open', function() {
        setTimeout(function() {
            $('.select2-container--open .select2-search__field').focus();
        }, 0);
    });
}
