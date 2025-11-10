<div id="show_complaint_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>View Complaint</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h3> Main Complaint </h3>
                        <p id="opdMainComplaintView"></p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <h3>Main Complaint Progression</h3>
                        <p id="opdMainComplaintProgressionView"></p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <h3> Direct Questioning </h3>
                        <p id="opdDirectQuestioningView"></p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <h3> Drug History </h3>
                        <p id="opdDrugHistoryView"></p>
                    </div>
                </div>
                <div class="modal-footer p-0">
                    <button type="button" aria-label="Close" id="cancelEditopdDiagnosis" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
