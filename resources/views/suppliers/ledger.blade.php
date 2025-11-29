@extends('layouts.app')
@section('title')
    {{ __('Supplier Ledger') }}
@endsection

@include('reports.partials._report-scripts')

@section('page_css')
<link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
<style>
    .supplier-info {
        margin-bottom: 20px;
    }
    .supplier-info h4 {
        margin-bottom: 5px;
    }
    .supplier-info p {
        margin-bottom: 3px;
    }
    .summary-card {
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .summary-card h5 {
        margin-bottom: 10px;
        font-weight: 600;
    }
    .summary-card .amount {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .bg-light-success {
        background-color: rgba(50, 205, 50, 0.1);
    }
    .bg-light-danger {
        background-color: rgba(255, 0, 0, 0.1);
    }
    .bg-light-info {
        background-color: rgba(0, 123, 255, 0.1);
    }
    .text-success {
        color: #28a745;
    }
    .text-danger {
        color: #dc3545;
    }
    .text-info {
        color: #17a2b8;
    }
    @media print {
        .no-print {
            display: none !important;
        }
        .print-only {
            display: block !important;
        }
        a {
            text-decoration: none !important;
            color: inherit !important;
        }
        .badge {
            border: 1px solid #000 !important;
            background-color: transparent !important;
            color: #000 !important;
        }
        .table {
            border-collapse: collapse !important;
        }
        .table td, .table th {
            border: 1px solid #ddd !important;
            padding: 8px !important;
        }
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1>{{ __('Supplier Ledger') }}</h1>
                        <div class="d-flex align-items-center">
                            @include('reports.partials._report-tools')
                            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-primary ms-2">
                                <i class="fas fa-arrow-left"></i> {{ __('Back to Suppliers') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Supplier Information') }}</h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="supplier-info">
                                <h4>{{ $supplier->name }}</h4>
                                <p><i class="fas fa-envelope me-2"></i> {{ $supplier->email }}</p>
                                <p><i class="fas fa-phone me-2"></i> {{ $supplier->phone }}</p>
                                <p><i class="fas fa-map-marker-alt me-2"></i> {{ $supplier->address }}, {{ $supplier->city }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="summary-card bg-light-info">
                        <h5 class="text-info"><i class="fas fa-shopping-cart me-2"></i> {{ __('Total Purchases') }}</h5>
                        <div class="amount text-info">{{ getCurrencySymbol() }} {{ number_format($totalPurchases, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-card bg-light-success">
                        <h5 class="text-success"><i class="fas fa-money-bill-wave me-2"></i> {{ __('Total Paid') }}</h5>
                        <div class="amount text-success">{{ getCurrencySymbol() }} {{ number_format($totalPaid, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-card bg-light-danger">
                        <h5 class="text-danger"><i class="fas fa-exclamation-circle me-2"></i> {{ __('Amount Due') }}</h5>
                        <div class="amount text-danger">{{ getCurrencySymbol() }} {{ number_format($totalDue, 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Purchase History') }}</h4>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="purchaseTable">
                                <thead>
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th>{{ __('Purchase No') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Items') }}</th>
                                        <th>{{ __('Total Amount') }}</th>
                                        <th>{{ __('Paid Amount') }}</th>
                                        <th>{{ __('Balance') }}</th>
                                        <th>{{ __('Payment Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchases as $purchase)
                                        <tr>
                                            <td>{{ $purchase->purchase_no }}</td>
                                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d M, Y') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info view-items" data-id="{{ $purchase->id }}">
                                                    {{ $purchase->purchasedMedcines->count() }} {{ __('Items') }}
                                                </button>
                                            </td>
                                            <td>{{ getCurrencySymbol() }} {{ number_format($purchase->net_amount, 2) }}</td>
                                            <td>{{ getCurrencySymbol() }} {{ number_format($purchase->paid_amount, 2) }}</td>
                                            <td>{{ getCurrencySymbol() }} {{ number_format($purchase->balance, 2) }}</td>
                                            <td>
                                                @if($purchase->payment_status == \App\Models\PurchaseMedicine::PAID)
                                                    <span class="badge bg-light-success">{{ __('Paid') }}</span>
                                                @else
                                                    <span class="badge bg-light-danger">{{ __('Unpaid') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">{{ __('No purchase records found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Modal -->
    <div class="modal fade" id="itemsModal" tabindex="-1" role="dialog" aria-labelledby="itemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemsModalLabel">{{ __('Purchase Items') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Medicine') }}</th>
                                    <th>{{ __('Lot No') }}</th>
                                    <th>{{ __('Expiry Date') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Items will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Section (Hidden) -->
    <div id="supplierLedgerPrintSection" class="d-none">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>{{ getAppName() }}</h2>
            <h3>{{ __('Supplier Ledger') }}</h3>
            <p>{{ \Carbon\Carbon::now()->format('d M, Y') }}</p>
        </div>
        
        <div style="margin-bottom: 20px;">
            <h4>{{ __('Supplier Information') }}</h4>
            <p><strong>{{ __('Name') }}:</strong> {{ $supplier->name }}</p>
            <p><strong>{{ __('Email') }}:</strong> {{ $supplier->email }}</p>
            <p><strong>{{ __('Phone') }}:</strong> {{ $supplier->phone }}</p>
            <p><strong>{{ __('Address') }}:</strong> {{ $supplier->address1 }}, {{ $supplier->city }}, {{ $supplier->zip }}</p>
        </div>
        
        <div style="margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <tr>
                    <td style="width: 33%; border: 1px solid #ddd; padding: 10px;">
                        <h5>{{ __('Total Purchases') }}</h5>
                        <div style="font-size: 18px; font-weight: bold;">{{ getCurrencySymbol() }} {{ number_format($totalPurchases, 2) }}</div>
                    </td>
                    <td style="width: 33%; border: 1px solid #ddd; padding: 10px;">
                        <h5>{{ __('Total Paid') }}</h5>
                        <div style="font-size: 18px; font-weight: bold;">{{ getCurrencySymbol() }} {{ number_format($totalPaid, 2) }}</div>
                    </td>
                    <td style="width: 33%; border: 1px solid #ddd; padding: 10px;">
                        <h5>{{ __('Amount Due') }}</h5>
                        <div style="font-size: 18px; font-weight: bold;">{{ getCurrencySymbol() }} {{ number_format($totalDue, 2) }}</div>
                    </td>
                </tr>
            </table>
        </div>
        
        <div>
            <h4>{{ __('Purchase History') }}</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ __('Purchase No') }}</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ __('Date') }}</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ __('Items Count') }}</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ __('Total Amount') }}</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ __('Paid Amount') }}</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ __('Balance') }}</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ __('Payment Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $purchase->purchase_no }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ \Carbon\Carbon::parse($purchase->created_at)->format('d M, Y') }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $purchase->purchasedMedcines->count() }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ getCurrencySymbol() }} {{ number_format($purchase->net_amount, 2) }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ getCurrencySymbol() }} {{ number_format($purchase->paid_amount, 2) }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ getCurrencySymbol() }} {{ number_format($purchase->balance, 2) }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">
                                @if($purchase->payment_status == \App\Models\PurchaseMedicine::PAID)
                                    {{ __('Paid') }}
                                @else
                                    {{ __('Unpaid') }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ __('No purchase records found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 30px; text-align: center;">
            <p>{{ __('Generated on') }}: {{ \Carbon\Carbon::now()->format('d M, Y h:i A') }}</p>
            <p>{{ getAppName() }} &copy; {{ date('Y') }}</p>
        </div>
        
        <div class="d-print-none" style="display: flex; justify-content: center; margin-top: 20px;">
            <button onclick="window.print();" class="btn btn-primary me-2" style="display: inline-block !important;">{{ __('Print Now') }}</button>
            <button onclick="window.close();" class="btn btn-secondary" style="display: inline-block !important;">{{ __('Close') }}</button>
        </div>
    </div>
@endsection

@section('page_scripts')
<script src="{{ asset('assets/js/dataTables.min.js') }}"></script>
<script>
    // Set currency symbol for JavaScript
    window.currencySymbol = '{{ getCurrencySymbol() }}';
</script>
@endsection
