@include('invoices.invoice_modal_css')

{{ Form::open(['route' => 'invoices.store', 'id' => 'createInvoiceForm', 'class' => 'needs-validation']) }}
{{Form::hidden('paid_amount', 0, ['id'=>'invoicePaidAmount'])}}
{{Form::hidden('change', 0, ['id'=>'invoiceChange'])}}
<div class="container-fluid">
    <div class="d-flex flex-column align-items-start flex-xxl-row">
        <div class="col-lg-4 col-12 mb-4 mb-lg-0">
            <h4>{{ __('messages.invoice.invoice') }} # <span
                    class="text-gray-500">{{ $invoiceId = \App\Models\Invoice::generateUniqueInvoiceId() }}</span>
                <input type="hidden" value="{{ $invoiceId }}" name="invoice_id" />
                {{ Form::hidden('currency_symbol', getCurrentCurrency(), ['class' => 'currencySymbol']) }}
            </h4>
        </div>
        <div class="col-lg-8 col-12">
            <div class="row">
                <div class="col-sm-6 col-12">
                    <div class="mb-sm-0 mb-6">
                        {{ Form::label('invoice_patient_id', __('messages.invoice.patient').':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        @if(isset($pId) && $pId != '')
                            @php
                                $patientName = $patients[$pId] ?? 'Unknown Patient';
                            @endphp
                            <select id="invoice_patient_id" name="patient_id" class="form-select" disabled>
                                <option value="{{ $pId }}" selected>{{ $patientName }}</option>
                            </select>
                            <input type="hidden" name="patient_id" value="{{ $pId }}">
                        @else
                            {{ Form::select('patient_id', $patients, null, ['class' => 'form-select', 'id' => 'invoice_patient_id', 'placeholder' => __('messages.document.select_patient'), 'required', 'data-control' => 'select2']) }}
                        @endif
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="">
                        {{ Form::label('invoice_date', __('messages.invoice.invoice_date').':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        <input type="text" name="invoice_date" id="invoice_date" value="{{ date('Y-m-d') }}" placeholder="{{ __('messages.invoice.invoice_date') }}" class="bg-white form-control" autocomplete="off" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="separator separator-dashed my-10"></div>
    <div class="row gx-10 mb-5">
        <div class="col-lg-6 col-sm-12">
            <div class="mb-5">
                {{ Form::label('discount', __('messages.invoice.discount').':', ['class' => 'form-label']) }}
                (%)
                <span class="required"></span>
                {{ Form::number('discount', 0, ['id' => 'discount', 'class' => 'form-control', 'placeholder' => __('messages.invoice.in_percentage'), 'required', 'min' => 0, 'max' => 100, 'step' => '.01']) }}
            </div>
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="mb-5">
                {{ Form::label('status', __('messages.common.status').':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('status', isset($statusArr) ? $statusArr : [], 1, ['class' => 'form-select status', 'id' => 'status', 'required', 'data-control' => 'select2']) }}
            </div>
        </div>
    </div>

</div>

<div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-end">
    <button type="button" class="btn btn-primary text-star" id="addInvoiceItem">
        {{ __('messages.invoice.add') }}</button>
</div>
<div class="table-responsive">
    <table class="table g-5 gs-0 mt-2" id="billTbl">
        <thead>
            <tr class="border-bottom fs-7 text-gray-700 text-uppercase">
                <th class="text-center">#</th>
                <th>{{ __('CHARGE TYPE') }}</th>
                <th>{{ __('messages.charges') }}</th>
                <th>{{ __('messages.invoice.description') }}</th>
                <th>{{ __('messages.invoice.qty') }}<span class="required"></span></th>
                <th>{{ __('messages.invoice.price') }}<span class="required"></span></th>
                <th class="text-end">{{ __('messages.invoice.amount') }}<span class="required"></span></th>
                <th class="text-end">{{ __('messages.common.action') }}</th>
            </tr>
        </thead>
        <tbody class="invoice-item-container">
            <tr class="border-bottom">
                <td class="text-center pt-6 item-number">1</td>
                 <td class="table__item-desc">
                    {{ Form::select('charge_type', $chargeTypes, null, ['class' => 'form-select', 'data-control' => 'select2', 'required', 'id' => 'chargeInvTypeId', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.charge_category.charge_type')]) }}
                </td>
                <td class="table__item-desc">
                    {{ Form::select('charge_id[]', [], null, ['id' => 'chargeId', 'class' => 'form-select chargeId', 'disabled', 'required', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.charges'), 'data-control' => 'select2']) }}
                    {{-- {{ Form::hidden('id[]', $invoiceItem->id) }} --}}
                </td>
                {{-- <td class="table__item-desc">
                    <select class="form-select chargeId first-row-select" name="charge_id[]" required placeholder="{{ __('messages.common.choose').' '.__('messages.charges') }}" data-control="select2">
                        <option selected="selected" value="">{{ __('messages.common.choose').' '.__('messages.charges') }}</option>
                        @foreach($associateCharges as $charge)
                            <option value="{{ $charge['key'] }}">{{ $charge['value'] }}</option>
                        @endforeach
                    </select>
                </td> --}}
                <td class="text-center">
                    <input class="form-control" name="description[]" type="text" placeholder="{{ __('messages.invoice.description') }}">
                </td>
                <td class="table__qty text-center">
                    <input class="form-control qty" required name="quantity[]" type="number" min="1" value="1" placeholder="{{ __('messages.invoice.qty') }}">
                </td>
                <td class="text-center">
                    <input class="form-control price-input price" required name="price[]" type="text" placeholder="{{ __('messages.invoice.price') }}">
                </td>
                <td class="amount text-center item-total pt-5 ms-2 text-nowrap">
                    <span class="amount">0.00</span>
                </td>
                <td class="text-end">
                    <a href="javascript:void(0)" title="{{__('messages.common.delete')}}"
                       class="deleteInvoiceItem btn px-2 text-danger fs-3">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="row">
    <div class="col-xxl-3 col-lg-5 col-md-6 ms-auto mt-4">
        <div class="border-top">
            <table class="table table-borderless box-shadow-none mb-0 mt-5">
                <tbody>
                <tr>
                    <td class="ps-0">{{ __('messages.invoice.sub_total').(':') }}</td>
                    <td class="text-gray-900 text-end pe-0">
                        GH₵ <span id="total" class="price">0.00</span>
                    </td>
                </tr>
                <tr>
                    <td class="ps-0">{{ __('messages.invoice.discount').(':') }}</td>
                    <td class="text-gray-900 text-end pe-0">
                        GH₵ <span id="discountAmount">0.00</span>
                    </td>
                </tr>
                <tr>
                    <td class="ps-0">{{ __('messages.invoice.total').(':') }}</td>
                    <td class="text-gray-900 text-end pe-0">
                        GH₵ <span id="finalAmount">0.00</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="d-flex justify-content-end mt-5">
    {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary me-3', 'id' => 'saveInvoiceBtn']) }}
    <button type="button" class="btn btn-secondary"
            data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
</div>
{{ Form::close() }}

<!-- Template for invoice items -->
@include('invoices.templates.invoice_item_template')

<!-- Include the search fix script -->
<script src="{{ asset('js/invoice_search_fix.js') }}"></script>
