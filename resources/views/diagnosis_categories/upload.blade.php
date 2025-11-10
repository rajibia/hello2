@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4>{{ __('Upload ICD 10 Disease Codes') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('diagnosis.category.diagnosisUpload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file" class="form-label">{{ __('Choose Excel File') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-file-excel"></i>
                                </span>
                                <input type="file" name="icd10_file" id="file" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group mt-4 text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> {{ __('Upload') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
