<div id="edit_permission_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">{{ __('messages.permission-settings.edit_permission') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            {{ Form::open(['id'=>'editPermissionForm', 'method' => 'patch']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="editOperationCatErrorsBox"></div>
                {{ Form::hidden('editPermissionId',null,['id'=>'editPermissionIdText']) }}
                <div class="row">
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('name', __('messages.user.name').(':'), ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('name', '', ['id'=>'editPermissionName','class' => 'form-control','required','placeholder'=>__('messages.user.name')]) }}
                    </div>
                    
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('permissions', __('messages.permission-settings.permissions').':', ['class' => 'form-label d-block mb-2']) }}
                    
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="add" id="edit_perm_add">
                            <label class="form-check-label" for="perm_add">Add</label>
                        </div>
                    
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="edit" id="edit_perm_edit">
                            <label class="form-check-label" for="perm_edit">Edit</label>
                        </div>
                    
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="delete" id="edit_perm_delete">
                            <label class="form-check-label" for="perm_delete">Delete</label>
                        </div>
                    
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="view" id="edit_perm_view">
                            <label class="form-check-label" for="perm_view">View</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit','class' => 'btn btn-primary m-0','id' => 'editPermissionSave','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
