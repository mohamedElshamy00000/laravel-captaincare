@extends('layouts.backend')
@section('content')

    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h5>Edit holiday</h5>
        </div>
        
        <form action="{{ route('admin.school.holiday.update', $holiday->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row card-body align-items-center">
                <div class="col-md-6">
                    <label for="holiday">holiday Name*</label>
                    <input type="text" id="holiday" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ $holiday->name }}" required>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <input type="hidden" name="id" value="{{ $holiday->id }}">
                @error('id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="col-md-6">
                    <label for="date">Holiday Date</label>
                    <input type="date" class="form-control" name="date" id="date" value="{{ $holiday->date }}" required placeholder="holiday date">
                    @error('date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Update</button>
            </div>
        </form>
    </div>
@endsection

