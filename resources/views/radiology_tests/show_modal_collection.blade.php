<div id="showRadiologyTestCollection" class="modal fade overlay" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg " >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"
                    id="exampleModalLabel">{{ __('messages.radiology_test.radiology_test_collection') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            {{ Form::open([ 'id' => 'RadTestItemCollection']) }}

            <input type="hidden" name="rad_item_id" value="" id="rad_item_id" class="rad_item_id">
            {{-- path_item_id --}}
            
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-3 mb-5">
                            {{ Form::label('collected_by', __('messages.radiology_test.collected_by') . ':', ['class' => 'form-label']) }}
                            <select name="collected_by" id="collected_by" class="form-select vitalsIPDId">

                            </select>
                        </div>
                        <div class="form-group col-md-3 mb-5">
                            {{ Form::label('collection_date', __('messages.radiology_test.collection_date') . ':', ['class' => 'form-label']) }}
                            <input type="date" name="collection_date" id="collected_date" class="form-select" />
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary me-3','id'=>'RadTestCollectionSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    </div>
                </div>
            {{ Form::close() }}

        </div>
    </div>
</div>
