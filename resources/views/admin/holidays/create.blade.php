@extends('layouts.backend')
@section('content')

    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h5>Create official holiday </h5>
        </div>
        <form action="{{ route('admin.official.holiday.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row card-body align-items-center">
                <div class="col-md-6">
                    <label for="holiday">holiday Name*</label>
                    <input type="text" id="holiday" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('holiday', isset($holiday) ? $holiday->name : '') }}" required>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="date">Holiday Date</label>
                    <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" id="date" placeholder="holiday date">
                    @error('date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Save</button>
            </div>
        </form>
    </div>
@endsection

