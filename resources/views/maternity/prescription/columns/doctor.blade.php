<div class="d-flex align-items-center">
    <div class="image image-circle image-mini me-3">
        <a href="{{ url('doctors',$row->doctor_id) }}">
            <div>
                <img src="{{ $row->doctor->doctorUser->imageUrl }}"
                     alt=""
                     class="user-img image image-circle object-contain" width="35px" height="35px">
            </div>
        </a>
    </div>
    <div class="d-flex flex-column">
        <a href="{{ url('doctors',$row->doctor_id) }}"
           class="mb-1 text-decoration-none">{{ $row->doctor->doctorUser->full_name}}</a>
        <span>{{ $row->doctor->doctorUser->email }}</span>
    </div>
</div>
