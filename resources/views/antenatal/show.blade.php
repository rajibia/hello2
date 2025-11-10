@extends('layouts.app')

@section('title', 'Antenatal Details')

@section('content')
<div class="container mt-5">
    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">{{ __('messages.antenatal.title') }}</h1>
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
        </div>
    </div>
    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="fa-solid fa-info-circle me-2"></i> Patient
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="row">

                            <div class="col-md-4">
                                <p class="fw-bold">{{ __('messages.antenatal.patient_id') }}: {{ $antenatal->patient_id }}</p>
                                <p class="fw-bold">{{ __('messages.antenatal.date') }}: {{ $antenatal->date }}</p>
                                <p class="fw-bold">{{ __('messages.antenatal.blood_pressure') }}: {{ $antenatal->blood_pressure }}</p>
                            </div>

                            <div class="col-md-4">
                                <p class="fw-bold">{{ __('messages.antenatal.antenatal_weight') }}: {{ $antenatal->antenatal_weight }}</p>
                                <p class="fw-bold">{{ __('messages.antenatal.bleeding') }}: 
                                    <span class="badge bg-{{ $antenatal->bleeding === '1' ? 'light-success' : 'light-primary' }}">
                                        {{ $antenatal->bleeding === '1' ? 'Yes' : 'No' }}
                                    </span>
                                </p>
                                <p class="fw-bold">{{ __('messages.antenatal.headache') }}: 
                                    <span class="badge bg-{{ $antenatal->headache === '1' ? 'light-success' : 'light-primary' }}">
                                        {{ $antenatal->headache === '1' ? 'Yes' : 'No' }}
                                    </span>
                                </p>
                            </div>

                            <div class="col-md-4">
                                <p class="fw-bold">{{ __('messages.antenatal.pain') }}: 
                                    <span class="badge bg-{{ $antenatal->pain === '1' ? 'light-success' : 'light-primary' }}">
                                        {{ $antenatal->pain === '1' ? 'Yes' : 'No' }}
                                    </span>
                                </p>
                                <p class="fw-bold">{{ __('messages.antenatal.vomiting') }}: 
                                    <span class="badge bg-{{ $antenatal->vomiting === '1' ? 'light-success' : 'light-primary' }}">
                                        {{ $antenatal->vomiting === '1' ? 'Yes' : 'No' }}
                                    </span>
                                </p>
                                <p class="fw-bold">{{ __('messages.antenatal.cough') }}: 
                                    <span class="badge bg-{{ $antenatal->cough === '1' ? 'light-success' : 'light-primary' }}">
                                        {{ $antenatal->cough === '1' ? 'Yes' : 'No' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="fa-solid fa-stethoscope me-2"></i> {{ __('messages.antenatal.special_findings_and_remark') }}
                </div>
                <div class="card-body">
                    <p class="fw-bold">{{ __('messages.antenatal.condition') }}: {{ $antenatal->condition ?? 'N/A' }}</p>
                    <p class="fw-bold">{{ __('messages.antenatal.special_findings_and_remark') }}:</p>
                    <p class="bg-light p-3 rounded border">{{ $antenatal->special_findings_and_remark ?? 'None' }}</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Additional Details -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="fa-solid fa-heartbeat me-2"></i> {{ __('messages.antenatal.foetal_heart') }}
                </div>
                <div class="card-body">
                    <p class="fw-bold">{{ __('messages.antenatal.uterus_size') }}: {{ $antenatal->uterus_size ?? 'N/A' }}</p>
                    <p class="fw-bold">{{ __('messages.antenatal.presentation_position') }}: {{ $antenatal->presentation_position ?? 'N/A' }}</p>
                    <p class="fw-bold">{{ __('messages.antenatal.foetal_heart') }}: {{ $antenatal->foetal_heart ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="fa-solid fa-vial me-2"></i> {{ __('messages.antenatal.urine_sugar') }}
                </div>
                <div class="card-body">
                    <p class="fw-bold">{{ __('messages.antenatal.urine_sugar') }}: {{ $antenatal->urine_sugar ?? 'N/A' }}</p>
                    <p class="fw-bold">{{ __('messages.antenatal.urine_albumin') }}: {{ $antenatal->urine_albumin ?? 'N/A' }}</p>
                    <p class="fw-bold">{{ __('messages.antenatal.next_visit') }}: {{ $antenatal->next_visit ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
