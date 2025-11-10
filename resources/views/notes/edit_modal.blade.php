<div id="edit_note_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Notes</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'editNoteForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="editNoteErrorsBox"></div>
               
                {{ Form::hidden('notes_id', null, ['id' => 'editNoteId']) }}
                <div class="row gx-10 mb-5">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="mb-5">
                            <div class="mb-5">
                                {{ Form::label('notes', 'Notes:', ['class' => 'form-label']) }}
                                {{ Form::textarea('notes', null, ['class' => 'form-control', 'required', 'rows' => 3, 'tabindex' => '2', 'placeholder' => 'Notes', 'id' => 'editNoteValue']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary me-3','id'=>'btnEditNoteSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    <button type="button" id="btnNoteCancel"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>

            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
