<div id="show_ipd_diagnosis_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>View Diagnosis</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h3> Report Type </h3>
                        <p id="ipdDiagnosisReportTypeView"></p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <h3> Report Date </h3>
                        <p id="ipdDiagnosisReportDateView"></p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <h3> Description </h3>
                        <p id="ipdDiagnosisDescriptionView"></p>
                    </div>
                  

                    <div class="col-md-12 mb-3">
                        <h3> Document </h3>
                        <p><a target="_blank" href="#" id="ipdDiagnosisDocumentView">Document</a></p>
                        
                    </div>
                  
                </div>
                <div class="modal-footer p-0">
                    <button type="button" aria-label="Close" id="cancelEditIpdDiagnosis" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
