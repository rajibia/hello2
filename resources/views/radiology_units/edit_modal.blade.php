<div id="editRadiologyUnitsModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content w-50 m-auto">
            <div class="modal-header">
                <h3>{{ __('messages.new_change.edit_unit') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id'=>'editRadiologyUnitsForm','method' => 'patch']) }}
            <div class="modal-body">
                <div class="alert alert-danger display-none hide" id="editPUniValidationErrorsBox"></div>
                {{ Form::hidden('radiologyUnitId',null,['id'=>'radiologyUnitId']) }}
                <div class="row">
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('name', __('messages.radiology_category.name').':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('name', '', ['placeholder' => __('messages.radiology_category.name'),'id'=>'editRadiologyUnitName','class' => 'form-control','required','placeholder'=>__('messages.radiology_category.name')]) }}
                    </div>
                </div>
                <div class="modal-footer p-0">
                    {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary','id'=>'editRadiologyUnitSaveBtn','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    <button type="button" aria-label="Close" class="btn btn-secondary ms-2"
                            data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
