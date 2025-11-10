<div id="showRadiologyTestBill" class="modal fade side-fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"
                    id="exampleModalLabel">{{ __('messages.radiology_test.radiology_test_details') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-4 mb-5">
                        <label for="bill_no"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.radiology_test.bill_no').(':') }}</label><br>
                        <span id="showRadiologyBillNo"
                              class="fs-5 text-gray-800 showSpan"></span>
                    </div>
                    <div class="form-group col-4 mb-5">
                        <label for="patient"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.radiology_test.patient_name').(':') }}</label><br>
                        <span id="showRadiologyTestPatient"
                              class="fs-5 text-gray-800 showSpan"></span>
                    </div>
                    <div class="form-group col-4 mb-5">
                        <label for="ipd"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.radiology_test.ipd').(':') }}</label><br>
                        <span id="showRadiologyTestIpd"
                              class="fs-5 text-gray-800 showSpan"></span>
                    </div>
                    <div class="form-group col-4 mb-5">
                        <label for="opd"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.radiology_test.case_id').(':') }}</label><br>
                        <span id="showRadiologyTestCase"
                              class="fs-5 text-gray-800 showSpan"></span>
                    </div>
                    <div class="form-group col-4 mb-5">
                        <label for="previous_report_value"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.radiology_test.previous_report_value').(':') }}</label><br>
                        <span id="showRadiologyTestPreviousReportValue"
                              class="fs-5 text-gray-800 showSpan"></span>
                    </div>
                    <div class="form-group col-4 mb-5">
                        <label for="note"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.radiology_test.note').(':') }}</label><br>
                        <span id="showRadiologyTestNote"
                              class="fs-5 text-gray-800 showSpan"></span>
                    </div>
                    <div class="form-group col-4 mb-5">
                        <label for="doctor"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.radiology_test.doctor').(':') }}</label><br>
                        <span id="showRadiologyTestDoctor"
                              class="fs-5 text-gray-800 showSpan"></span>
                    </div>
                    <div class="form-group col-4 mb-5">
                        <label for="total"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.radiology_test.total').(':') }}</label><br>
                        <span id="showRadiologyTestTotal"
                              class="fs-5 text-gray-800 showSpan"></span>
                    </div>
                    <div class="form-group col-4 mb-5">
                        <label for="amount_paid"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.radiology_test.amount_paid').(':') }}</label><br>
                        <span id="showRadiologyTestAmountPaid"
                              class="fs-5 text-gray-800 showSpan"></span>
                    </div>
                    <div class="form-group col-4 mb-5">
                        <label for="discount"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.radiology_test.discount').(':') }}</label><br>
                        <span id="showRadiologyTestDiscount"
                              class="fs-5 text-gray-800 showSpan"></span>
                    </div>
                    <div class="form-group col-6 mb-5">
                        <label for="balance"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.radiology_test.balance').(':') }}</label><br>
                        <span id="showRadiologyTestBalance"
                              class="fs-5 text-gray-800 showSpan"></span>
                    </div>
                    <div class="form-group col-4 mb-5">
                        <label for="status"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.radiology_test.status').(':') }}</label><br>
                        <span id="showRadiologyTestStatus"
                              class="fs-5 text-gray-800 showSpan"></span>
                    </div>
                    <div class="form-group col-4 mb-5">
                        <label for="created_on"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.common.created_on').(':') }}</label><br>
                        <span id="showRadiologyTestCreatedOn"
                              class="fs-5 text-grßß ay-800 showSpan"></span>
                    </div>
                    <div class="form-group col-4 mb-5">
                        <label for="updated_on"
                               class="pb-2 fs-5 text-gray-600">{{ __('messages.common.last_updated').(':') }}</label><br>
                        <span id="showRadiologyTestUpdatedOn"
                              class="ffs-5 text-gray-800 showSpan"></span>
                    </div>
                </div>
                <table class="items-table col-12 mx-auto">
                    <thead style="background-color: #3dc1d3; color: #fff;">
                        <tr>
                            <th scope="col">Test Name</th>
                            <th scope="col">Sample Collected</th>
                            <th scope="col">Report Days</th>
                            <th scope="col">Report Date</th>
                            <th scope="col">Approved By / Approve Date</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="radiology-test-tbody" style="background-color: #f6fcff; color: #2c3e50;">
                        <!-- Rows will be appended here -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
