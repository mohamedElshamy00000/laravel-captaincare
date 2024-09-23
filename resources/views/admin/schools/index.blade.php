@extends('layouts.backend')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Schools List
            </div>
            @can('permission_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route("admin.schools.create") }}">
                        Add School
                    </a>
                </div>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
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
                        <th>
                            Register At
                        </th>
                        <th>
                            Action
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
                                    <span class="badge bg-danger">Blocked</span>
                                @endif
                            </td>
                            <td>
                                {{ $school->groups->count() }}
                            </td>
                            <td>
                                {{ $school->created_at->format('Y-m-d') ?? '' }}
                            </td>
                            <td>

                                <div class="dropdown d-inline">
                                    <a href="javascript:void(0)" class="dropdown-toggle badge bg-primary" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-lg-start border-0 shadow-sm rounded-0">
                                        <li><a class="dropdown-item" href="{{ route('admin.schools.show', $school->id) }}">Show</a></li>
                                        <li><hr class="dropdown-divider"></li>                                       
                                        <li><a class="dropdown-item" href="{{ route('admin.schools.edit', $school->id) }}">Edit</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.school.create.semester', $school->id) }}">Create Semester</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.school.holiday.create', $school->id) }}">Add Holiday</a></li>
                                    </ul>
                                </div>
                                @if (auth()->user()->hasRole('Admin'))
                                    @if($school->status)
                                        <a href="{{ route('admin.school.banUnban', ['id' => $school->id, 'status' => 0]) }}" class="badge bg-danger">Block</a>
                                    @else
                                        <a href="{{ route('admin.school.banUnban', ['id' => $school->id, 'status' => 1]) }}" class="badge bg-info">Unblock</a>
                                    @endif
                                @endif

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            {{ $schools->links() }}
        </div>
    </div>
@endsection
