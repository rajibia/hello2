<div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="poverview" role="tabpanel">
            <div class="card">
                <div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 d-flex flex-column mb-md-10 mb-5">
                                {{ Form::label('patient', 'General Examination:', ['class' => 'pb-2 fs-5 text-gray-600']) }}
                                <span
                                    class="fs-5 text-gray-800">{{ $general_examination->general_examination ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
