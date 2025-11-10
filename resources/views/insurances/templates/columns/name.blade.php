<div class="d-flex align-items-center">
    @if(!is_null($row->image))
        <div class="image image-mini me-3">
            <a href="javascript:void(0)" data-id="{{ $row->id }}" class="show-btn">
                <img src="{{$row->image}}" alt="user" class="user-img image rounded-circle object-contain">
            </a>
        </div>
    @endif
    <div class="d-flex flex-column">
        <a href="{{ url('insurances').'/'.$row->id }}" class="text-decoration-none"> {{ $row->name }}</a>
        {{-- <a href="javascript:void(0)" class="mb-1 show-user-btn text-decoration-none" data-id="{{ $row->id }}">
            {{$row->name}}
        </a> --}}
    </div>
</div>




