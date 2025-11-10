<div class="card mb-5 mb-xl-10">
    <div>
        <div class="card-body pt-9 pb-0">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('patient_id', __('messages.prescription.patient').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span class="fs-5 text-gray-800">{{$prescription->patient->patientUser->full_name}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('doctor_id', __('messages.case.doctor').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span class="fs-5 text-gray-800">{{$prescription->doctor->doctorUser->full_name}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('food_allergies', __('messages.prescription.food_allergies').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{ ($prescription->food_allergies != "") ? $prescription->food_allergies : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('tendency_bleed', __('messages.prescription.tendency_bleed').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{($prescription->tendency_bleed != "") ? $prescription->tendency_bleed : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('heart_disease', __('messages.prescription.heart_disease').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{($prescription->heart_disease != "") ? $prescription->heart_disease : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('high_blood_pressure', __('messages.prescription.high_blood_pressure').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{($prescription->high_blood_pressure != "") ? $prescription->high_blood_pressure : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('diabetic', __('messages.prescription.diabetic').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{($prescription->diabetic != "") ? $prescription->diabetic : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('surgery', __('messages.prescription.surgery').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{($prescription->surgery != "") ? $prescription->surgery : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('accident', __('messages.prescription.accident').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{($prescription->accident != "") ? $prescription->accident : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('others', __('messages.prescription.others').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{($prescription->others != "") ? $prescription->others : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('medical_history', __('messages.prescription.medical_history').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
        <span
                class="fs-5 text-gray-800">{{ ($prescription->medical_history != "") ? \Carbon\Carbon::parse($prescription->medical_history)->translatedFormat('jS M, Y') : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('current_medication', __('messages.prescription.current_medication').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{ ($prescription->current_medication != "") ? $prescription->current_medication : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('female_pregnancy', __('messages.prescription.female_pregnancy').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{ ($prescription->female_pregnancy != "") ? $prescription->female_pregnancy : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('breast_feeding', __('messages.prescription.breast_feeding').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{($prescription->breast_feeding != "") ? $prescription->breast_feeding : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('health_insurance', __('messages.prescription.health_insurance').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{($prescription->health_insurance != "") ? $prescription->health_insurance : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column mb-md-10 mb-5">
                    {{ Form::label('low_income', __('messages.prescription.low_income').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{($prescription->low_income != "") ? $prescription->low_income : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column">
                    {{ Form::label('reference', __('messages.prescription.reference').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span
                        class="fs-5 text-gray-800">{{($prescription->reference != "") ? $prescription->reference : __('messages.common.n/a')}}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column">
                    {{ Form::label('status', __('messages.common.status').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <p class="m-0">
                        <span
                            class="badge fs-6 bg-light-{{!empty($prescription->status == 1) ? 'success' : 'danger'}}">{{($prescription->status == 1) ? __('messages.common.active') : __('messages.common.de_active') }}</span>
                    </p>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column">
                    {{ Form::label('created_on', __('messages.common.created_on').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span class="fs-5 text-gray-800" data-toggle="tooltip" data-placement="right"
                          title="{{ date('jS M, Y', strtotime($prescription->created_at)) }}">{{ $prescription->created_at->diffForHumans() }}</span>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-2 d-flex flex-column">
                    {{ Form::label('last_updated', __('messages.common.last_updated').(':'), ['class' => 'pb-2 fs-5 text-gray-600']) }}
                    <span class="fs-5 text-gray-800" data-toggle="tooltip" data-placement="right"
                          title="{{ date('jS M, Y', strtotime($prescription->updated_at)) }}">{{ $prescription->updated_at->diffForHumans() }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-6">
                    <h6>{{ __('messages.prescription.rx') }}:</h6>
                    <div class="table-responsive">
                        <table class="table box-shadow-none">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('messages.prescription.medicine_name') }}</th>
                                <th scope="col">{{ __('messages.ipd_patient_prescription.dosage') }}</th>
                                <th scope="col">{{ __('messages.prescription.duration') }}</th>
                                <th scope="col">{{ __('messages.medicine_bills.dose_interval') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(empty($prescription->getMedicine))
                                <tr>
                                    <td class="text-center" colspan="3">
                                        {{ __('messages.prescription.no_data_available') }}
                                    </td>
                                </tr>
                            @else
                                @foreach($prescription->getMedicine as $medicine)
                                    @foreach($medicine->medicines as $medi)
                                            <tr>
                                                <td class="py-4 border-bottom-0">{{ $medi->name }}</td>
                                                <td class="py-4 border-bottom-0">
                                                    {{ $medicine->dosage }}
                                                    @if($medicine->time == 0)
                                                        ({{ __('messages.prescription.after_meal') }})
                                                    @else
                                                        ({{  __('messages.prescription.before_meal')}})
                                                    @endif
                                                </td>
                                                <td class="py-4 border-bottom-0">{{ $medicine->day }} Day</td>
                                                @if ($medicine->dose_interval != 0)
                                                <td class="py-4 border-bottom-0">{{ App\Models\Prescription::DOSE_INTERVAL[$medicine->dose_interval] }}</td>
                                                @endif
                                            </tr>
                                        @break
                                    @endforeach
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-5">
                        <h4 class="mb-0 me-3 mt-3">
                            @if($prescription->next_visit_qty != null)
                                {{ __('messages.prescription.next_visit') }} : {{ $prescription->next_visit_qty }}
                                @if($prescription->next_visit_time == 0)
                                    {{ __('messages.prescription.days') }}
                                @elseif($prescription->next_visit_time == 1)
                                    {{ __('messages.prescription.month') }}
                                @else
                                    {{ __('messages.prescription.year') }}
                                @endif
                            @endif
                        </h4>
                        <div class="mt-3">
                            <br>
                            <h4>{{ 'Dr. '.$prescription->doctor->doctorUser->full_name }}</h4>
                            <h6 class="text-gray-600 fw-light mb-0">{{ $prescription->doctor->specialist }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
