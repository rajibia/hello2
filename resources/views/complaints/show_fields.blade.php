<div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="poverview" role="tabpanel">
            <div class="card">
                <div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 d-flex flex-column mb-md-10 mb-5">
                                {{ Form::label('patient', 'Main Complaint:', ['class' => 'pb-2 fs-5 text-gray-600']) }}
                                <span
                                    class="fs-5 text-gray-800">{{ $complaint->main_complaint ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-12 d-flex flex-column mb-md-10 mb-5">
                                {{ Form::label('patient', 'Main Complaint Progression:', ['class' => 'pb-2 fs-5 text-gray-600']) }}
                                <span
                                    class="fs-5 text-gray-800">{{ $complaint->main_complaint_progression ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-12 d-flex flex-column mb-md-10 mb-5">
                                {{ Form::label('patient', 'Direct Questioning:', ['class' => 'pb-2 fs-5 text-gray-600']) }}
                                <span
                                    class="fs-5 text-gray-800">{{ $complaint->direct_questioning ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-12 d-flex flex-column mb-md-10 mb-5">
                                {{ Form::label('patient', 'Drug History:', ['class' => 'pb-2 fs-5 text-gray-600']) }}
                                <span
                                    class="fs-5 text-gray-800">{{ $complaint->drug_history ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
