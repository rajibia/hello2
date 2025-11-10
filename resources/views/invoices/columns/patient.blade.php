@if($row->patientUser)
<div class="d-flex align-items-center">
    <div class="image image-mini me-3">
        <a href="{{ route('patients.show',$row->id) }}">
            <div>
                <img src="{{ $row->patientUser->image_url }}" alt=""
                     class="user-img image image-circle object-contain" width="40" height="40">
            </div>
        </a>
    </div>
    <div class="d-inline-block align-top">
        <a href="{{ route('patients.show',$row->id) }}"
           class="text-primary-800 mb-1 d-block  text-decoration-none">{{ $row->patientUser->first_name }} {{ $row->patientUser->last_name }}</a>
        <span class="d-block">{{ $row->patientUser->email }}</span>
    </div>
</div>
@else
<div class="text-muted">
    <span>Patient data not available (ID: {{ $row->id }}, User ID: {{ $row->user_id ?? 'null' }})</span>
</div>
@endif
