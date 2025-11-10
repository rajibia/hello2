<div class="d-flex align-items-center">
    {{-- @if(Auth::user()->hasRole('Patient|Nurse|Case Manager')) --}}
    @if(Auth::user()->hasRole('Patient|Case Manager'))
        <div class="image image-circle image-mini me-3">
            <a href="javascript:void(0)">
                <img src="{{$row->doctor->doctorUser->imageUrl}}" alt="user"
                     class="user-img image rounded-circle object-contain">
            </a>
        </div>
        <div class="d-flex flex-column">
            {{$row->doctor->doctorUser->full_name}}
            <span class="fs-6">{{$row->doctor->doctorUser->email}}</span>
        </div>
    @else
        <div class="image image-circle image-mini me-3">
            <a href="{{$row->doctor !== NULL ? url('doctors',$row->doctor->id) : ""}}">
                <img src="{{$row->doctor !== NULL ? $row->doctor->doctorUser->imageUrl : ""}}" alt="user"
                     class="user-img image rounded-circle object-contain">
            </a>
        </div>
        <div class="d-flex flex-column">
            <a href="{{$row->doctor !== NULL ? url('doctors',$row->doctor->id) : ""}}" class="mb-1 text-decoration-none fs-6">
                {{$row->doctor !== NULL ? $row->doctor->doctorUser->full_name : ""}}
            </a>
            <span class="fs-6">{{$row->doctor !== NULL ? $row->doctor->doctorUser->email : ""}}</span>
        </div>
    @endif
</div>
