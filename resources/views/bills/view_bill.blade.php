@extends('layouts.app')

@section('title')
    {{ __('messages.bill.bill_details') }}
@endsection

@section('content')
    <div class="container">
        <h2>{{ __('messages.bill.bill_details').' '.'For '.$patient->patientUser->first_name.' '.$patient->patientUser->last_name }}</h2>
        <hr>

        {{-- Hidden element to store currency for JS access --}}
        <div id="jsCurrency" style="display: none;">{{ strtoupper(getCurrentCurrency()) }}</div>

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

        <div class="tab-content" id="billsTabContent">
            <div class="tab-pane fade show active" id="unpaid" role="tabpanel" aria-labelledby="unpaid-tab">
                <div id="selectAllContainer" class="mb-3">
                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                    <label for="selectAll">{{ __('Select All Unpaid Bills') }}</label>
                </div>

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

                <div>
                    <h5>{{ __('Medicine Bill') }}
                    <div id="unpaidMedicineCount" data-count="{{ $patient->medicine_bills->where('payment_status', 0)->count() }}" style="display: none;"></div>

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
                        <td><input type="checkbox" class="select-row medicine-bill-item" data-id="{{ $bill->id }}" data-type="Medicine" data-bill-no="{{ $bill->bill_number }}" value="{{ $bill->total - ($bill->total * $bill->discount / 100) }}" onclick="updateMedicineTotals()"></td>
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

        <div>
            <h5>{{ __('IPD Bill') }}</h5>
            <div id="unpaidIpdCount" data-count="{{ $patient->ipdPatientDepartments->where('bill_status', 0)->count() }}" style="display: none;"></div>

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
                        <td><input type="checkbox" class="select-row ipd-bill-item" data-id="{{ $bill->id }}" data-type="IPD" data-bill-no="{{ $bill->ipd_number }}" value="{{ $bill->bill && $bill->bill->total_charges ? $bill->bill->total_charges - ($bill->bill->total * $bill->bill->discount / 100) : 0 }}" onclick="updateIPDTotals()"></td>
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

        <div>
            <h5>{{ __('OPD Bill') }}</h5>
            <div id="unpaidOpdCount" data-count="{{ $patient->invoices->where('status', \App\Models\Invoice::PENDING)->count() }}" style="display: none;"></div>

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
                        <td><input type="checkbox" class="select-row opd-bill-item" data-id="{{ $invoice->id }}" data-type="OPD" data-bill-no="{{ $invoice->invoice_id }}" value="{{ $invoice->amount - ($invoice->amount * $invoice->discount / 100) }}" onclick="updateOPDTotals()"></td>
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
                        <td><input type="checkbox" class="select-row pathology-bill-item" data-id="{{ $test->id }}" data-type="Pathology" data-bill-no="{{ $test->bill_no }}" value="{{ $test->balance }}" onclick="updatePathologyTotals()"></td>
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
                        <td><input type="checkbox" class="select-row radiology-bill-item" data-id="{{ $test->id }}" data-type="Radiology" data-bill-no="{{ $test->bill_no }}" value="{{ $test->balance }}" onclick="updateRadiologyTotals()"></td>
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

        <div>
            <h5>{{ __('Maternity Bills') }}</h5>
            <div id="unpaidMaternityCount" data-count="{{ $patient->maternity->filter(function($m) { return ($m->paid_amount ?? 0) < ($m->standard_charge ?? 0); })->count() }}" style="display: none;"></div>

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
                        <td><input type="checkbox" class="select-row maternity-bill-item" data-id="{{ $maternity->id }}" data-type="Maternity" data-bill-no="{{ $maternity->invoice_id ?? 'MAT-' . $maternity->id }}" value="{{ $maternity->standard_charge ?? 0 }}" onclick="updateMaternityTotals()"></td>
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
            </div> <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ __('This tab shows all paid bills. No payment actions are available for paid bills.') }}
                </div>

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
            </div> </div> </div>

    <div class="modal fade" id="paymentReceiptModal" tabindex="-1" aria-labelledby="paymentReceiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentReceiptModalLabel">{{ __('Payment Receipt Preview') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="receiptContent">
                    {{-- Receipt content will be dynamically inserted here --}}
                    <div class="text-center mb-4">
                        <h4>{{ __('Payment Receipt') }}</h4>
                        <p><strong>{{ __('Patient') }}:</strong> {{ $patient->patientUser->first_name.' '.$patient->patientUser->last_name }}</p>
                        <p><strong>{{ __('Date') }}:</strong> <span id="receiptDate"></span></p>
                    </div>

                    <h5 class="mb-3">{{ __('Selected Bills for Payment') }}</h5>
                    <table class="table table-bordered table-striped" id="receiptBillsTable">
                        <thead>
                            <tr>
                                <th>{{ __('Bill Type') }}</th>
                                <th>{{ __('Invoice ID') }}</th>
                                <th>{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Bill rows will be inserted here --}}
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #f0f0f0; font-weight: bold;">
                                <td colspan="2" class="text-end">{{ __('Total Amount') }}:</td>
                                <td id="receiptTotalAmount"></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="alert alert-warning mt-4">
                        {{ __('messages.common.confirm_payment_message') }}
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-info" onclick="printReceipt()">
                        <i class="fas fa-print me-2"></i> {{ __('Print Preview') }}
                    </button>
                    <button type="button" class="btn btn-success" id="confirmPaymentBtn" onclick="confirmPayment()">
                        <i class="fas fa-money-bill-wave me-2"></i> {{ __('Confirm Payment') }}
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Use a variable to get the currency symbol from the hidden div
        const currency = document.getElementById('jsCurrency').textContent.trim();

        // Function to update totals for Medicine section
        // Note: I've removed the redundant `let currency = $("#currency").text()` and fixed it to use the constant.

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

            // Update tab badges
            document.getElementById('unpaidCount').textContent = unpaidCount;
            document.getElementById('paidCount').textContent = paidCount;
        }

        // Function to handle tab switching
        function handleTabSwitch() {
            const unpaidTab = document.getElementById('unpaid-tab');
            const selectAllContainer = document.getElementById('selectAllContainer');

            // Show/hide select all container based on active tab
            // This is a correction from the original logic which checked classList.contains('active') on the button itself after the tab has shown
            const unpaidPane = document.getElementById('unpaid');
            if (unpaidPane && unpaidPane.classList.contains('active')) {
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

        // Helper function to format currency for display
        function formatCurrency(amount) {
            // Assuming checkNumberFormat is available globally from Laravel helpers
            // If not, a simpler formatting is used here:
            return currency + (Math.round(amount * 100) / 100).toFixed(2);
        }

        function updateMedicineTotals() {
            let subTotalMedicine = 0;

            // Calculate Medicine Total
            document.querySelectorAll('#medicineTable .select-row:checked').forEach(item => {
                subTotalMedicine += parseFloat(item.value);
            });
            document.getElementById('subTotalMedicine').innerText = formatCurrency(subTotalMedicine);
            document.getElementById('totalMedicine').innerText = formatCurrency(subTotalMedicine);

            // Update section select all checkbox state
            updateSectionSelectAllState('medicineTable');

            updateSummaryTotals();
        }

        // Function to update totals for IPD section
        function updateIPDTotals() {
            let subTotalIPD = 0;

            // Calculate IPD Total
            document.querySelectorAll('#ipdTable .select-row:checked').forEach(item => {
                subTotalIPD += parseFloat(item.value);
            });
            document.getElementById('subTotalIPD').innerText = formatCurrency(subTotalIPD);
            document.getElementById('totalIPD').innerText = formatCurrency(subTotalIPD);

            // Update section select all checkbox state
            updateSectionSelectAllState('ipdTable');

            updateSummaryTotals();
        }

        function updateOPDTotals() {
            let subTotalOPD = 0;

            // Calculate OPD Total
            document.querySelectorAll('#opdTable .select-row:checked').forEach(item => {
                subTotalOPD += parseFloat(item.value);
            });
            document.getElementById('subTotalOPD').innerText = formatCurrency(subTotalOPD);
            document.getElementById('totalOPD').innerText = formatCurrency(subTotalOPD);

            // Update section select all checkbox state
            updateSectionSelectAllState('opdTable');

            updateSummaryTotals();
        }

        // Function to update totals for Pathology section
        function updatePathologyTotals() {
            let subTotalPathology = 0;

            // Calculate Pathology Total
            document.querySelectorAll('#pathologyTable .select-row:checked').forEach(item => {
                subTotalPathology += parseFloat(item.value);
            });
            document.getElementById('subTotalPathology').innerText = formatCurrency(subTotalPathology);
            document.getElementById('totalPathology').innerText = formatCurrency(subTotalPathology);

            // Update section select all checkbox state
            updateSectionSelectAllState('pathologyTable');

            updateSummaryTotals();
        }

        // Function to update totals for Radiology section
        function updateRadiologyTotals() {
            let subTotalRadiology = 0;

            // Calculate Radiology Total
            document.querySelectorAll('#radiologyTable .select-row:checked').forEach(item => {
                subTotalRadiology += parseFloat(item.value);
            });
            document.getElementById('subTotalRadiology').innerText = formatCurrency(subTotalRadiology);
            document.getElementById('totalRadiology').innerText = formatCurrency(subTotalRadiology);

            // Update section select all checkbox state
            updateSectionSelectAllState('radiologyTable');

            updateSummaryTotals();
        }


        // Function to update totals for Maternity section
        function updateMaternityTotals() {
            let subTotalMaternity = 0;

            // Calculate Maternity Total
            document.querySelectorAll('#maternityTable .select-row:checked').forEach(item => {
                subTotalMaternity += parseFloat(item.value);
            });
            document.getElementById('subTotalMaternity').innerText = formatCurrency(subTotalMaternity);
            document.getElementById('totalMaternity').innerText = formatCurrency(subTotalMaternity);

            // Update section select all checkbox state
            updateSectionSelectAllState('maternityTable');

            updateSummaryTotals();
        }

        function updateSummaryTotals() {
            // Function to safely extract float value from currency-formatted string
            const extractFloat = (elementId) => {
                const text = document.getElementById(elementId).innerText;
                // Use a regex to remove the currency symbol and parse the number
                return parseFloat(text.replace(new RegExp(currency.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'g'), '')) || 0;
            };

            const subTotalMedicine = extractFloat('subTotalMedicine');
            const subTotalIPD = extractFloat('subTotalIPD');
            const subTotalOPD = extractFloat('subTotalOPD');
            const subTotalPathology = extractFloat('subTotalPathology'); // Using subTotal for consistency, though it was totalPathology in original JS
            const subTotalRadiology = extractFloat('subTotalRadiology'); // Using subTotal for consistency, though it was totalRadiology in original JS
            const subTotalMaternity = extractFloat('subTotalMaternity');

            const summarySubTotal = subTotalMedicine + subTotalIPD + subTotalOPD + subTotalPathology + subTotalRadiology + subTotalMaternity;
            document.getElementById('summarySubTotal').innerText = formatCurrency(summarySubTotal);
            document.getElementById('summaryTotal').innerText = formatCurrency(summarySubTotal);
        }

        // Helper function to update section select all checkbox states
        function updateSectionSelectAllState(tableId) {
            const table = document.getElementById(tableId);
            if (!table) return;

            const selectAllCheckbox = table.querySelector('thead input[type="checkbox"]');
            if (!selectAllCheckbox) return;

            const enabledCheckboxes = table.querySelectorAll('.select-row:not(:disabled)');
            const checkedEnabledCheckboxes = table.querySelectorAll('.select-row:not(:disabled):checked');

            if (enabledCheckboxes.length === 0) {
                 selectAllCheckbox.checked = false;
                 selectAllCheckbox.indeterminate = false;
                 return;
            }

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

            // Also update the main 'Select All' checkbox state
            updateMainSelectAllState();
        }

        // New function to update the state of the main 'Select All' checkbox
        function updateMainSelectAllState() {
            const mainSelectAll = document.getElementById('selectAll');
            if (!mainSelectAll) return;

            const allCheckboxes = document.querySelectorAll('.select-row:not(:disabled)');
            const checkedCheckboxes = document.querySelectorAll('.select-row:not(:disabled):checked');

            if (allCheckboxes.length === 0) {
                mainSelectAll.checked = false;
                mainSelectAll.indeterminate = false;
            } else if (checkedCheckboxes.length === 0) {
                mainSelectAll.checked = false;
                mainSelectAll.indeterminate = false;
            } else if (checkedCheckboxes.length === allCheckboxes.length) {
                mainSelectAll.checked = true;
                mainSelectAll.indeterminate = false;
            } else {
                mainSelectAll.checked = false;
                mainSelectAll.indeterminate = true;
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
            updateMainSelectAllState();
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
            updateMainSelectAllState(); // Redundant here but safe
        }

        // NEW: Function to prepare and show the payment modal
        function paySelected() {
            const selectedBills = [];
            let totalAmount = 0;

            document.querySelectorAll('.select-row:checked').forEach(item => {
                const amount = parseFloat(item.value);
                const billType = item.dataset.type;
                const billId = item.dataset.billNo;

                if (amount > 0) {
                    selectedBills.push({
                        id: item.dataset.id,
                        type: billType,
                        bill_no: billId,
                        amount: amount
                    });
                    totalAmount += amount;
                }
            });

            if (selectedBills.length === 0) {
                alert('{{ __("Please select at least one unpaid bill to proceed with payment.") }}');
                return;
            }

            // Populate the modal
            const tbody = document.getElementById('receiptBillsTable').querySelector('tbody');
            tbody.innerHTML = '';
            selectedBills.forEach(bill => {
                const row = `<tr>
                                <td>${bill.type}</td>
                                <td>${bill.bill_no}</td>
                                <td>${formatCurrency(bill.amount)}</td>
                             </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });

            document.getElementById('receiptTotalAmount').textContent = formatCurrency(totalAmount);
            document.getElementById('receiptDate').textContent = new Date().toLocaleDateString();

            // Store the data needed for the final payment in the modal's data attribute (or a global variable)
            $('#paymentReceiptModal').data('selected-bills', selectedBills);

            // Show the modal
            const receiptModal = new bootstrap.Modal(document.getElementById('paymentReceiptModal'));
            receiptModal.show();
        }

        // NEW: Function to handle the actual AJAX payment after confirmation
        function confirmPayment() {
            const selectedBills = $('#paymentReceiptModal').data('selected-bills');

            if (!selectedBills || selectedBills.length === 0) {
                alert('No bills were selected for payment.');
                return;
            }

            // Map the selected bills into the structure needed by the backend controller
            const dataToSend = {
                _token: '{{ csrf_token() }}',
                medicineBills: selectedBills.filter(b => b.type === 'Medicine').map(b => b.id),
                ipdBills: selectedBills.filter(b => b.type === 'IPD').map(b => b.id),
                opdBills: selectedBills.filter(b => b.type === 'OPD').map(b => b.id),
                pathologyBills: selectedBills.filter(b => b.type === 'Pathology').map(b => b.id),
                radiologyBills: selectedBills.filter(b => b.type === 'Radiology').map(b => b.id),
                maternityBills: selectedBills.filter(b => b.type === 'Maternity').map(b => b.id),
            };

            // Disable the confirm button to prevent double-click
            $('#confirmPaymentBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __("Processing...") }}');

            $.ajax({
                url: '{{ route('bills.paySelected', $patient->id) }}',
                type: 'POST',
                data: dataToSend,
                success: function(response) {
                    alert('Payment successful!');
                    // Hide the modal and reload the page to show paid bills
                    $('#paymentReceiptModal').modal('hide');
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

                    // Re-enable the button
                    $('#confirmPaymentBtn').prop('disabled', false).html('<i class="fas fa-money-bill-wave me-2"></i> {{ __("Confirm Payment") }}');
                }
            });
        }

        // NEW: Function to handle printing
        function printReceipt() {
            const printContent = document.getElementById('receiptContent').innerHTML;
            const originalBody = document.body.innerHTML;

            // Prepare content for printing
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>{{ __("Payment Receipt") }}</title>');
            // Include Bootstrap CSS for basic styling
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
            printWindow.document.write('<style>@media print { .alert-warning { display: none; } }</style>'); // Hide the warning message in print
            printWindow.document.write('</head><body>');
            printWindow.document.write(printContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Wait for all content to load before printing
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
            };
        }
    </script>
@endsection