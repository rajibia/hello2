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
                        <div class="ms-2">
                            <button type="button" id="paySelectedBtn" class="btn btn-primary" onclick="paySelected()">
                                <i class="fas fa-money-bill-wave me-2"></i> {{ __('Pay Selected') }}
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Summary Totals (per section) --}}
                <div class="mb-3">
                    <div class="card">
                        <div class="card-body d-flex flex-wrap gap-3 align-items-center">
                            <div><strong>Medicine:</strong> <span id="subTotalMedicine">{{ strtoupper(getCurrentCurrency()) }}0.00</span><span id="totalMedicine" style="display:none">{{ strtoupper(getCurrentCurrency()) }}0.00</span></div>
                            <div><strong>IPD:</strong> <span id="subTotalIPD">{{ strtoupper(getCurrentCurrency()) }}0.00</span><span id="totalIPD" style="display:none">{{ strtoupper(getCurrentCurrency()) }}0.00</span></div>
                            <div><strong>OPD:</strong> <span id="subTotalOPD">{{ strtoupper(getCurrentCurrency()) }}0.00</span><span id="totalOPD" style="display:none">{{ strtoupper(getCurrentCurrency()) }}0.00</span></div>
                            <div><strong>Pathology:</strong> <span id="subTotalPathology">{{ strtoupper(getCurrentCurrency()) }}0.00</span><span id="totalPathology" style="display:none">{{ strtoupper(getCurrentCurrency()) }}0.00</span></div>
                            <div><strong>Radiology:</strong> <span id="subTotalRadiology">{{ strtoupper(getCurrentCurrency()) }}0.00</span><span id="totalRadiology" style="display:none">{{ strtoupper(getCurrentCurrency()) }}0.00</span></div>
                            <div><strong>Maternity:</strong> <span id="subTotalMaternity">{{ strtoupper(getCurrentCurrency()) }}0.00</span><span id="totalMaternity" style="display:none">{{ strtoupper(getCurrentCurrency()) }}0.00</span></div>
                            <div class="ms-auto"><strong>Summary:</strong> <span id="summarySubTotal">{{ strtoupper(getCurrentCurrency()) }}0.00</span><span id="summaryTotal" style="display:none">{{ strtoupper(getCurrentCurrency()) }}0.00</span></div>
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
                    @php
                        $medStatus = $bill->payment_status;
                    @endphp
                    @if($medStatus != \App\Models\MedicineBill::FULLPAID)
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
                        <td>
                            @php
                                if ($medStatus == \App\Models\MedicineBill::FULLPAID) {
                                    $badgeClass = 'success';
                                    $label = __('Paid');
                                } elseif ($medStatus == \App\Models\MedicineBill::PARTIALY_PAID) {
                                    $badgeClass = 'warning';
                                    $label = __('Partial');
                                } else {
                                    $badgeClass = 'danger';
                                    $label = __('Unpaid');
                                }
                            @endphp
                            <span class="badge bg-{{ $badgeClass }}">{{ $label }}
                                @if(isset($bill->paid_amount) && $bill->paid_amount > 0)
                                    - {{ checkNumberFormat($bill->paid_amount, strtoupper(getCurrentCurrency())) }}
                                @endif
                            </span>
                        </td>
                        <td>
                            <div class="badge bg-light-info">
                                {{ \Carbon\Carbon::parse($bill->bill_date)->translatedFormat('jS M, Y')}}
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

                {{-- Unpaid IPD Bills --}}
                <div>
                    <h5>{{ __('IPD Bills (Unpaid)') }}</h5>
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
                                <td><input type="checkbox" class="select-row" onclick="updateIPDTotals()" data-id="{{ $bill->id }}" data-type="IPD" data-bill-no="{{ $bill->ipd_number }}" value="{{ $bill->bill ? ($bill->bill->total_charges - ($bill->bill->discount ?? 0)) : 0 }}"></td>
                                <td>
                                    <a href="{{ route('ipd.patient.show', $bill->id) }}">
                                        {{$bill->ipd_number}}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex pe-22">
                                        @if($bill->bill && !empty($bill->bill->total_charges))
                                            {{ checkNumberFormat($bill->bill->total_charges - ($bill->bill->discount ?? 0), strtoupper(getCurrentCurrency())) }}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td><span class="badge bg-warning">Unpaid</span></td>
                                <td>
                                    <div class="badge bg-light-info">
                                        @if($bill->bill && !empty($bill->bill->created_at))
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
                                <td colspan="5" class="text-center">No Unpaid IPD Bills</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Unpaid OPD Bills --}}
                <div>
                    <h5>{{ __('OPD Bills (Unpaid)') }}</h5>
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
                                <td><input type="checkbox" class="select-row" onclick="updateOPDTotals()" data-id="{{ $invoice->id }}" data-type="OPD" data-bill-no="{{ $invoice->invoice_id }}" value="{{ $invoice->amount - ($invoice->discount ?? 0) }}"></td>
                                <td>
                                    <a href="{{ getLoggedinPatient() ? url('employee/invoices'). '/' . $invoice->id : route('invoices.show',$invoice->id) }}">
                                        {{$invoice->invoice_id}}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex pe-22">
                                        @if(!empty($invoice->amount))
                                            {{ checkNumberFormat($invoice->amount - ($invoice->discount ?? 0), strtoupper(getCurrentCurrency())) }}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td><span class="badge bg-warning">Unpaid</span></td>
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
                </div>

                {{-- Unpaid Pathology Tests --}}
                <div>
                    <h5>{{ __('Pathology Tests (Unpaid)') }}</h5>
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
                                <td><input type="checkbox" class="select-row" onclick="updatePathologyTotals()" data-id="{{ $test->id }}" data-type="Pathology" data-bill-no="{{ $test->bill_no }}" value="{{ $test->balance }}"></td>
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
                                <td><span class="badge bg-warning">Unpaid</span></td>
                                <td>
                                    <div class="badge bg-light-info">
                                        {{ \Carbon\Carbon::parse($test->created_at)->translatedFormat('jS M, Y') }}
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
                </div>

                {{-- Unpaid Radiology Tests --}}
                <div>
                    <h5>{{ __('Radiology Tests (Unpaid)') }}</h5>
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
                                <td><input type="checkbox" class="select-row" onclick="updateRadiologyTotals()" data-id="{{ $test->id }}" data-type="Radiology" data-bill-no="RAD-{{ $test->id }}" value="{{ $test->balance }}"></td>
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
                                <td><span class="badge bg-warning">Unpaid</span></td>
                                <td>
                                    <div class="badge bg-light-info">
                                        {{ \Carbon\Carbon::parse($test->created_at)->translatedFormat('jS M, Y') }}
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
                </div>

                {{-- Unpaid Maternity Bills --}}
                <div>
                    <h5>{{ __('Maternity Bills (Unpaid)') }}</h5>
                    <div id="unpaidMaternityCount" data-count="{{ $patient->maternity->filter(function($m) { return ($m->paid_amount ?? 0) < ($m->standard_charge ?? 0) && ($m->standard_charge ?? 0) > 0; })->count() }}" style="display: none;"></div>
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
                            @if(($maternity->paid_amount ?? 0) < ($maternity->standard_charge ?? 0) && ($maternity->standard_charge ?? 0) > 0)
                            <tr>
                                <td><input type="checkbox" class="select-row" onclick="updateMaternityTotals()" data-id="{{ $maternity->id }}" data-type="Maternity" data-bill-no="{{ $maternity->invoice_id ?? 'MAT-' . $maternity->id }}" value="{{ $maternity->standard_charge - ($maternity->paid_amount ?? 0) }}"></td>
                                <td><a href="{{ route('maternity.patient.show', $maternity->id) }}" class="text-decoration-none">{{ $maternity->invoice_id ?? 'MAT-' . $maternity->id }}</a></td>
                                <td>
                                    <div class="d-flex pe-22">
                                        @if(!empty($maternity->standard_charge))
                                            {{ checkNumberFormat($maternity->standard_charge - ($maternity->paid_amount ?? 0), strtoupper(getCurrentCurrency())) }}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td><span class="badge bg-warning">Unpaid</span></td>
                                <td>
                                    <div class="badge bg-light-info">
                                        {{ \Carbon\Carbon::parse($maternity->created_at)->translatedFormat('jS M, Y') }}
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
                </div>
            </div>

            <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
                <div class="mb-3">
                    <!-- Paid Bills Summary Totals -->
                </div>

                <div>
                    <h5>{{ __('Paid Medicine Bills') }}</h5>
                    <div id="paidMedicineCount" data-count="{{ $patient->medicine_bills->where('payment_status', \App\Models\MedicineBill::FULLPAID)->count() }}" style="display: none;"></div>
                    <table id="paidMedicineTable" class="table table-bordered table-striped">
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
                            @if($bill->payment_status == \App\Models\MedicineBill::FULLPAID)
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
                    <table id="paidIpdTable" class="table table-bordered table-striped">
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
                                        @php
                                            $billRec = $bill->bill ?? null;
                                            $ipdPaid = $billRec->total_payments ?? 0;
                                            $ipdTotal = $billRec->total_charges ?? 0;
                                        @endphp
                                        @if($billRec && $ipdTotal > 0)
                                            {{ checkNumberFormat(max(0, $ipdTotal - ($billRec->discount ?? 0)), strtoupper(getCurrentCurrency())) }}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        if ($ipdTotal > 0 && $ipdPaid >= $ipdTotal) {
                                            $badgeClass = 'success'; $label = __('Paid');
                                        } elseif ($ipdPaid > 0) {
                                            $badgeClass = 'warning'; $label = __('Partial');
                                        } else {
                                            $badgeClass = 'danger'; $label = __('Unpaid');
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ $label }}
                                        @if($ipdPaid > 0)
                                            - {{ checkNumberFormat($ipdPaid, strtoupper(getCurrentCurrency())) }}
                                        @endif
                                    </span>
                                </td>
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
                    <table id="paidOpdTable" class="table table-bordered table-striped">
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
                                        @php
                                            $invTotal = $invoice->amount ?? 0;
                                            $invPaid = is_array($invoice->paid_amount) ? array_sum($invoice->paid_amount) : ($invoice->paid_amount ?? 0);
                                        @endphp
                                        @if($invTotal > 0)
                                            {{ checkNumberFormat($invTotal - ($invoice->discount ?? 0), strtoupper(getCurrentCurrency())) }}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        if ($invTotal > 0 && $invPaid >= $invTotal) {
                                            $badgeClass = 'success'; $label = __('Paid');
                                        } elseif ($invPaid > 0) {
                                            $badgeClass = 'warning'; $label = __('Partial');
                                        } else {
                                            $badgeClass = 'danger'; $label = __('Unpaid');
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ $label }}
                                        @if($invPaid > 0)
                                            - {{ checkNumberFormat($invPaid, strtoupper(getCurrentCurrency())) }}
                                        @endif
                                    </span>
                                </td>
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
                    <table id="paidPathologyTable" class="table table-bordered table-striped">
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
                                        @php
                                            $pathPaid = $test->amount_paid ?? 0;
                                            $pathBalance = $test->balance ?? 0;
                                            $pathTotal = $pathPaid + $pathBalance;
                                        @endphp
                                        @if($pathTotal > 0)
                                            {{ checkNumberFormat($pathTotal, strtoupper(getCurrentCurrency())) }}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        if ($pathBalance == 0) {
                                            $badgeClass = 'success'; $label = __('Paid');
                                        } elseif ($pathPaid > 0) {
                                            $badgeClass = 'warning'; $label = __('Partial');
                                        } else {
                                            $badgeClass = 'danger'; $label = __('Unpaid');
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ $label }}
                                        @if($pathPaid > 0)
                                            - {{ checkNumberFormat($pathPaid, strtoupper(getCurrentCurrency())) }}
                                        @endif
                                    </span>
                                </td>
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
                    <table id="paidRadiologyTable" class="table table-bordered table-striped">
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
                                        @php
                                            $radPaid = $test->amount_paid ?? 0;
                                            $radBalance = $test->balance ?? 0;
                                            $radTotal = $radPaid + $radBalance;
                                        @endphp
                                        @if($radTotal > 0)
                                            {{ checkNumberFormat($radTotal, strtoupper(getCurrentCurrency())) }}
                                        @else
                                            {{ __('messages.common.n/a') }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        if ($radBalance == 0) {
                                            $badgeClass = 'success'; $label = __('Paid');
                                        } elseif ($radPaid > 0) {
                                            $badgeClass = 'warning'; $label = __('Partial');
                                        } else {
                                            $badgeClass = 'danger'; $label = __('Unpaid');
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ $label }}
                                        @if($radPaid > 0)
                                            - {{ checkNumberFormat($radPaid, strtoupper(getCurrentCurrency())) }}
                                        @endif
                                    </span>
                                </td>
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
                    <table id="paidMaternityTable" class="table table-bordered table-striped">
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
            </div>
        </div>
    </div>

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

                    <div class="row g-3 align-items-center mt-3">
                        <div class="col-auto">
                            <label for="receiptAmountTendered" class="col-form-label">{{ __('Amount Paid') }}</label>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <span class="input-group-text">{{ strtoupper(getCurrentCurrency()) }}</span>
                                <input type="number" id="receiptAmountTendered" class="form-control" min="0" step="0.01" value="0.00">
                            </div>
                        </div>
                        <div class="col-auto">
                            <div><strong>{{ __('Change / Balance') }}:</strong> <span id="receiptChange">{{ strtoupper(getCurrentCurrency()) }}0.00</span></div>
                        </div>
                    </div>

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
            
            // Ensure correct visibility on page load
            handleTabSwitch();
        });

        // Recompute per-section paid/unpaid counts from DOM and update data-count attributes
        function refreshCountsFromDOM() {
            const sections = [
                {unpaidSel: '#medicineTable .select-row, #medicineTable .medicine-bill-item', unpaidElem: 'unpaidMedicineCount', paidElem: 'paidMedicineCount', paidTable: 'paidMedicineTable'},
                {unpaidSel: '#ipdTable .select-row', unpaidElem: 'unpaidIpdCount', paidElem: 'paidIpdCount', paidTable: 'paidIpdTable'},
                {unpaidSel: '#opdTable .select-row', unpaidElem: 'unpaidOpdCount', paidElem: 'paidOpdCount', paidTable: 'paidOpdTable'},
                {unpaidSel: '#pathologyTable .select-row', unpaidElem: 'unpaidPathologyCount', paidElem: 'paidPathologyCount', paidTable: 'paidPathologyTable'},
                {unpaidSel: '#radiologyTable .select-row', unpaidElem: 'unpaidRadiologyCount', paidElem: 'paidRadiologyCount', paidTable: 'paidRadiologyTable'},
                {unpaidSel: '#maternityTable .select-row', unpaidElem: 'unpaidMaternityCount', paidElem: 'paidMaternityCount', paidTable: 'paidMaternityTable'}
            ];

            sections.forEach(s => {
                try {
                    const unpaidCount = document.querySelectorAll(s.unpaidSel).length || 0;
                    const paidTbl = document.getElementById(s.paidTable);
                    let paidCount = 0;
                    if (paidTbl && paidTbl.querySelectorAll) {
                        const rows = paidTbl.querySelectorAll('tbody tr');
                        rows.forEach(r => {
                            // consider row a real paid row if it contains an anchor (invoice link) or a number cell
                            if (r.querySelector('a') || r.querySelector('.badge.bg-success')) {
                                paidCount += 1;
                            }
                        });
                    }

                    const unpaidElem = document.getElementById(s.unpaidElem);
                    const paidElem = document.getElementById(s.paidElem);
                    if (unpaidElem) unpaidElem.dataset.count = unpaidCount;
                    if (paidElem) paidElem.dataset.count = paidCount;
                } catch (e) {
                    // ignore section errors
                    console.error('refreshCountsFromDOM error for', s, e);
                }
            });

            // finally update the top badges
            updateTabCounts();
        }

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

                        // Populate the modal with non-editable amount display and hidden inputs
                        const tbody = document.getElementById('receiptBillsTable').querySelector('tbody');
                        tbody.innerHTML = '';
                        selectedBills.forEach((bill, index) => {
                                // Amount is displayed as fixed text; a hidden input carries the value for submission
                                const safeAmount = (Math.round(bill.amount * 100) / 100).toFixed(2);
                                const row = `<tr>
                                                                <td>${bill.type}</td>
                                                                <td>${bill.bill_no}</td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">${currency}</span>
                                                                        <input type="text" readonly class="form-control-plaintext" value="${safeAmount}">
                                                                        <input type="hidden" class="receipt-amount-input" data-id="${bill.id}" data-type="${bill.type}" data-max="${safeAmount}" value="${safeAmount}">
                                                                    </div>
                                                                    <div class="text-muted small mt-1">Balance: <span class="receipt-balance" data-id="${bill.id}">${currency}0.00</span></div>
                                                                </td>
                                                         </tr>`;
                                tbody.insertAdjacentHTML('beforeend', row);
                        });

            // Set receipt date
            document.getElementById('receiptDate').textContent = new Date().toLocaleDateString();

            // Recalc totals by summing the (non-editable) hidden per-row amounts
            function recalcReceiptTotal() {
                let newTotal = 0;
                const inputs = document.querySelectorAll('.receipt-amount-input');
                inputs.forEach(input => {
                    const val = parseFloat(input.value) || 0;
                    const max = parseFloat(input.getAttribute('data-max')) || val;

                    // update balance display for this row (always 0 since amount is fixed)
                    const balanceEl = document.querySelector('.receipt-balance[data-id="' + input.dataset.id + '"]');
                    const remaining = Math.round((max - val) * 100) / 100;
                    if (balanceEl) {
                        if (remaining >= 0) {
                            balanceEl.textContent = currency + remaining.toFixed(2);
                        } else {
                            balanceEl.textContent = 'Overpaid ' + currency + Math.abs(remaining).toFixed(2);
                        }
                    }

                    newTotal += val;
                });

                // Update total display
                document.getElementById('receiptTotalAmount').textContent = formatCurrency(newTotal);

                // Compute tendered / change
                const tenderEl = document.getElementById('receiptAmountTendered');
                let tenderedVal = 0;
                if (tenderEl) {
                    tenderedVal = parseFloat(tenderEl.value) || 0;
                }

                const diff = Math.round((tenderedVal - newTotal) * 100) / 100;
                const changeEl = document.getElementById('receiptChange');
                if (changeEl) {
                    if (diff >= 0) {
                        changeEl.textContent = currency + diff.toFixed(2);
                    } else {
                        changeEl.textContent = currency + Math.abs(diff).toFixed(2);
                    }
                }

                // Enable confirm button when there is at least one bill
                const confirmBtn = document.getElementById('confirmPaymentBtn');
                if (confirmBtn) confirmBtn.disabled = !(newTotal > 0);
                return newTotal > 0;
            }

            // initial calc
            recalcReceiptTotal();

            // Set the tendered input default to the total amount and watch for changes
            const tenderEl = document.getElementById('receiptAmountTendered');
            if (tenderEl) {
                tenderEl.value = (Math.round(totalAmount * 100) / 100).toFixed(2);
                tenderEl.addEventListener('change', function () {
                    // normalize precision on blur/change only
                    if (this.value === '') return recalcReceiptTotal();
                    const numVal = parseFloat(this.value) || 0;
                    this.value = (Math.round(numVal * 100) / 100).toFixed(2);
                    recalcReceiptTotal();
                });
                tenderEl.addEventListener('input', function () {
                    // just recalc while typing, don't format yet
                    recalcReceiptTotal();
                });
            }

            // Store the data needed for the final payment in the modal's data attribute
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

            // Collect amounts from the modal inputs and build structured payload
            const inputs = document.querySelectorAll('.receipt-amount-input');
            if (!inputs || inputs.length === 0) {
                alert('No bill amounts found.');
                return;
            }

            const grouped = {
                medicineBills: [],
                ipdBills: [],
                opdBills: [],
                pathologyBills: [],
                radiologyBills: [],
                maternityBills: []
            };

            let valid = true;
            inputs.forEach(input => {
                const id = input.dataset.id;
                const type = input.dataset.type;
                const amount = parseFloat(input.value) || 0;
                const max = parseFloat(input.getAttribute('data-max')) || 0;
                if (amount <= 0) {
                    valid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
                const obj = {id: id, amount: amount};
                if (type === 'Medicine') grouped.medicineBills.push(obj);
                else if (type === 'IPD') grouped.ipdBills.push(obj);
                else if (type === 'OPD') grouped.opdBills.push(obj);
                else if (type === 'Pathology') grouped.pathologyBills.push(obj);
                else if (type === 'Radiology') grouped.radiologyBills.push(obj);
                else if (type === 'Maternity') grouped.maternityBills.push(obj);
            });

            if (!valid) {
                // focus first invalid input and scroll it into view for better UX
                const firstInvalid = document.querySelector('.receipt-amount-input.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({behavior: 'smooth', block: 'center'});
                }
                alert('{{ "Please correct invalid payment amounts before confirming." }}');
                return;
            }

            // Prepare payload as JSON and include CSRF header
            const payload = Object.assign({}, grouped);

            // include tendered and change/remaining in payload
            const totalPaid = (function(){
                let s = 0;
                document.querySelectorAll('.receipt-amount-input').forEach(i => { s += parseFloat(i.value) || 0; });
                return Math.round(s * 100) / 100;
            })();
            const tenderedVal = parseFloat(document.getElementById('receiptAmountTendered')?.value) || 0;
            const changeVal = Math.round((tenderedVal - totalPaid) * 100) / 100;
            payload.total = totalPaid;
            payload.tendered = tenderedVal;
            payload.change = changeVal > 0 ? changeVal : 0;
            payload.remaining = changeVal < 0 ? Math.abs(changeVal) : 0;

            // Disable the confirm button to prevent double-click
            const $confirmBtn = $('#confirmPaymentBtn');
            $confirmBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ "Processing..." }}');

            $.ajax({
                url: '{{ route('bills.paySelected', $patient->id) }}',
                type: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: JSON.stringify(payload),
                success: function(response) {
                    // Payment succeeded on server — update UI without full page reload
                    const selectedBills = $('#paymentReceiptModal').data('selected-bills') || [];
                    const today = new Date().toLocaleDateString();

                    // Tally top-level totals so badges update immediately
                    let totalUnpaid = parseInt(document.getElementById('unpaidCount')?.textContent || '0', 10) || 0;
                    let totalPaid = parseInt(document.getElementById('paidCount')?.textContent || '0', 10) || 0;

                    // For each paid bill, move its row from unpaid table into paid table
                    selectedBills.forEach(bill => {
                        // Try to find the checkbox row in unpaid tables
                        let selector = `.select-row[data-id="${bill.id}"][data-type="${bill.type}"]`;
                        let checkbox = document.querySelector(selector);
                        if (!checkbox && bill.type === 'Medicine') {
                            checkbox = document.querySelector(`.medicine-bill-item[data-id="${bill.id}"]`);
                        }
                        if (!checkbox) return; // row not found in DOM

                        const row = checkbox.closest('tr');
                        if (!row) return;

                        // Clone and adapt for paid table (remove checkbox cell, mark Paid, append payment date)
                        const cloned = row.cloneNode(true);
                        // remove any checkbox cell if present (first td)
                        const firstTd = cloned.querySelector('td');
                        if (firstTd) firstTd.remove();

                        // update badge to Paid
                        const badge = cloned.querySelector('.badge');
                        if (badge) {
                            badge.className = 'badge bg-success';
                            badge.textContent = 'Paid';
                        }

                        // append payment date cell if table expects it
                        const payDateTd = document.createElement('td');
                        payDateTd.innerHTML = `<div class="badge bg-light-success">${today}</div>`;

                        cloned.appendChild(payDateTd);

                        // Append to respective paid table
                        function paidTableId(type) {
                            switch (type) {
                                case 'Medicine': return 'paidMedicineTable';
                                case 'IPD': return 'paidIpdTable';
                                case 'OPD': return 'paidOpdTable';
                                case 'Pathology': return 'paidPathologyTable';
                                case 'Radiology': return 'paidRadiologyTable';
                                case 'Maternity': return 'paidMaternityTable';
                                default: return null;
                            }
                        }

                        const paidTbl = document.getElementById(paidTableId(bill.type));
                        if (paidTbl && paidTbl.querySelector('tbody')) {
                            paidTbl.querySelector('tbody').appendChild(cloned);
                        }

                        // Remove original unpaid row
                        row.remove();

                        // update top-level totals immediately
                        totalUnpaid = Math.max(0, totalUnpaid - 1);
                        totalPaid = totalPaid + 1;

                        // Update hidden counters
                        const mapSuffix = {
                            'Medicine': 'Medicine', 'IPD': 'Ipd', 'OPD': 'Opd', 'Pathology': 'Pathology', 'Radiology': 'Radiology', 'Maternity': 'Maternity'
                        };
                        const suf = mapSuffix[bill.type] || bill.type;
                        const unpaidElem = document.getElementById(`unpaid${suf}Count`);
                        const paidElem = document.getElementById(`paid${suf}Count`);
                        if (unpaidElem) {
                            const cur = parseInt(unpaidElem.dataset.count || '0', 10);
                            unpaidElem.dataset.count = Math.max(0, cur - 1);
                        }
                        if (paidElem) {
                            const cur2 = parseInt(paidElem.dataset.count || '0', 10);
                            paidElem.dataset.count = cur2 + 1;
                        }
                    });

                    // Update top badges immediately
                    const unpaidBadge = document.getElementById('unpaidCount');
                    const paidBadge = document.getElementById('paidCount');
                    if (unpaidBadge) unpaidBadge.textContent = totalUnpaid;
                    if (paidBadge) paidBadge.textContent = totalPaid;

                    // Refresh counts from DOM and summaries
                    refreshCountsFromDOM();
                    updateSummaryTotals();
                    updateMedicineTotals();
                    updateIPDTotals();
                    updateOPDTotals();
                    updatePathologyTotals();
                    updateRadiologyTotals();
                    updateMaternityTotals();

                    // Cleanup and UI feedback
                    $('#paymentReceiptModal').modal('hide');
                    $('#paymentReceiptModal').data('selected-bills', []);
                    alert('Payment successful! View updated without page reload.');
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
                    $confirmBtn.prop('disabled', false).html('<i class="fas fa-money-bill-wave me-2"></i> {{ "Confirm Payment" }}');
                }
            });
        }

        // NEW: Function to handle printing
        function printReceipt() {
            // Clone the receipt content and replace inputs with their values for a clean print
            const receipt = document.getElementById('receiptContent').cloneNode(true);
            // Replace any per-row payment inputs (hidden) with their numeric values for a clean print
            const inputs = receipt.querySelectorAll('.receipt-amount-input');
            inputs.forEach(inp => {
                const val = parseFloat(inp.value) || 0;
                const td = inp.closest('td');
                if (td) td.innerHTML = currency + (Math.round(val * 100) / 100).toFixed(2);
            });

            // Replace the tendered input with its displayed numeric value in the print clone
            const tenderClone = receipt.querySelector('#receiptAmountTendered');
            if (tenderClone) {
                const tenderVal = parseFloat(document.getElementById('receiptAmountTendered')?.value) || 0;
                // If the input is inside an input-group, replace the whole group
                const group = tenderClone.closest('.input-group');
                const display = currency + (Math.round(tenderVal * 100) / 100).toFixed(2);
                if (group) group.innerHTML = display;
                else tenderClone.outerHTML = display;
            }

            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>{{ __("Payment Receipt") }}</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
            printWindow.document.write('<style>@media print { .alert-warning { display: none; } }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(receipt.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
            };
        }
    </script>
@endsection