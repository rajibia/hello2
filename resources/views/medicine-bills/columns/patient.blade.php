@if($row->patient && $row->patient->patientUser)
<div class="d-flex align-items-center">
    <div class="image image-mini me-3">
        <a href="{{ route('patients.show', $row->patient->id) }}">
            <div>
                <img src="{{ $row->patient->patientUser->image_url ?? asset('assets/images/default-avatar.png') }}" alt=""
                    class="user-img image image-circle object-contain">
            </div>
        </a>
    </div>
    <div class="d-flex flex-column">
        <a href="{{ route('patients.show', $row->patient->id) }}"
            class="text-decoration-none mb-1">{{ $row->patient->patientUser->full_name ?? 'N/A' }}</a>
        <span>{{ $row->patient->patientUser->email ?? 'N/A' }}</span>
    </div>
</div>
@else
<div class="d-flex align-items-center">
    <div class="image image-mini me-3">
        <div>
            <img src="{{ asset('assets/images/default-avatar.png') }}" alt=""
                class="user-img image image-circle object-contain">
        </div>
    </div>
    <div class="d-flex flex-column">
        <span class="text-decoration-none mb-1 text-muted">Patient Not Found</span>
        <span class="text-muted">N/A</span>
    </div>
</div>
@endif
