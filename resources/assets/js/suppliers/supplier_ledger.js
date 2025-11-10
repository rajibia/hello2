'use strict';

document.addEventListener('DOMContentLoaded', function () {
    initDataTable();
    handlePrintReport();
    handleViewItems();
});

// Initialize DataTable
function initDataTable() {
    if (!$('#purchaseTable').length) return;

    $('#purchaseTable').DataTable({
        "order": [[1, "desc"]],
        "language": {
            "search": "_INPUT_",
            "searchPlaceholder": "Search records",
            "lengthMenu": "_MENU_ records per page",
            "zeroRecords": "No records found",
            "info": "Showing page _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)"
        }
    });
}

// Handle View Items Button Click
function handleViewItems() {
    listenClick('.view-items', function () {
        let purchaseId = $(this).data('id');
        
        // Clear previous items
        $('#itemsTableBody').empty();
        
        // Show loading indicator
        $('#itemsTableBody').append('<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading items...</td></tr>');
        
        // Load items via AJAX
        $.ajax({
            url: route('purchase-medicines.items', purchaseId),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Clear loading indicator
                $('#itemsTableBody').empty();
                
                if (response.success && response.data.length > 0) {
                    response.data.forEach(function(item) {
                        let row = '<tr>' +
                            '<td>' + item.medicine_name + '</td>' +
                            '<td>' + item.lot_no + '</td>' +
                            '<td>' + item.expiry_date + '</td>' +
                            '<td>' + item.quantity + '</td>' +
                            '<td>' + getCurrencySymbol() + ' ' + parseFloat(item.amount).toFixed(2) + '</td>' +
                            '</tr>';
                        $('#itemsTableBody').append(row);
                    });
                } else {
                    $('#itemsTableBody').append('<tr><td colspan="5" class="text-center">No items found</td></tr>');
                }
                
                $('#itemsModal').modal('show');
            },
            error: function() {
                $('#itemsTableBody').empty();
                $('#itemsTableBody').append('<tr><td colspan="5" class="text-center text-danger">Error loading items</td></tr>');
                $('#itemsModal').modal('show');
            }
        });
    });
}

// Handle Print Report Button Click
function handlePrintReport() {
    listenClick('#printReport', function () {
        let printWindow = window.open('', '_blank');
        let printContent = document.getElementById('supplierLedgerPrintSection').innerHTML;
        let supplierName = $('.supplier-info h4').text();
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Supplier Ledger - ${supplierName}</title>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        margin: 20px; 
                        line-height: 1.5;
                    }
                    table { 
                        width: 100%; 
                        border-collapse: collapse; 
                        margin-bottom: 20px; 
                    }
                    th, td { 
                        border: 1px solid #ddd; 
                        padding: 8px; 
                        text-align: left; 
                    }
                    th { 
                        background-color: #f2f2f2; 
                    }
                    h2, h3, h4 { 
                        margin-bottom: 10px; 
                    }
                    .d-print-none { 
                        display: none; 
                    }
                    @media print {
                        .d-print-none { 
                            display: none !important; 
                        }
                        button { 
                            display: none !important; 
                        }
                    }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.focus();
    });
}

// Helper function to get currency symbol
function getCurrencySymbol() {
    return window.currencySymbol || '$';
}
