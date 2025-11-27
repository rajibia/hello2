<div class="d-flex align-items-center">

    @if($row->doctor)
        <div class="image image-circle image-mini me-3">
            <a href="{{ route('doctors_show', $row->doctor->id) }}">
                <div>
                    <img src="{{ $row->doctor->doctorUser->image_url }}"
                         alt="doctor image"
                         class="user-img rounded-circle object-contain">
                </div>
            </a>
        </div>

        <div class="d-flex flex-column">
            <a href="{{ route('doctors_show', $row->doctor->id) }}"
               class="text-decoration-none mb-1">
                {{ $row->doctor->doctorUser->full_name }}
            </a>
            <span>{{ $row->doctor->doctorUser->email }}</span>
        </div>

    @else
        <!-- Fallback when doctor is missing -->
        <div class="image image-circle image-mini me-3">
            <div>
                <img src="/images/default-user.png"
                     alt="no doctor"
                     class="user-img rounded-circle object-contain">
            </div>
        </div>

        <div class="d-flex flex-column">
            <span class="text-muted mb-1">No Doctor Assigned</span>
            <span class="text-muted">—</span>
        </div>
    @endif

</div>
