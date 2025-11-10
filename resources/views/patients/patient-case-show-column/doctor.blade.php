<div class="d-flex align-items-center">
    {{-- @if(Auth::user()->hasRole('Patient|Nurse|Case Manager')) --}}
    @if(Auth::user()->hasRole('Patient|Case Manager'))
        <div class="image image-circle image-mini me-3">
            <img src="{{ $row->doctor->doctorUser->imageUrl }}" alt="user" class="user-img image rounded-circle object-contain">
        </div>
        <div class="d-flex flex-column">
            {{$row->doctor->doctorUser->first_name}}
            <span class="fs-6">{{$row->doctor->doctorUser->email}}</span>
        </div>
    @else
        @if($row->doctor !== null)
            <a href="{{url('doctors',$row->doctor->id)}}">
                <div class="image image-circle image-mini me-3">
                    <img src="{{$row->doctor->doctorUser->imageUrl}}" alt="user" class="user-img image rounded-circle object-contain">
                </div>
            </a>
            <div class="d-flex flex-column">
                <a href="{{url('doctors',$row->doctor->id)}}" class="mb-1 text-decoration-none fs-6">
                    {{$row->doctor->doctorUser->full_name}}
                </a>
                <span class="fs-6">{{$row->doctor->doctorUser->email}}</span>
            </div>
        @endif
    @endif
</div>
