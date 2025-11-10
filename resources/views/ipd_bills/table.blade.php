<div class="row">
    
    <div class="col-lg-6 col-md-12">
        <h5>{{ __('messages.ipd_charges') }}</h5>
        <div class="table-responsive-sm">
            <table
                class="table table-responsive-sm table-striped align-middle table-row-dashed fs-6 gx-5 gy-5 no-footer w-100">
                <thead class="thead-light">
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th scope="col">{{ __('messages.account.type') }}</th>
                        <th scope="col">{{ __('messages.medicine.category') }}</th>
                        <th scope="col">{{ __('messages.ipd_patient_charges.date') }}</th>
                        <th scope="col" class="d-flex justify-content-end me-5">{{ __('messages.invoice.amount') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-bold">
                    @foreach ($bill['charges'] as $charge)
                        <tr>
                            <td>{{ $charge->charge_type }}</td>
                            <td>{{ $charge->chargecategory->name }}</td>
                            <td>{{ $charge->date->format('d/m/Y') }}</td>
                            <td class="d-flex justify-content-end me-5">
                                {{ checkNumberFormat($charge->applied_charge, strtoupper(getCurrentCurrency())) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="w-100 " colspan="4">
                            <div class="d-flex justify-content-between">
                                {{ __('messages.bill.total_amount') . ':' }}
                                <span class="pl-2 font-weight-bold pe-5">
                                    {{--                                <span>{{ getCurrencySymbol() }}</span> --}}
                                    {{--                                <span>{{ $bill['total_charges'] }}</span> --}}
                                    <span>
                                        {{ checkNumberFormat($bill['total_charges'], strtoupper(getCurrentCurrency())) }}
                                    </span>
                                </span>
                                {{--                                                        <span>{{ moneyFormat($bill['total_charges'], strtoupper(getCurrentCurrency()))  }}</span></span> --}}
                            </div>
                        </td>
                        {{--                    <td class="text-right" colspan="4"> --}}
                        {{--                        {{ __('messages.bill.total_amount').':' }} --}}
                        {{--                        <span class="pl-2 font-weight-bold"><span>{{ getCurrencySymbol() }}</span> --}}
                        {{--                            <span>{{ $bill['total_charges']  }}</span></span> --}}
                        {{--                    </td> --}}
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-lg-6 col-md-12">
        <h5>{{ __('messages.account.payments') }}</h5>
        <div class="table-responsive-sm">
            <table
                class="table table-responsive-sm table-striped align-middle table-row-dashed fs-6 gx-5 gy-5 no-footer w-100">
                <thead class="thead-light">
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th scope="col">{{ __('messages.ipd_payments.payment_mode') }}</th>
                        <th scope="col">{{ __('messages.ipd_patient_charges.date') }}</th>
                        <th scope="col" class="d-flex justify-content-end me-5">
                            {{ __('messages.ipd_bill.paid_amount') }}</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-bold">
                    @foreach ($bill['payments'] as $payment)
                        <tr>
                            <td>{{ $payment->payment_mode_name }}</td>
                            <td>{{ $payment->date->format('d/m/Y') }}</td>
                            <td class="d-flex justify-content-end me-5">
                                {{ checkNumberFormat($payment->amount, strtoupper(getCurrentCurrency())) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="w-100 " colspan="3">
                            <div class="d-flex justify-content-between">
                                {{ __('messages.bill.total_amount') . ':' }}
                                <span class="pl-2 font-weight-bold pe-5">
                                    {{--                                <span>{{ getCurrencySymbol() }}</span> --}}
                                    <span>
                                        {{ checkNumberFormat($bill['total_payment'], strtoupper($bill['payment_currency']->currency_symbol ?? getCurrentCurrency())) }}
                                    </span>
                                </span>
                            </div>
                        </td>
                        {{--                    <td class="text-right" colspan="4"> --}}
                        {{--                        <div> --}}
                        {{--                            <div> --}}
                        {{--                                {{ __('messages.bill.total_amount').':' }} --}}
                        {{--                            </div> --}}
                        {{--                            <div class="text-end pe-25 font-weight-bold "><span>{{ getCurrencySymbol() }}</span> --}}
                        {{--                                <span>{{ $bill['total_payment']  }}</span> --}}
                        {{--                            </div> --}}
                        {{--                        </div> --}}
                        {{--                    </td> --}}
                    </tr>
                </tfoot>
            </table>

        </div>
        <form id="ipdBillForm">
            <input type="hidden" value="{{ $ipdPatientDepartment->id }}" name="ipd_patient_department_id">
            @if ($ipdPatientDepartment->bill)
                <input type="hidden" value="{{ $ipdPatientDepartment->bill->id }}" name="bill_id">
            @endif
            <div class="row mb-5">
                <div class="col-lg-12 col-md-12 table-responsive-sm">
                    <table
                        class="table table-responsive-sm table-striped align-middle table-row-dashed fs-6 gx-5 gy-5 no-footer w-100">
                        <thead class="thead-light">
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="h5 font-weight-bold" scope="col" colspan="2">
                                    {{ __('messages.bill.bill_summary') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-bold">

                            <tr>
                                <td>{{ __('messages.ipd_bill.paid_amount') . ':' }}</td>
                                <td class="d-flex justify-content-end me-5 font-weight-bold">
                                    {{--                                <span>{{ getCurrencySymbol() }}</span> --}}
                                    <span id="totalPayments">
                                        {{ checkNumberFormat($bill['total_payment'], strtoupper($bill['payment_currency']->currency_symbol ?? getCurrentCurrency())) }}
                                    </span>
                            </tr>
                            <tr>
                                <td>{{ __('messages.ipd_bill.total_charges') . ':' }}</td>
                                <td class="d-flex justify-content-end me-5 font-weight-bold">
                                    {{--                                <span>{{ getCurrencySymbol() }}</span> --}}
                                    <span id="totalCharges">
                                        {{ checkNumberFormat($bill['total_charges'], strtoupper($bill['payment_currency']->currency_symbol ?? getCurrentCurrency())) }}
                                    </span>
                                    {{--                                <span id="totalCharges">{{ moneyFormat($bill['total_charges'], strtoupper(getCurrentCurrency())) }}</span> --}}
                            </tr>
                            <tr>
                                <td>{{ __('messages.ipd_bill.gross_total') . ':' }}</td>
                                <td class="d-flex justify-content-end me-5 font-weight-bold">
                                    {{--                                <span>{{ getCurrencySymbol() }}</span> --}}
                                    {{--                                <span id="grossTotal">{{ $bill['gross_total']  }}</span> --}}
                                    <span id="grossTotal">
                                        {{ checkNumberFormat($bill['gross_total'], strtoupper($bill['payment_currency']->currency_symbol ?? getCurrentCurrency())) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('messages.ipd_bill.discount_in_percentage') . ' (%) :' }}</td>
                                <td class="text-right font-weight-bold  d-flex justify-content-end ">
                                    <div class="input-group w-25 w-sm-50 w-xs-75 float-right ">
                                        <input type="text"
                                            class="form-control d-flex justify-content-end  price-input"
                                            name="discount_in_percentage" id="discountPercent"
                                            value="{{ $bill['discount_in_percentage'] }}" required>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('messages.ipd_bill.tax_in_percentage') . ' (%) :' }}</td>
                                <td class="text-right font-weight-bold d-flex justify-content-end ">
                                    <div class="input-group w-25 w-sm-50 w-xs-75 d-flex justify-content-end ">
                                        <input type="text" name="tax_in_percentage" id="taxPercentage"
                                            class="form-control text-right price-input"
                                            value="{{ $bill['tax_in_percentage'] }}" required>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('messages.ipd_bill.other_charges') . ':' }}</td>
                                <td class="text-right font-weight-bold d-flex justify-content-end ">
                                    <div class="input-group w-25 w-sm-50 w-xs-75 d-flex justify-content-end ">
                                        <input type="text" class="form-control  price-input" name="other_charges"
                                            id="otherCharges" value="{{ $bill['other_charges'] }}" required>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">{{ __('messages.ipd_bill.net_payable_amount') . ':' }}
                                    (<span id="billStatus"></span>)
                                </td>
                                <td class="d-flex justify-content-end me-5 font-weight-bold">
                                    {{--                                <span>{{ getCurrencySymbol() }}</span> --}}
                                    {{--                                <span id="netPayabelAmount">{{ $bill['net_payable_amount']  }}</span></td> --}}
                                    {{--                                <span id="netPayabelAmount"></span> --}}
                                    {{ checkNumberFormat($bill['net_payable_amount'], strtoupper($bill['payment_currency']->currency_symbol ?? getCurrentCurrency())) }}

                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>
            <a href="{{ url('ipd-bills/' . $ipdPatientDepartment->id . '/pdf') }}" target="_blank"
                class="btn btn-success mb-5 btn-active-light-primary me-2 {{ $ipdPatientDepartment->bill ? '' : 'disabled' }} "
                id="printBillBtn" role="button" aria-pressed="true">{{ __('messages.bill.print_bill') }}</a>
          
            @hasrole('Doctor')
            
                @if (!$ipdPatientDepartment->doctor_discharge)
                
                        <button type="button" class="btn btn-danger mb-5 me-2" data-bs-toggle="modal" data-bs-target="#dischargeModal">Discharge patient</button>
                @endif
                
                
            @else
                
                @if (!getLoggedinPatient())
                    @if (!$ipdPatientDepartment->bill_status)
                        @if($ipdPatientDepartment->doctor_discharge)
                            <button type="submit" class="btn mb-5 btn-light btn-active-light-primary me-2" id="saveIpdBillbtn">
                                {{ __('messages.bill.generate_bill') . ' & ' . __('messages.ipd_bill.discharge_patient') }}
                            </button>
                        @else
                            <b><span class="text-danger p-auto">Pending doctor discharge</span></b>
                            <!--badge bg-primary-->
                        @endif
                    @endif
                @endif
            @endhasrole

        </form>
    </div>
</div>


<div class="modal fade" id="dischargeModal" tabindex="-1" aria-labelledby="prescribeMedicationModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="prescribeMedicationModalLabel">Discharge patient</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      
                      <div class="modal-body">
                        <form idx="dischargeForm" method='POST' action="{{route('doctor.discharge')}}">
                         @csrf
                          <input type="hidden" name="patient_id" value="{{$ipdPatientDepartment->id}}">

                            <div class="mb-3">
                              <label for="dischargeReason" class="form-label">Discharge Reason</label>
                              <select class="form-select" id="dischargeReason" name="discharge_reason" required>
                                <option selected disabled value="">Please select a reason...</option>
                                <option value="Condition Improved / Treatment Complete">Condition Improved / Treatment Complete</option>
                                <option value="Transferred to Another Facility">Transferred to Another Facility</option>
                                <option value="Discharged Against Medical Advice (AMA)">Discharged Against Medical Advice (AMA)</option>
                                <option value="Referred to Palliative / Hospice Care">Referred to Palliative / Hospice Care</option>
                                <option value="Deceased">Deceased</option>
                                <option value="other">Other</option>
                              </select>
                            </div>
                          <div class="mb-3">
                            <label for="dosage" class="form-label">Discharge Notes (optional)</label>
                            <textarea class="form-control" id="dosage" rows="3"></textarea>
                          </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="dischargeForm" class="btn btn-primary">Discharge</button>
                        </form>
                      </div>
                      <!--<div class="modal-footer">-->
                        
                      <!--</div>-->
                      <!--  </form>-->
                    </div>
                  </div>
                </div>
