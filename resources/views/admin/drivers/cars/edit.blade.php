@extends('layouts.backend')

@section('styles')
<link href="{{ asset('backend/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('backend/assets/libs/@chenfengyuan/datepicker/datepicker.min.css') }}">
@endsection

@section('content')

    <div class="card border-0 shadow-sm">
        <div class="card-header">
            Create Car - to driver : <h4>{{ $driver->name }}</h4>
        </div>
        <form action="{{ route("admin.cars.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                                                        
                <div class="mt-3">
                    <label for="formFile" class="form-label">License</label>
                    <input class="form-control" type="file" name="license" id="formFile">
                    @error('license')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="card-body">
                                                        
                <div class="mt-3">
                    <label for="photo" class="form-label">Photo*</label>
                    <input class="form-control" type="file" name="photo" id="photo">
                    @error('photo')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="card-body">
                <div class="mb-2">
                    <label>Year Make*</label>
                    <div class="position-relative" id="datepicker5">
                        <input type="text" class="form-control" data-provide="datepicker" data-date-container='#datepicker5'
                            data-date-format="dd M, yyyy" data-date-min-view-mode="2"
                            value="{{ old('make', isset($car) ? $car->make : '') }}"
                            class="@error('make') is-invalid @enderror"
                            name="make">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <label for="model">Model*</label>
                    <input type="text" id="model" name="model" class="form-control @error('model') is-invalid @enderror"
                           value="{{ old('model', isset($car) ? $car->model : '') }}" required>
                    @error('model')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="card-body">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="formCheck2" checked="">
                    <label class="form-check-label" for="formCheck2">
                        status
                    </label>
                </div>
            </div>

            <input type="hidden" name="id" value="{{ $driver->id }}">
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Save</button>
                <a class="btn btn-secondary" href="{{ route('admin.drivers.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection


@section('scripts')
    <script src="{{ asset('backend/assets/js/pages/form-advanced.init.js') }}"></script>
@endsection
