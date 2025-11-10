@extends('layouts.app')
@section('title')
    {{ __('Reports') }}
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/css/sub-header.css') }}">
    <style>
        .report-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .report-icon {
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 15px;
        }
        .report-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: background-color 0.2s ease;
            text-decoration: none;
        }
        .report-link:hover {
            background-color: rgba(0,0,0,0.05);
        }
        .report-category-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .report-category-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: #009ef7;
        }
    </style>
@endsection
@section('content')
    @include('flash::message')
    <div>
        <!-- <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h2>{{ __('Reports Dashboard') }}</h2>
            </div>
        </div> -->
        <div class="card-body pt-0 fs-6 py-8 px-8 px-lg-10 text-gray-700">
            <div class="row g-5 g-xl-8">
                <!-- Report Category: Patient Reports -->
                <div class="col-xl-6">
                    <div class="card report-card card-xl-stretch mb-5 mb-xl-8">
                        <div class="card-header border-0">
                            <h3 class="card-title report-category-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Patient Reports</span>
                            </h3>
                        </div>
                        <div class="card-body py-3">
                            <div class="d-flex flex-column">
                                <a href="{{ route('reports.daily-count') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-info text-info"><i class="fas fa-chart-bar"></i></div>
                                    <span>Daily OPD & IPD Count</span>
                                </a>
                                <a href="{{ route('reports.discharge') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-success text-success"><i class="fas fa-file-medical-alt"></i></div>
                                    <span>Discharge Report</span>
                                </a>
                                <a href="{{ route('reports.opd-statement') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-primary text-primary"><i class="fas fa-file-alt"></i></div>
                                    <span>OPD Statement Report</span>
                                </a>
                                <a href="{{ route('reports.monthly-outpatient-morbidity') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-warning text-warning"><i class="fas fa-chart-pie"></i></div>
                                    <span>Monthly Outpatient Morbidity Returns</span>
                                </a>
                                <a href="{{ route('reports.patient-statement') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-danger text-danger"><i class="fas fa-file-medical"></i></div>
                                    <span>Patient Statement Report</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Category: Financial Reports -->
                <div class="col-xl-6">
                    <div class="card report-card card-xl-stretch mb-5 mb-xl-8">
                        <div class="card-header border-0">
                            <h3 class="card-title report-category-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Financial Reports</span>
                            </h3>
                        </div>
                        <div class="card-body py-3">
                            <div class="d-flex flex-column">
                                <a href="{{ route('reports.transaction') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-success text-success"><i class="fas fa-money-bill-wave"></i></div>
                                    <span>Daily & Monthly Transaction Report</span>
                                </a>
                                <a href="{{ route('reports.opd-balance') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-primary text-primary"><i class="fas fa-balance-scale"></i></div>
                                    <span>OPD Balance Report</span>
                                </a>
                                <a href="{{ route('reports.ipd-balance') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-info text-info"><i class="fas fa-balance-scale-left"></i></div>
                                    <span>IPD Balance Report</span>
                                </a>
                                <a href="{{ route('reports.pharmacy-bill') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-warning text-warning"><i class="fas fa-file-invoice-dollar"></i></div>
                                    <span>Pharmacy Bill Report</span>
                                </a>
                                <a href="{{ route('reports.expenses') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-danger text-danger"><i class="fas fa-chart-line"></i></div>
                                    <span>Expenses Report</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Category: Inventory Reports -->
                <div class="col-xl-6">
                    <div class="card report-card card-xl-stretch mb-5 mb-xl-8">
                        <div class="card-header border-0">
                            <h3 class="card-title report-category-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Inventory Reports</span>
                            </h3>
                        </div>
                        <div class="card-body py-3">
                            <div class="d-flex flex-column">
                                <a href="{{ route('reports.medicine') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-primary text-primary"><i class="fas fa-pills"></i></div>
                                    <span>Medicine Report</span>
                                </a>
                                <a href="{{ route('reports.expiry-medicine') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-danger text-danger"><i class="fas fa-exclamation-triangle"></i></div>
                                    <span>Expiry Medicine Report</span>
                                </a>
                                <a href="{{ route('reports.medicine-transfer') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-success text-success"><i class="fas fa-exchange-alt"></i></div>
                                    <span>{{ __('messages.medicine.medicine_transfer_report') }}</span>
                                </a>
                                <a href="{{ route('reports.medicine-adjustment') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-warning text-warning"><i class="fas fa-balance-scale"></i></div>
                                    <span>{{ __('messages.medicine.medicine_adjustment_report') }}</span>
                                </a>
                                <a href="{{ route('reports.company-claim') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-warning text-warning"><i class="fas fa-building"></i></div>
                                    <span>Company Claim Report</span>
                                </a>
                                <a href="{{ route('suppliers.index') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-success text-success"><i class="fas fa-boxes"></i></div>
                                    <span>Supplier Report</span>
                                </a>
                                <a href="{{ route('reports.purchase') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-primary text-primary"><i class="fas fa-shopping-cart"></i></div>
                                    <span>Purchase Report</span>
                                </a>
                                <a href="{{ route('reports.stock') }}" class="report-link text-gray-800 text-hover-primary fs-5">
                                    <div class="report-icon bg-light-info text-info"><i class="fas fa-boxes"></i></div>
                                    <span>Inventory Stock Report</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
