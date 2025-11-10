@extends('layouts.app')
@section('title')
    {{ __('messages.medicine_bills.add_medicine_bill') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div>
                <a href="javascript:void(0)" class="btn btn-primary me-3 add-patient-modal">{{ __('messages.patient.new_patient') }}</a>
                <a href="{{ route('medicine-bills.index') }}" class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    @include('layouts.errors')
                    @include('flash::message')
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    {{Form::hidden('uniqueId',2,['id'=>'medicineUniqueId'])}}
                    {{ Form::hidden('associateMedicines', json_encode($medicineList), ['class' => 'associatePurchaseMedicines']) }}
                    {{ Form::hidden('medicineCategories', json_encode($medicineCategoriesList), ['id' => 'showMedicineCategoriesMedicineBill']) }}

                    {{ Form::open(['route' => 'medicine-bills.store', 'method' => 'post', 'id' => 'createMedicinebillForm']) }}
                    <div class="row">
                        @include('medicine-bills.medicine-table')
                    </div>
                    {{ Form::close() }}
                </div>
                @include('medicine-bills.templates.templates')
            </div>
        </div>
    </div>
    @include('medicine-bills.add_patient_modal')
@endsection
@section('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing medicine bills functionality...');

    // Initialize date picker
    if (typeof $ !== 'undefined' && $('.medicine_bill_date').length > 0) {
        console.log('Initializing date picker...');

        // Destroy existing instances
        $('.medicine_bill_date').each(function() {
            if (this._flatpickr) {
                this._flatpickr.destroy();
            }
        });

        // Initialize with jQuery
        $('.medicine_bill_date').flatpickr({
            enableTime: true,
            defaultDate: new Date(),
            dateFormat: "Y-m-d H:i",
            time_24hr: false,
            minuteIncrement: 1
        });

        console.log('Date picker initialized successfully');
    }

    // Debug: Check if the original medicine_bill.js functions are working
    setTimeout(function() {
        console.log('=== DEBUGGING MEDICINE FUNCTIONALITY ===');

        if (typeof $ !== 'undefined') {
            // Check if the original listenChange function exists
            if (typeof window.listenChange === 'function') {
                console.log('listenChange function is available');
            } else {
                console.error('listenChange function is NOT available');
            }

            // Check if the loadSaleMedicineCreate function exists
            if (typeof window.loadSaleMedicineCreate === 'function') {
                console.log('loadSaleMedicineCreate function is available');
                // Try to call it manually
                try {
                    window.loadSaleMedicineCreate();
                    console.log('loadSaleMedicineCreate called successfully');
                } catch (e) {
                    console.error('Error calling loadSaleMedicineCreate:', e);
                }
            } else {
                console.error('loadSaleMedicineCreate function is NOT available');
            }

            // Check form elements
            const categoryDropdowns = $('.medicineBillCategoriesId');
            console.log('Category dropdowns found:', categoryDropdowns.length);

            categoryDropdowns.each(function(index) {
                console.log(`Category dropdown ${index}:`);
                console.log('  - Classes:', this.className);
                console.log('  - ID:', this.id);
                console.log('  - Options count:', this.options.length);
                console.log('  - Has Select2:', $(this).hasClass('select2-hidden-accessible'));

                // Manually trigger Select2 initialization if needed
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    console.log('Initializing Select2 for category dropdown', index);
                    $(this).select2();
                }
            });

            const medicineDropdowns = $('.medicinePurchaseId, .purchaseMedicineId');
            console.log('Medicine dropdowns found:', medicineDropdowns.length);

            medicineDropdowns.each(function(index) {
                console.log(`Medicine dropdown ${index}:`);
                console.log('  - Classes:', this.className);
                console.log('  - ID:', this.id);
                console.log('  - Options count:', this.options.length);
                console.log('  - Has Select2:', $(this).hasClass('select2-hidden-accessible'));

                // Manually trigger Select2 initialization if needed
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    console.log('Initializing Select2 for medicine dropdown', index);
                    $(this).select2();
                }
            });

            // Test if the category change event is working by manually triggering it
            if (categoryDropdowns.length > 0) {
                console.log('Testing category change event...');
                const firstCategory = categoryDropdowns.first();
                const firstOption = firstCategory.find('option').not('[value=""]').first();

                if (firstOption.length > 0) {
                    console.log('Triggering change event with category:', firstOption.val());
                    firstCategory.val(firstOption.val()).trigger('change');

                    // Also trigger Select2 event
                    firstCategory.trigger('select2:select');
                }
            }

        } else {
            console.error('jQuery is not available');
        }

        console.log('=== END DEBUGGING ===');
    }, 3000);
});
</script>
@endsection
