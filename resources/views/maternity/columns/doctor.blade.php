@if($row->doctor && $row->doctor->doctorUser)
<div class="d-flex align-items-center">
    <div class="image image-circle image-mini me-3">
        <img src="{{$row->doctor->doctorUser->image_url}}" alt="user">
    </div>
    <div class="d-flex flex-column">
        <a href="{{ route('doctors_show', $row->doctor->id) }}" class="mb-1 text-decoration-none fs-6">
            {{$row->doctor->doctorUser->full_name}}
        </a>
        <span class="fs-6">{{$row->doctor->doctorUser->email}}</span>
    </div>
</div>
@else
<span class="text-muted">N/A</span>
@endif
