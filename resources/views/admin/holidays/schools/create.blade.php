@extends('layouts.backend')
@section('content')

    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h5>Create holiday to <span class="text-primary">{{ $school->name }}</span></h5>
        </div>
        <form action="{{ route('admin.school.holiday.store') }}" method="POST" enctype="multipart/form-data">
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
                    <input type="date" class="form-control" name="date" id="date" placeholder="holiday date">
                    @error('date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <input type="hidden" name="school_id" value="{{ $school->id }}">

            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Save</button>
                <a class="btn btn-secondary" href="{{ route('admin.schools.show', $school->id ) }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection

