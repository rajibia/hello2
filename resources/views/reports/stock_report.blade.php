@extends('layouts.app')
@section('title')
    {{ __('Inventory Stock Report') }}
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/css/sub-header.css') }}">
    <style>
        @media print {
            @page {
                margin: 10px;
            }
            body {
                padding: 10px !important;
                font-size: 12px !important;
            }
            .print-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            .print-table th, .print-table td {
                border: 1px solid #ddd;
                padding: 8px;
                font-size: 12px;
            }
            .print-table th {
                background-color: #f2f2f2;
                text-align: left;
            }
            .print-header {
                text-align: center;
                margin-bottom: 20px;
            }
            .print-header h2 {
                margin-bottom: 5px;
            }
            .print-header h4 {
                margin-top: 0;
                color: #6c757d;
            }
            .no-print {
                display: none !important;
            }
            a {
                text-decoration: none !important;
                color: inherit !important;
            }
            .badge {
                border: none !important;
                background-color: transparent !important;
                color: #000 !important;
            }
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1>{{ __('Inventory Stock Report') }}</h1>
                <div class="d-flex align-items-center">
                    <button id="printReport" class="btn btn-primary me-2">
                        <i class="fas fa-print"></i> {{ __('Print Report') }}
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
                    </a>
                </div>
            </div>
            
            @livewire('stock-report')
        </div>
    </div>
@endsection

{{-- Print functionality is handled in the Livewire component --}}
