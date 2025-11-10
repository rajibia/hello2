@extends('layouts.app')

@section('title')
    {{ __('messages.bill.bill_details') }}
@endsection

@section('content')
    <div class="container">
        <h2>{{ __('messages.bill.bill_details').' '.'For '.$patient->patientUser->first_name.' '.$patient->patientUser->last_name }}</h2>
        <hr>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-3" id="billsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="unpaid-tab" data-bs-toggle="tab" data-bs-target="#unpaid" type="button" role="tab" aria-controls="unpaid" aria-selected="true">
                    <i class="fas fa-exclamation-circle text-danger me-2"></i>
                    {{ __('Unpaid Bills') }}
                    <span class="badge bg-danger ms-2" id="unpaidCount">0</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="paid-tab" data-bs-toggle="tab" data-bs-target="#paid" type="button" role="tab" aria-controls="paid" aria-selected="false">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    {{ __('Paid Bills') }}
                    <span class="badge bg-success ms-2" id="paidCount">0</span>
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="billsTabContent">
            <!-- Unpaid Bills Tab -->
            <div class="tab-pane fade show active" id="unpaid" role="tabpanel" aria-labelledby="unpaid-tab">
                <!-- Select All Checkbox (only for unpaid tab) -->
                <div id="selectAllContainer" class="mb-3">
                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                    <label for="selectAll">{{ __('Select All Unpaid Bills') }}</label>
                </div>

                <!-- Toolbar Card -->
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="col-md-4" style="margin-right: 5px">
                            <input type="text" class="form-control" placeholder="{{ __('Search...') }}" aria-label="Search">
                        </div>
                        <div class="input-group">
                            <input type="date" class="form-control" aria-label="Date filter">
                        </div>
                    </div>
                </div>

                <!-- Medicine Bill Section -->
                <div>
                    <h5>{{ __('Medicine Bill') }}
                    <div id="unpaidMedicineCount" data-count="{{ $patient->medicine_bills->where('payment_status', 0)->count() }}" style="display: none;"></div>

            <!-- Main Medicine Bill Table -->
            <table class="table table-bordered table-striped" id="medicineTable">
                <thead>
                <tr>
                    <th><input type="checkbox" onclick="toggleSelectAllInSection(this, 'medicineTable')"></th>
                    <th>{{ __('messages.invoice.invoice_id') }}</th>
                    <th>{{ __('messages.bill.amount') }}</th>
                    <th>{{ __('messages.bill.payment_status') }}</th>
                    <th>{{ __('messages.bill.bill_date') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($patient->medicine_bills as $bill)
                    @if($bill->payment_status == 0)
                    <tr>
                        <td><input type="checkbox" class="select-row" data-id="{{ $bill->id }}" value="{{ $bill->total - ($bill->total * $bill->discount / 100) }}" onclick="updateMedicineTotals()"></td>
                        <td>
                            <a href="{{ route('medicine-bills.show', $bill->id) }}">
                                {{$bill->bill_number}}
                            </a>
                        </td>
                        <td>
                            <div class="d-flex pe-22">
                                @if(!empty($bill->total))
                                    {{ checkNumberFormat($bill->total - ($bill->total * $bill->discount / 100), strtoupper(getCurrentCurrency())) }}
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </div>
                        </td>
                        <td>{{$bill->payment_status==1?"Paid":"Unpaid"}}</td>
                        <td>
                            <div class="badge bg-light-info">
                                {{ \Carbon\Carbon::parse($bill->bill_date)->translatedFormat('jS M, Y')}}
                            </div>
                        </td>
                    </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No Unpaid Medicine Bills</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Subtotal and Total Table for Medicine -->
            <table class="table"
                   style="background-color: rgba(195, 197, 197, 0.644); font-size: 12px; font-weight: bolder">
                <tr>
                    <td>{{ __('messages.bill.sub_total') }}</td>
                    <td id="subTotalMedicine">{{ strtoupper(getCurrentCurrency()) }}0</td>
                </tr>
                <tr>
                    <td>{{ __('messages.bill.total') }}</td>
                    <td id="totalMedicine">{{ strtoupper(getCurrentCurrency()) }}0</td>
                </tr>
            </table>
        </div>

        <!-- IPD Bill Section -->
        <div>
            <h5>{{ __('IPD Bill') }}</h5>
            <div id="unpaidIpdCount" data-count="{{ $patient->ipdPatientDepartments->where('bill_status', 0)->count() }}" style="display: none;"></div>

            <!-- Main IPD Bill Table -->
            <table class="table table-bordered table-striped" id="ipdTable">
                <thead>
                <tr>
                    <th><input type="checkbox" onclick="toggleSelectAllInSection(this, 'ipdTable')"></th>
                    <th>{{ __('messages.invoice.invoice_id') }}</th>
                    <th>{{ __('messages.bill.amount') }}</th>
                    <th>{{ __('messages.bill.payment_status') }}</th>
                    <th>{{ __('messages.bill.bill_date') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($patient->ipdPatientDepartments as $bill)
                    @if($bill->bill_status == 0)
                    <tr>
                        <td><input type="checkbox" class="select-row" data-id="{{ $bill->id }}" value="{{ $bill->bill && $bill->bill->total_charges ? $bill->bill->total_charges - ($bill->bill->total * $bill->bill->discount / 100) : 0 }}" onclick="updateIPDTotals()"></td>
                        <td>
                            <a href="{{ route('ipd.patient.show', $bill->id) }}">
                                {{$bill->ipd_number}}
                            </a>
                        </td>
                        <td>
                            <div class="d-flex pe-22">
                                @if($bill->bill && !empty($bill->bill->total_charges))
                                    {{ checkNumberFormat($bill->bill->total_charges - ($bill->bill->total * $bill->discount / 100), strtoupper(getCurrentCurrency())) }}
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </div>
                        </td>
                        <td>{{$bill->bill_status==1?"Paid":"Unpaid"}}</td>
                        <td>
                            <div class="badge bg-light-info">
                                @if($bill->bill && !empty($bill->bill->total_charges))
                                    {{\Carbon\Carbon::parse($bill->bill->created_at)->translatedFormat('jS M, Y')}}
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No Unpaid IPD Bills</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Subtotal and Total Table for IPD -->
            <table class="table"
                   style="background-color: rgba(195, 197, 197, 0.644); font-size: 12px; font-weight: bolder">
                <tr>
                    <td>{{ __('messages.bill.sub_total') }}</td>
                    <td id="subTotalIPD">{{ strtoupper(getCurrentCurrency()) }} 0</td>
                </tr>
                <tr>
                    <td>{{ __('messages.bill.total') }}</td>
                    <td id="totalIPD">{{ strtoupper(getCurrentCurrency()) }} 0</td>
                </tr>
            </table>
        </div>

        <!-- OPD Bill Section -->
        <div>
            <h5>{{ __('OPD Bill') }}</h5>
            <div id="unpaidOpdCount" data-count="{{ $patient->invoices->where('status', \App\Models\Invoice::PENDING)->count() }}" style="display: none;"></div>

            <!-- Main OPD Bill Table -->
            <table class="table table-bordered table-striped" id="opdTable">
                <thead>
                <tr>
                    <th><input type="checkbox" onclick="toggleSelectAllInSection(this, 'opdTable')"></th>
                    <th>{{ __('messages.invoice.invoice_id') }}</th>
                    <th>{{ __('messages.bill.amount') }}</th>
                    <th>{{ __('messages.bill.payment_status') }}</th>
                    <th>{{ __('messages.bill.bill_date') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($patient->invoices as $invoice)
                    @if($invoice->status == \App\Models\Invoice::PENDING)
                    <tr>
                        <td><input type="checkbox" class="select-row" data-id="{{ $invoice->id }}" value="{{ $invoice->amount - ($invoice->amount * $invoice->discount / 100) }}" onclick="updateOPDTotals()"></td>
                        <td>
                            <a href="{{ getLoggedinPatient() ? url('employee/invoices'). '/' . $invoice->id : route('invoices.show',$invoice->id) }}">
                                {{$invoice->invoice_id}}
                            </a>
                        </td>
                        <td>
                            <div class="d-flex pe-22">
                                @if(!empty($invoice->amount))
                                    {{ checkNumberFormat($invoice->amount===null?"":$invoice->amount - ($invoice->amount * $invoice->discount / 100), strtoupper(getCurrentCurrency())) }}
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($invoice->status == \App\Models\Invoice::PENDING)
                                <span class="badge bg-danger">Unpaid</span>
                            @else
                                <span class="badge bg-success">Paid</span>
                            @endif
                        </td>
                        {{--                        <td>{{$invoice->status==1?"Paid":"Unpaid"}}</td>--}}
                        <td>
                            <div class="badge bg-light-info">
                                @if(!empty($invoice->invoice_date))
                                    {{\Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat('jS M, Y')}}
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No Unpaid OPD Bills</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Subtotal and Total Table for OPD -->
            <table class="table"
                   style="background-color: rgba(195, 197, 197, 0.644); font-size: 12px; font-weight: bolder">
                <tr>
                    <td>{{ __('messages.bill.sub_total') }}</td>
                    <td id="subTotalOPD">{{ strtoupper(getCurrentCurrency()) }} 0</td>
                </tr>
                <tr>
                    <td>{{ __('messages.bill.total') }}</td>
                    <td id="totalOPD">{{ strtoupper(getCurrentCurrency()) }} 0</td>
                </tr>
            </table>
        </div>

        <!-- Pathology Tests Section -->
        <div>
            <h5>{{ __('Pathology Tests') }}</h5>
            <div id="unpaidPathologyCount" data-count="{{ $patient->pathologyTests->where('balance', '>', 0)->count() }}" style="display: none;"></div>
            <table class="table table-bordered table-striped" id="pathologyTable">
                <thead>
                <tr>
                    <th><input type="checkbox" onclick="toggleSelectAllInSection(this, 'pathologyTable')"></th>
                    <th>{{ __('Invoice ID') }}</th>
                    <th>{{ __('messages.bill.amount') }}</th>
                    <th>{{ __('messages.bill.payment_status') }}</th>
                    <th>{{ __('messages.bill.bill_date') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($patient->pathologyTests as $test)
                    @if($test->balance > 0)
                    <tr>
                        <td><input type="checkbox" class="select-row" data-id="{{ $test->id }}" value="{{ $test->balance }}" onclick="updatePathologyTotals()"></td>
                        <td>
                            <a href="{{ route('pathology.test.show', $test->id) }}" class="text-decoration-none">{{ $test->bill_no }}</a>
                        </td>
                        <td>
                            <div class="d-flex pe-22">
                                @if(!empty($test->balance))
                                    {{ checkNumberFormat($test->balance, strtoupper(getCurrentCurrency())) }}
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </div>
                        </td>
                        <td><span class="badge bg-danger">Unpaid</span></td>
                        <td>
                            <div class="badge bg-light-success">
                                {{ \Carbon\Carbon::parse($test->created_at)->translatedFormat('jS M, Y')}}
                            </div>
                        </td>
                    </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No Unpaid Pathology Tests</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Subtotal and Total Table for Pathology -->
            <table class="table"
                   style="background-color: rgba(195, 197, 197, 0.644); font-size: 12px; font-weight: bolder">
                <tr>
                    <td>{{ __('messages.bill.sub_total') }}</td>
                    <td id="subTotalPathology">{{ strtoupper(getCurrentCurrency()) }}0</td>
                </tr>
                <tr>
                    <td>{{ __('messages.bill.total') }}</td>
                    <td id="totalPathology">{{ strtoupper(getCurrentCurrency()) }}0</td>
                </tr>
            </table>
        </div>

        <!-- Radiology Tests Section -->
        <div>
            <h5>{{ __('Radiology Tests') }}</h5>
            <div id="unpaidRadiologyCount" data-count="{{ $patient->radiologyTests->where('balance', '>', 0)->count() }}" style="display: none;"></div>
            <table class="table table-bordered table-striped" id="radiologyTable">
                <thead>
                <tr>
                    <th><input type="checkbox" onclick="toggleSelectAllInSection(this, 'radiologyTable')"></th>
                    <th>{{ __('Invoice ID') }}</th>
                    <th>{{ __('Bill No') }}</th>
                    <th>{{ __('messages.bill.amount') }}</th>
                    <th>{{ __('messages.bill.payment_status') }}</th>
                    <th>{{ __('messages.bill.bill_date') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($patient->radiologyTests as $test)
                    @if($test->balance > 0)
                    <tr>
                        <td><input type="checkbox" class="select-row" data-id="{{ $test->id }}" value="{{ $test->balance }}" onclick="updateRadiologyTotals()"></td>
                        <td><a href="{{ route('radiology.test.show', $test->id) }}" class="text-decoration-none">RAD-{{ $test->id }}</a></td>
                        <td>{{ $test->bill_no }}</td>
                        <td>
                            <div class="text-center">
                                @if(!empty($test->balance))
                                    {{ checkNumberFormat($test->balance, strtoupper(getCurrentCurrency())) }}
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </div>
                        </td>
                        <td><span class="badge bg-danger">Unpaid</span></td>
                        <td>
                            <div class="badge bg-light-warning">
                                {{ \Carbon\Carbon::parse($test->created_at)->translatedFormat('jS M, Y')}}
                            </div>
                        </td>
                    </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No Unpaid Radiology Tests</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Subtotal and Total Table for Radiology -->
            <table class="table"
                   style="background-color: rgba(195, 197, 197, 0.644); font-size: 12px; font-weight: bolder">
                <tr>
                    <td>{{ __('messages.bill.sub_total') }}</td>
                    <td id="subTotalRadiology">{{ strtoupper(getCurrentCurrency()) }}0</td>
                </tr>
                <tr>
                    <td>{{ __('messages.bill.total') }}</td>
                    <td id="totalRadiology">{{ strtoupper(getCurrentCurrency()) }}0</td>
                </tr>
            </table>
        </div>

        <!-- Maternity Bills Section -->
        <div>
            <h5>{{ __('Maternity Bills') }}</h5>
            <div id="unpaidMaternityCount" data-count="{{ $patient->maternity->filter(function($m) { return ($m->paid_amount ?? 0) < ($m->standard_charge ?? 0); })->count() }}" style="display: none;"></div>

            <!-- Main Maternity Bill Table -->
            <table class="table table-bordered table-striped" id="maternityTable">
                <thead>
                <tr>
                    <th><input type="checkbox" onclick="toggleSelectAllInSection(this, 'maternityTable')"></th>
                    <th>{{ __('Invoice ID') }}</th>
                    <th>{{ __('messages.bill.amount') }}</th>
                    <th>{{ __('messages.bill.payment_status') }}</th>
                    <th>{{ __('messages.bill.bill_date') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($patient->maternity as $maternity)
                    @if(($maternity->paid_amount ?? 0) < ($maternity->standard_charge ?? 0))
                    <tr>
                        <td><input type="checkbox" class="select-row" data-id="{{ $maternity->id }}" value="{{ $maternity->standard_charge ?? 0 }}" onclick="updateMaternityTotals()"></td>
                        <td><a href="{{ route('maternity.patient.show', $maternity->id) }}" class="text-decoration-none">{{ $maternity->invoice_id ?? 'MAT-' . $maternity->id }}</a></td>
                        <td>
                            <div class="d-flex pe-22">
                                @if(!empty($maternity->standard_charge))
                                    {{ checkNumberFormat($maternity->standard_charge, strtoupper(getCurrentCurrency())) }}
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </div>
                        </td>
                        <td><span class="badge bg-danger">Unpaid</span></td>
                        <td>
                            <div class="badge bg-light-primary">
                                {{ \Carbon\Carbon::parse($maternity->created_at)->translatedFormat('jS M, Y')}}
                            </div>
                        </td>
                    </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No Unpaid Maternity Bills</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Subtotal and Total Table for Maternity -->
            <table class="table"
                   style="background-color: rgba(195, 197, 197, 0.644); font-size: 12px; font-weight: bolder">
                <tr>
                    <td>{{ __('messages.bill.sub_total') }}</td>
                    <td id="subTotalMaternity">{{ strtoupper(getCurrentCurrency()) }}0</td>
                </tr>
                <tr>
                    <td>{{ __('messages.bill.total') }}</td>
                    <td id="totalMaternity">{{ strtoupper(getCurrentCurrency()) }}0</td>
                </tr>
            </table>
        </div>

        <!-- Billing Summary Section -->
        <div class="card mt-3">
            <div class="card-body">
                <h5>{{ __('Billing Summary') }}</h5>
                <table class="table"
                       style="background-color: rgba(195, 197, 197, 0.644); font-size: 12px; font-weight: bolder">
                    <tr>
                        <td>{{ __('messages.bill.sub_total') }}</td>
                        <td id="summarySubTotal">{{ strtoupper(getCurrentCurrency()) }} 0</td>
                    </tr>
                    <tr>
                        <td>{{ __('messages.bill.total') }}</td>
                        <td id="summaryTotal">{{ strtoupper(getCurrentCurrency()) }} 0</td>
                    </tr>
                </table>
                <button class="btn btn-primary" onclick="paySelected()">{{ __('Pay Selected') }}</button>
            </div>
        </div>
            </div> <!-- End of unpaid tab -->

            <!-- Paid Bills Tab -->
            <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ __('This tab shows all paid bills. No payment actions are available for paid bills.') }}
                </div>

                <!-- Paid Medicine Bills -->
                <div>
                    <h5>{{ __('Paid Medicine Bills') }}</h5>
                    <div id="paidMedicineCount" data-count="{{ $patient->medicine_bills->where('payment_status', 1)->count() }}" style="display: none;"></div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('messages.invoice.invoice_id') }}</th>
                            <th>{{ __('messages.bill.amount') }}</th>
                            <th>{{ __('messages.bill.payment_status') }}</th>
                            <th>{{ __('messages.bill.bill_date') }}</th>
                            <th>{{ __('Payment Date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($patient->medicine_bills as $bill)
                            @if($bill->payment_status == 1)
                            <tr>
                                <td>
                                    <a href="{{ route('medicine-bills.show', $bill->id) }}">
                                        {{$bill->bill_number}}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex pe-22">
                                        @if(!empty($bill->total))
                                            {{ checkNumberFormat($bill->total - ($bill->total * $bill->discount / 100), strtoupper(getCurrentCurrency())) }}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>
                                    <div class="badge bg-light-info">
                                        {{ \Carbon\Carbon::parse($bill->bill_date)->translatedFormat('jS M, Y')}}
                                    </div>
                                </td>
                                <td>
                                    <div class="badge bg-light-success">
                                        {{ \Carbon\Carbon::parse($bill->updated_at)->translatedFormat('jS M, Y')}}
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Paid Medicine Bills</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paid IPD Bills -->
                <div>
                    <h5>{{ __('Paid IPD Bills') }}</h5>
                    <div id="paidIpdCount" data-count="{{ $patient->ipdPatientDepartments->where('bill_status', 1)->count() }}" style="display: none;"></div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('messages.invoice.invoice_id') }}</th>
                            <th>{{ __('messages.bill.amount') }}</th>
                            <th>{{ __('messages.bill.payment_status') }}</th>
                            <th>{{ __('messages.bill.bill_date') }}</th>
                            <th>{{ __('Payment Date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($patient->ipdPatientDepartments as $bill)
                            @if($bill->bill_status == 1)
                            <tr>
                                <td>
                                    <a href="{{ route('ipd.patient.show', $bill->id) }}">
                                        {{$bill->ipd_number}}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex pe-22">
                                        @if($bill->bill && !empty($bill->bill->total_charges))
                                            {{ checkNumberFormat($bill->bill->total_charges - ($bill->bill->total * $bill->bill->discount / 100), strtoupper(getCurrentCurrency())) }}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>
                                    <div class="badge bg-light-info">
                                        @if($bill->bill && !empty($bill->bill->total_charges))
                                            {{\Carbon\Carbon::parse($bill->bill->created_at)->translatedFormat('jS M, Y')}}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="badge bg-light-success">
                                        {{ \Carbon\Carbon::parse($bill->updated_at)->translatedFormat('jS M, Y')}}
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Paid IPD Bills</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paid OPD Bills -->
                <div>
                    <h5>{{ __('Paid OPD Bills') }}</h5>
                    <div id="paidOpdCount" data-count="{{ $patient->invoices->where('status', \App\Models\Invoice::PAID)->count() }}" style="display: none;"></div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('messages.invoice.invoice_id') }}</th>
                            <th>{{ __('messages.bill.amount') }}</th>
                            <th>{{ __('messages.bill.payment_status') }}</th>
                            <th>{{ __('messages.bill.bill_date') }}</th>
                            <th>{{ __('Payment Date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($patient->invoices as $invoice)
                            @if($invoice->status == \App\Models\Invoice::PAID)
                            <tr>
                                <td>
                                    <a href="{{ getLoggedinPatient() ? url('employee/invoices'). '/' . $invoice->id : route('invoices.show',$invoice->id) }}">
                                        {{$invoice->invoice_id}}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex pe-22">
                                        @if(!empty($invoice->amount))
                                            {{ checkNumberFormat($invoice->amount===null?"":$invoice->amount - ($invoice->amount * $invoice->discount / 100), strtoupper(getCurrentCurrency())) }}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>
                                    <div class="badge bg-light-info">
                                        @if(!empty($invoice->invoice_date))
                                            {{\Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat('jS M, Y')}}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="badge bg-light-success">
                                        {{ \Carbon\Carbon::parse($invoice->updated_at)->translatedFormat('jS M, Y')}}
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Paid OPD Bills</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paid Pathology Tests -->
                <div>
                    <h5>{{ __('Paid Pathology Tests') }}</h5>
                    <div id="paidPathologyCount" data-count="{{ $patient->pathologyTests->where('balance', 0)->count() }}" style="display: none;"></div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('Invoice ID') }}</th>
                            <th>{{ __('messages.bill.amount') }}</th>
                            <th>{{ __('messages.bill.payment_status') }}</th>
                            <th>{{ __('messages.bill.bill_date') }}</th>
                            <th>{{ __('Payment Date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($patient->pathologyTests as $test)
                            @if($test->balance == 0)
                            <tr>
                                <td>
                                    <a href="{{ route('pathology.test.show', $test->id) }}" class="text-decoration-none">{{ $test->bill_no }}</a>
                                </td>
                                <td>
                                    <div class="d-flex pe-22">
                                        @if(!empty($test->balance))
                                            {{ checkNumberFormat($test->balance, strtoupper(getCurrentCurrency())) }}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>
                                    <div class="badge bg-light-success">
                                        {{ \Carbon\Carbon::parse($test->created_at)->translatedFormat('jS M, Y')}}
                                    </div>
                                </td>
                                <td>
                                    <div class="badge bg-light-success">
                                        {{ \Carbon\Carbon::parse($test->updated_at)->translatedFormat('jS M, Y')}}
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Paid Pathology Tests</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paid Radiology Tests -->
                <div>
                    <h5>{{ __('Paid Radiology Tests') }}</h5>
                    <div id="paidRadiologyCount" data-count="{{ $patient->radiologyTests->where('balance', 0)->count() }}" style="display: none;"></div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('Invoice ID') }}</th>
                            <th>{{ __('Bill No') }}</th>
                            <th>{{ __('messages.bill.amount') }}</th>
                            <th>{{ __('messages.bill.payment_status') }}</th>
                            <th>{{ __('messages.bill.bill_date') }}</th>
                            <th>{{ __('Payment Date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($patient->radiologyTests as $test)
                            @if($test->balance == 0)
                            <tr>
                                <td><a href="{{ route('radiology.test.show', $test->id) }}" class="text-decoration-none">RAD-{{ $test->id }}</a></td>
                                <td>{{ $test->bill_no }}</td>
                                <td>
                                    <div class="d-flex pe-22">
                                        @if(!empty($test->balance))
                                            {{ checkNumberFormat($test->balance, strtoupper(getCurrentCurrency())) }}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>
                                    <div class="badge bg-light-success">
                                        {{ \Carbon\Carbon::parse($test->created_at)->translatedFormat('jS M, Y')}}
                                    </div>
                                </td>
                                <td>
                                    <div class="badge bg-light-success">
                                        {{ \Carbon\Carbon::parse($test->updated_at)->translatedFormat('jS M, Y')}}
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No Paid Radiology Tests</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paid Maternity Bills -->
                <div>
                    <h5>{{ __('Paid Maternity Bills') }}</h5>
                    <div id="paidMaternityCount" data-count="{{ $patient->maternity->filter(function($m) { return ($m->paid_amount ?? 0) >= ($m->standard_charge ?? 0) && ($m->standard_charge ?? 0) > 0; })->count() }}" style="display: none;"></div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('Invoice ID') }}</th>
                            <th>{{ __('messages.bill.amount') }}</th>
                            <th>{{ __('messages.bill.payment_status') }}</th>
                            <th>{{ __('messages.bill.bill_date') }}</th>
                            <th>{{ __('Payment Date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($patient->maternity as $maternity)
                            @if(($maternity->paid_amount ?? 0) >= ($maternity->standard_charge ?? 0) && ($maternity->standard_charge ?? 0) > 0)
                            <tr>
                                <td><a href="{{ route('maternity.patient.show', $maternity->id) }}" class="text-decoration-none">{{ $maternity->invoice_id ?? 'MAT-' . $maternity->id }}</a></td>
                                <td>
                                    <div class="d-flex pe-22">
                                        @if(!empty($maternity->standard_charge))
                                            {{ checkNumberFormat($maternity->standard_charge, strtoupper(getCurrentCurrency())) }}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>
                                    <div class="badge bg-light-primary">
                                        {{ \Carbon\Carbon::parse($maternity->created_at)->translatedFormat('jS M, Y')}}
                                    </div>
                                </td>
                                <td>
                                    <div class="badge bg-light-success">
                                        {{ \Carbon\Carbon::parse($maternity->updated_at)->translatedFormat('jS M, Y')}}
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Paid Maternity Bills</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div> <!-- End of paid tab -->
        </div> <!-- End of tab content -->
        {{-- <div id="currency">{{strtoupper(getCurrentCurrency())}}</div> --}}

    </div>

    <script>
        // Function to update totals for Medicine section
        let currency = $("#currency").text()

        // Function to update tab counts
        function updateTabCounts() {
            // Count unpaid bills using data attributes (accurate server-side counts)
            let unpaidCount = 0;
            unpaidCount += parseInt(document.getElementById('unpaidMedicineCount').dataset.count || 0);
            unpaidCount += parseInt(document.getElementById('unpaidIpdCount').dataset.count || 0);
            unpaidCount += parseInt(document.getElementById('unpaidOpdCount').dataset.count || 0);
            unpaidCount += parseInt(document.getElementById('unpaidPathologyCount').dataset.count || 0);
            unpaidCount += parseInt(document.getElementById('unpaidRadiologyCount').dataset.count || 0);
            unpaidCount += parseInt(document.getElementById('unpaidMaternityCount').dataset.count || 0);

            // Count paid bills using data attributes
            let paidCount = 0;
            paidCount += parseInt(document.getElementById('paidMedicineCount').dataset.count || 0);
            paidCount += parseInt(document.getElementById('paidIpdCount').dataset.count || 0);
            paidCount += parseInt(document.getElementById('paidOpdCount').dataset.count || 0);
            paidCount += parseInt(document.getElementById('paidPathologyCount').dataset.count || 0);
            paidCount += parseInt(document.getElementById('paidRadiologyCount').dataset.count || 0);
            paidCount += parseInt(document.getElementById('paidMaternityCount').dataset.count || 0);

            // Debug logging
            console.log('Unpaid counts:', {
                medicine: document.getElementById('unpaidMedicineCount').dataset.count || 0,
                ipd: document.getElementById('unpaidIpdCount').dataset.count || 0,
                opd: document.getElementById('unpaidOpdCount').dataset.count || 0,
                pathology: document.getElementById('unpaidPathologyCount').dataset.count || 0,
                radiology: document.getElementById('unpaidRadiologyCount').dataset.count || 0,
                maternity: document.getElementById('unpaidMaternityCount').dataset.count || 0,
                total: unpaidCount
            });

            // Update tab badges
            document.getElementById('unpaidCount').textContent = unpaidCount;
            document.getElementById('paidCount').textContent = paidCount;
        }

        // Function to handle tab switching
        function handleTabSwitch() {
            const unpaidTab = document.getElementById('unpaid-tab');
            const paidTab = document.getElementById('paid-tab');
            const selectAllContainer = document.getElementById('selectAllContainer');

            // Show/hide select all container based on active tab
            if (unpaidTab.classList.contains('active')) {
                selectAllContainer.style.display = 'block';
            } else {
                selectAllContainer.style.display = 'none';
            }
        }

        // Initialize tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners for tab switching
            const tabs = document.querySelectorAll('[data-bs-toggle="tab"]');
            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', handleTabSwitch);
            });

            // Initial count update
            updateTabCounts();
        });
        function updateMedicineTotals() {
            let subTotalMedicine = 0;

            // Calculate Medicine Total
            document.querySelectorAll('#medicineTable .select-row:checked').forEach(item => {
                subTotalMedicine += parseFloat(item.value);
            });
            document.getElementById('subTotalMedicine').innerText = currency + subTotalMedicine;
            document.getElementById('totalMedicine').innerText = currency + subTotalMedicine;

            // Update section select all checkbox state
            updateSectionSelectAllState('medicineTable');

            updateSummaryTotals();
            updateTabCounts();
        }

        // Function to update totals for IPD section
        function updateIPDTotals() {
            let subTotalIPD = 0;

            // Calculate IPD Total
            document.querySelectorAll('#ipdTable .select-row:checked').forEach(item => {
                subTotalIPD += parseFloat(item.value);
            });
            document.getElementById('subTotalIPD').innerText = currency + subTotalIPD;
            document.getElementById('totalIPD').innerText = currency + subTotalIPD;

            // Update section select all checkbox state
            updateSectionSelectAllState('ipdTable');

            updateSummaryTotals();
            updateTabCounts();
        }

        function updateOPDTotals() {
            let subTotalOPD = 0;

            // Calculate OPD Total
            document.querySelectorAll('#opdTable .select-row:checked').forEach(item => {
                subTotalOPD += parseFloat(item.value);
            });
            document.getElementById('subTotalOPD').innerText = currency + subTotalOPD;
            document.getElementById('totalOPD').innerText = currency + subTotalOPD;

            // Update section select all checkbox state
            updateSectionSelectAllState('opdTable');

            updateSummaryTotals();
            updateTabCounts();
        }

        // Function to update totals for Pathology section
                        function updatePathologyTotals() {
            let subTotalPathology = 0;

            // Calculate Pathology Total
            document.querySelectorAll('#pathologyTable .select-row:checked').forEach(item => {
                subTotalPathology += parseFloat(item.value);
            });

            // Update section select all checkbox state
            updateSectionSelectAllState('pathologyTable');

            updateSummaryTotals();
            updateTabCounts();
        }

        // Function to update totals for Radiology section
        function updateRadiologyTotals() {
            let subTotalRadiology = 0;

            // Calculate Radiology Total
            document.querySelectorAll('#radiologyTable .select-row:checked').forEach(item => {
                subTotalRadiology += parseFloat(item.value);
            });

            // Update section select all checkbox state
            updateSectionSelectAllState('radiologyTable');

            updateSummaryTotals();
            updateTabCounts();
        }



        // Function to update totals for Maternity section
        function updateMaternityTotals() {
            let subTotalMaternity = 0;

            // Calculate Maternity Total
            document.querySelectorAll('#maternityTable .select-row:checked').forEach(item => {
                subTotalMaternity += parseFloat(item.value);
            });
            document.getElementById('subTotalMaternity').innerText = currency + subTotalMaternity;
            document.getElementById('totalMaternity').innerText = currency + subTotalMaternity;

            // Update section select all checkbox state
            updateSectionSelectAllState('maternityTable');

            updateSummaryTotals();
            updateTabCounts();
        }

        function updateSummaryTotals() {
            const subTotalMedicine = parseFloat(document.getElementById('subTotalMedicine').innerText.replace(currency, '')) || 0;
            const subTotalIPD = parseFloat(document.getElementById('subTotalIPD').innerText.replace(currency, '')) || 0;
            const subTotalOPD = parseFloat(document.getElementById('subTotalOPD').innerText.replace(currency, '')) || 0;
            const subTotalPathology = parseFloat(document.getElementById('totalPathology').innerText.replace(currency, '')) || 0;
            const subTotalRadiology = parseFloat(document.getElementById('totalRadiology').innerText.replace(currency, '')) || 0;
            const subTotalMaternity = parseFloat(document.getElementById('subTotalMaternity').innerText.replace(currency, '')) || 0;

            const summarySubTotal = subTotalMedicine + subTotalIPD + subTotalOPD + subTotalPathology + subTotalRadiology + subTotalMaternity;
            document.getElementById('summarySubTotal').innerText = currency + summarySubTotal;
            document.getElementById('summaryTotal').innerText = currency + summarySubTotal;
        }

        // Helper function to update section select all checkbox states
        function updateSectionSelectAllState(tableId) {
            const table = document.getElementById(tableId);
            if (!table) return;

            const selectAllCheckbox = table.querySelector('thead input[type="checkbox"]');
            if (!selectAllCheckbox) return;

            const enabledCheckboxes = table.querySelectorAll('.select-row:not(:disabled)');
            const checkedEnabledCheckboxes = table.querySelectorAll('.select-row:not(:disabled):checked');

            if (checkedEnabledCheckboxes.length === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (checkedEnabledCheckboxes.length === enabledCheckboxes.length) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            }
        }

        function toggleSelectAllInSection(selectAllCheckbox, tableId) {
            const checkboxes = document.querySelectorAll(`#${tableId} .select-row:not(:disabled)`);
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            if (tableId === 'medicineTable') {
                updateMedicineTotals();
            } else if (tableId === 'ipdTable') {
                updateIPDTotals();
            } else if (tableId === 'opdTable') {
                updateOPDTotals();
            } else if (tableId === 'pathologyTable') {
                updatePathologyTotals();
            } else if (tableId === 'radiologyTable') {
                updateRadiologyTotals();
            } else if (tableId === 'maternityTable') {
                updateMaternityTotals();
            }
        }

        function toggleSelectAll(selectAllCheckbox) {
            const checkboxes = document.querySelectorAll('.select-row:not(:disabled)');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateMedicineTotals();
            updateIPDTotals();
            updateOPDTotals();
            updatePathologyTotals();
            updateRadiologyTotals();
            updateMaternityTotals();
        }

        function paySelected() {
            $.ajax({
                url: '{{ route('bills.paySelected', $patient->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    medicineBills: Array.from(document.querySelectorAll('#medicineTable .select-row:checked')).map(item => item.dataset.id),
                    ipdBills: Array.from(document.querySelectorAll('#ipdTable .select-row:checked')).map(item => item.dataset.id),
                    opdBills: Array.from(document.querySelectorAll('#opdTable .select-row:checked')).map(item => item.dataset.id),
                    pathologyBills: Array.from(document.querySelectorAll('#pathologyTable .select-row:checked')).map(item => item.dataset.id),
                    radiologyBills: Array.from(document.querySelectorAll('#radiologyTable .select-row:checked')).map(item => item.dataset.id),
                    maternityBills: Array.from(document.querySelectorAll('#maternityTable .select-row:checked')).map(item => item.dataset.id),
                },
                success: function(response) {
                    alert('Payment successful!');
                    location.reload();
                },
                error: function(xhr) {
                    let errorMessage = 'Payment failed. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            errorMessage = response.error || response.message || errorMessage;
                        } catch (e) {
                            // Keep default error message if JSON parsing fails
                        }
                    }
                    alert(errorMessage);
                    console.error('Payment error:', xhr);
                }
            });
        }
    </script>
@endsection
