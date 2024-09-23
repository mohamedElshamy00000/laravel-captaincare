@extends('layouts.backend')
@section('content')

    <div class="card border-0 shadow-sm">
        <div class="card-header">
            Create school class
        </div>
        <form action="{{ route("admin.school.classes.store")}}" method="POST">
            @csrf
            <div class="card-body">
                <div class="mb-2">
                    <label for="title">Name*</label>
                    <input type="text" id="title" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', isset($class) ? $class->name : '') }}" required>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="description">Description*</label>
                    <input type="text" id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                           value="{{ old('description', isset($class) ? $class->description : '') }}" required>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                
                <div class="row card-body align-items-center ps-0">
                    <label for="semester">study*</label>
    
                    <div class="col-sm-auto">
                        <label class="visually-hidden" for="entry_time">Entry time</label>
                        <div class="input-group" id="timepicker-input-group1">
                            <input type="text" class="form-control" name="entry_time" id="timepicker"  data-provide="timepicker">
                            <span class="input-group-text"><i class="mdi mdi-clock-outline"></i></span>
                        </div>

                        @error('entry_time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    
                    <div class="col-sm-auto">to</div>

                    <div class="col-sm-auto">
                        <label class="visually-hidden" for="check_out">Check out</label>

                        <div class="input-group" id="timepicker-input-group3">
                            <input type="text" class="form-control" name="check_out" id="timepicker3"  data-provide="timepicker">
                            <span class="input-group-text"><i class="mdi mdi-clock-outline"></i></span>
                        </div>

                        @error('check_out')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>                    
    
                </div>
                <input type="hidden" name="school_id" value="{{ $school->id }}">
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Save</button>
                <a class="btn btn-secondary" href="{{ route('admin.schools.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection

