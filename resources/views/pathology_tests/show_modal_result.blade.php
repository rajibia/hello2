<div id="showPathologyTestResult" class="modal fade overlay" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg " >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"
                    id="exampleModalLabel">{{ __('messages.pathology_test.pathology_test_collection') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            {{ Form::open([ 'id' => 'PathTestItemResult', 'enctype'=>"multipart/form-data"]) }}

            <input type="hidden" name="path_item_id" value="" class="path_item_id">
            
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-4 mb-5">
                            <label for="test_name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.pathology_test.test_name').(':') }}</label><br>
                            <span id="showPathologyTestName"
                                  class="fs-5 text-gray-800 showSpan"></span>
                        </div>
                        <div class="form-group col-4 mb-5">
                            <label for="expected_date"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.pathology_test.expected_date').(':') }}</label><br>
                            <span id="showPathologyExpectedDate"
                                  class="fs-5 text-gray-800 showSpan"></span>
                        </div>
                        <div class="form-group col-4 mb-5">
                            <label for="approved_date"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.pathology_test.approved_date').(':') }}</label><br>
                            <span id="showPathologyApprovedDate"
                                  class="fs-5 text-gray-800 showSpan"></span>
                        </div>
                        <div class="form-group col-4 mb-5">
                            <label for="approved_by"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.pathology_test.approved_by').(':') }}</label><br>
                            <span id="showPathologyApprovedBy"
                                  class="fs-5 text-gray-800 showSpan"></span>
                        </div>
                        <div class="form-group col-4 mb-5">
                            <label for="collection_date"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.pathology_test.collection_date').(':') }}</label><br>
                            <span id="showPathologyCollectionDate"
                                  class="fs-5 text-gray-800 showSpan"></span>
                        </div>
                        <div class="form-group col-4 mb-5">
                            <label for="collection_by"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.pathology_test.collection_by').(':') }}</label><br>
                            <span id="showPathologyCollectionBy"
                                  class="fs-5 text-gray-800 showSpan"></span>
                        </div>
                        
                       
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group mb-5">
                                {{ Form::label('result', __('messages.pathology_test.patient_result').':',['class' => 'form-label']) }}
                                {{ Form::textarea('result', null, ['class' => 'form-control', 'id' => 'patient_result', 'placeholder'=>__('messages.pathology_test.patient_result')]) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4 mb-5">
                            {{ Form::label('approved_by', __('messages.pathology_test.approved_by') . ':', ['class' => 'form-label']) }}
                            <select name="approved_by" id="approved_by" class="form-select vitalsIPDId">

                            </select>
                        </div>
                        <div class="form-group col-md-4 mb-5">
                            {{ Form::label('approved_date', __('messages.pathology_test.approved_date') . ':', ['class' => 'form-label']) }}
                            <input type="date" name="approved_date" id="approved_date" class="form-select" />
                        </div>
                        <div class="form-group col-md-4 mb-5">
                            {{ Form::label('report_doc', __('messages.upload_report') . ':', ['class' => 'form-label']) }}
                            <input type="file" id="report_doc" name="report_doc"
                                class="form-control"
                                accept=".png, .jpg, .jpeg, .gif, .doc, .docx, .pdf" />
                        </div>
                        
                    </div>
                    <div class="form-group col-md-12">
                        <div class="table-responsive-sm">
                            <div class="overflow-auto">
                                <table class="table table-striped">
                                    <thead class="thead-dark">
                                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                            <th class="">{{ __('messages.new_change.parameter_name') }}<span class="required"></span>
                                            </th>
                                            <th class="">{{ __('messages.new_change.patient_report_value') }}<span class="required"></span>
                                            </th>
                                            <th class="">{{ __('messages.new_change.reference_range') }}<span class="required"></span>
                                            </th>
                                            {{-- <th class="">{{ __('messages.pathology_test.unit') }}<span class="required"></span>
                                            </th> --}}
                                        </tr>
                                    </thead>
                                    <tbody class="pathology-test-container">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary me-3','id'=>'PathTestResultSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    </div>
                </div>
            {{ Form::close() }}

        </div>
    </div>
</div>
