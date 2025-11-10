<div class="form-check form-switch form-check-custom form-check-solid justify-content-center">
    <input class="form-check-input w-30px h-20px is-active" data-id="{{$row->id}}" type="checkbox" value=""
           name="status" {{$row->status == 1 ? 'checked' : ''}}/>
</div>
