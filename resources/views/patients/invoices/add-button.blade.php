@aware(['component'])
 
@php
    $theme = $component->getTheme();
@endphp
 



@php
    $configurableAreas = $this->getConfigurableAreas();
    $pId = null;
    if (isset($configurableAreas['toolbar-right-end'])) {
        
        foreach ($configurableAreas['toolbar-right-end'] as $configurableArea) {
            
            if (is_array($configurableArea)) {
                if (isset($configurableArea['patientId'])) {
                    $pId = $configurableArea['patientId'];
                }
            }
        }
    }
    
@endphp

@if($pId != null)
    <button type="button" class="btn btn-primary" 
            data-bs-toggle="modal" 
            data-bs-target="#createInvoiceModal" 
            data-patient-id="{{ $pId }}">
        {{__('messages.invoice.new_invoice')}}
    </button>
    
    <!-- Include the modal directly in the add-button file -->
    @php
        // Get patients directly
        $patients = \App\Models\Patient::with('patientUser')->get()->pluck('patientUser.full_name', 'id')->sort();
        
        // Get status options directly
        $statusArr = \App\Models\Invoice::STATUS_ARR;
        unset($statusArr[\App\Models\Invoice::STATUS_ALL]);
        
        // Get charges data
        $chargesList = \App\Models\Charge::with('chargeCategory')->get()->pluck('chargeCategory.name', 'id')->sort();
        $allCharges = \App\Models\Charge::with('chargeCategory')->get()->toArray();
        
        // Format charges for dropdown
        $associateCharges = [];
        foreach ($chargesList as $key => $item) {
            $associateCharges[] = [
                'key' => $key,
                'value' => $item,
            ];
        }
        $chargeTypes = \App\Models\ChargeType::where('status', 1)->get()->pluck('name', 'id')->toArray();
            asort($chargeTypes);
    @endphp
    
    <div id="createInvoiceModal" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">{{ __('messages.invoice.new_invoice') }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none hide" id="invoiceErrorsBox"></div>
                    {{Form::hidden('invoiceSaveUrl',route('invoices.store'),['id'=>'createInvoiceSaveUrl','class'=>'invoiceSaveUrl'])}}
                    {{Form::hidden('invoiceUrl',route('invoices.index'),['id'=>'createInvoiceUrl','class'=>'invoiceUrl'])}}
                    {{Form::hidden('charges',json_encode($associateCharges),['id'=>'createInvoiceCharges','class'=>'invoiceCharges'])}}
                    {{Form::hidden('allCharges',json_encode($allCharges),['id'=>'allCharges','class'=>'allCharges'])}}
                    {{Form::hidden('uniqueId',2,['id'=>'createInvoiceUniqueId','class'=>'uniqueId'])}}

                    {{ Form::hidden('chargeCategoryUrl', url('charge-categories'), ['id' => 'chargesCategoryURl']) }}
                    {{ Form::hidden('chargeUrl', url('charges'), ['class' => 'chargesURl']) }}
                    {{ Form::hidden('chargeCreateUrl', route('charges.store'), ['id' => 'createChargesURL']) }}
                    {{ Form::hidden('changeChargeTypeUrl', url('get-charge-categories'), ['class' => 'changeChargeTypeURL']) }}
                    {{ Form::hidden('charges', __('messages.bed.charge'), ['id' => 'Charges']) }}
                    @include('invoices.create_modal_content', ['patients' => $patients, 'pId' => $pId, 'chargeTypes' => $chargeTypes])
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
        @include('invoices.invoice_modal_js')
    @endpush
@endif
            
