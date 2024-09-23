@extends('layouts.backend')
@section('content')

    <div class="card">
        <div class="card-header ps-0">
            <h2>Edit School Semester</h2>
        </div>
        <form action="{{ route('admin.semesters.update', [$schoolSemester->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body pb-0">
                <div class="mb-2">
                    <label for="semester">Semester Name*</label>
                    <input type="text" id="semester" name="semester" class="form-control @error('semester') is-invalid @enderror"
                           value="{{ old('semester', isset($schoolSemester) ? $schoolSemester->semester : '') }}" required>
                    @error('semester')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row card-body align-items-center">
                <label for="semester">study*</label>

                <div class="col-sm-auto">
                    <label class="visually-hidden" for="study_start">Study Start</label>
                    <input type="date" class="form-control" name="study_start" id="study_start" value="{{ old('semester', isset($schoolSemester) ? $schoolSemester->study_start : '') }}" placeholder="study start">
                    @error('study_start')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-sm-auto">to</div>
                <div class="col-sm-auto">
                    <label class="visually-hidden" for="study_end">Study End</label>
                    <input type="date" class="form-control" name="study_end" id="study_end" value="{{ old('semester', isset($schoolSemester) ? $schoolSemester->study_end : '') }}" placeholder="study end">
                    @error('study_end')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

            </div>

            <div class="row card-body align-items-center">
                <label for="semester">Exam*</label>

                <div class="col-sm-auto">
                    <label class="visually-hidden" for="exam_start">Exam Start</label>
                    <input type="date" class="form-control" name="exam_start" id="exam_start" value="{{ old('semester', isset($schoolSemester) ? $schoolSemester->exam_start : '') }}" placeholder="Exam start">
                    @error('exam_start')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-sm-auto">to</div>
                <div class="col-sm-auto">
                    <label class="visually-hidden" for="exam_end">Exam End</label>
                    <input type="date" class="form-control" name="exam_end" id="exam_end" value="{{ old('semester', isset($schoolSemester) ? $schoolSemester->exam_end : '') }}" placeholder="Exam end">
                    @error('exam_end')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

            </div>
            <div class="row card-body align-items-center">
                <label for="semester">Holiday*</label>

                <div class="col-sm-auto">
                    <label class="visually-hidden" for="autoSizingInput">holiday Start</label>
                    <input type="date" class="form-control" name="holiday_start" id="autoSizingInput" value="{{ old('semester', isset($schoolSemester) ? $schoolSemester->holiday_start : '') }}" placeholder="holiday start">
                    @error('holiday_start')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-sm-auto">to</div>
                <div class="col-sm-auto">
                    <label class="visually-hidden" for="autoSizingInputGroup">holiday End</label>
                    <input type="date" class="form-control" name="holiday_end" id="autoSizingInputGroup" value="{{ old('semester', isset($schoolSemester) ? $schoolSemester->holiday_end : '') }}" placeholder="holiday end">
                    @error('holiday_end')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

            </div>

            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Update</button>
                <a class="btn btn-secondary" href="{{ route('admin.semesters.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection

