@extends('layouts.backend')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Semesters List
            </div>
            {{-- <div class="float-end">
                <a class="btn btn-success btn-sm text-white" href="{{ route('admin.school.create.semester',) }}">
                    Add Semesters
                </a>
            </div> --}}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <thead>
                    <tr>
                        <th>
                            ID
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Phone
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Groups
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($schools as $key => $school)
                        <tr data-entry-id="{{ $school->id }}">
                            <td>
                                {{ $school->id ?? '' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.schools.show', $school->id) }}">{{ $school->name ?? '' }}</a>
                            </td>
                            <td>
                                {{ $school->email ?? '' }}
                            </td>
                            <td>
                                {{ $school->phone_number ?? '' }}
                            </td>
                            <td>
                                @if($school->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">destroy</span>
                                @endif
                            </td>
                            <td>
                                {{ $school->groups->count() }}
                            </td>
                            
                        </tr>
                        <tr>
                            <td colspan="6">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>
                                                #
                                            </th>
                                            <th>
                                                Name
                                            </th>
                                            <th>
                                                study
                                            </th>
                                            <th>
                                                exam
                                            </th>
                                            <th>
                                                holiday
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($school->semesters as $key => $semester)
                                            <tr data-entry-id="{{ $semester->id }}">
                                                
                                                <td>
                                                    {{ $semester->id ?? '' }}
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1">{{ $semester->semester ?? '' }} </h5>
                                                </td>
                                                <td>
                                                    <li class="list-inline-item">
                                                        <i class="bx bx-time-five me-1"></i> {{ $semester->study_start}} <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle"></i> {{ $semester->study_end}}
                                                    </li>
                                                </td>
                                                <td>
                                                    <li class="list-inline-item">
                                                        <i class="bx bx-time-five me-1"></i> {{ $semester->exam_start}} <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle"></i> {{ $semester->exam_end}}
                                                    </li>
                                                </td>
                                                <td>
                                                    <li class="list-inline-item">
                                                        <i class="bx bx-time-five me-1"></i> {{ $semester->holiday_start}} <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle"></i> {{ $semester->holiday_end}}
                                                    </li>
                                                </td>
                                                <td>
                                                    <a class="badge bg-info" href="{{ route('admin.semesters.edit', $semester->id) }}">
                                                        Edit
                                                    </a>
                
                                                    @if (auth()->user()->hasRole('Admin'))
                                                        <a href="{{ route('admin.school.destroy.semester', $semester->id) }}" class="badge bg-danger">destroy</a>
                                                    @endif
                
                                                </td>
                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        {{ $schools->links('vendor.pagination.bootstrap-5') }}
    </div>
    
@endsection
