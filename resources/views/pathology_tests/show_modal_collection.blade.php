<div id="showPathologyTestCollection" class="modal fade overlay" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg " >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"
                    id="exampleModalLabel">{{ __('messages.pathology_test.pathology_test_collection') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            {{ Form::open([ 'id' => 'PathTestItemCollection']) }}

            <input type="hidden" name="path_item_id" value="" id="path_item_id" class="path_item_id">
            {{-- path_item_id --}}
            
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-3 mb-5">
                            {{ Form::label('collected_by', __('messages.pathology_test.collected_by') . ':', ['class' => 'form-label']) }}
                            <select name="collected_by" id="collected_by" class="form-select vitalsIPDId">

                            </select>
                        </div>
                        <div class="form-group col-md-3 mb-5">
                            {{ Form::label('collection_date', __('messages.pathology_test.collection_date') . ':', ['class' => 'form-label']) }}
                            <input type="date" name="collection_date" id="collected_date" class="form-select" />
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary me-3','id'=>'PathTestCollectionSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    </div>
                </div>
            {{ Form::close() }}

        </div>
    </div>
</div>
