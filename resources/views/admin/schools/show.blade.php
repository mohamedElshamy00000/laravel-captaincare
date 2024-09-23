@extends('layouts.backend')

@section('styles')
<link href="{{ asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    {{-- <div class="flex-shrink-0 me-4">
                        @if ($school->photo)
                        <img src="{{ asset('backend/assets/images/companies/img-1.png') }}" alt="" class="avatar-sm">
                        @endif
                    </div> --}}

                    <div class="flex-grow-1 overflow-hidden">
                        <h2 class="text-truncate font-size-30">{{ $school->name }}
                            @if ($school->status == 1)
                                <span class="badge badge-pill badge-soft-success font-size-11">active</span>
                            @else
                                <span class="badge badge-pill badge-soft-danger font-size-11">not active</span>
                            @endif
                        </h2>
                    </div>
                </div>

                <h5 class="font-size-15 mt-4">Details :</h5>

                <p class="text-muted">{{ $school->address }},</p>

                <div class="text-muted mt-4">
                    <p><i class="mdi mdi-chevron-right text-primary me-1"></i> {{ $school->email }}</p>
                    <p><i class="mdi mdi-chevron-right text-primary me-1"></i> {{ $school->phone_number }}</p>
                </div>
                <a href="{{ route('admin.school.holiday.index', $school->id) }}" class="text-primary font-16">Show Holidays <i class="mdi mdi-chevron-right"></i></a>
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap">
                    <h4 class="card-title mb-4">School Classes</h4>

                    <div class="ms-auto">
                        <a href="{{ route('admin.school.classes.create', $school->id) }}" class="btn btn-primary waves-effect waves-light btn-sm">Create</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap">
                        <tbody>
                            @foreach ($school->classes as $sClass)
                               <tr>
                                    <td>
                                        <h5 class="font-size-14 m-0">{{ $sClass->name }}</h5>
                                        <p class="text-muted mb-0">{{ $sClass->description }}</p>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="badge bg-success bg-soft text-success font-size-11"><i class="bx bx-right-arrow-alt"></i> {{ $sClass->entry_time }}</span>
                                            <span class="badge bg-dark bg-soft text-dark font-size-11"><i class="bx bx-building"></i> </span>
                                            <span class="badge bg-primary bg-soft text-primary font-size-11">{{ $sClass->check_out }} <i class="bx bx-right-arrow-alt"></i> </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if (auth()->user()->hasRole('Admin'))
                                            <a class="badge bg-info" href="{{ route('admin.school.classes.edit', $sClass->id) }}">
                                                Edit
                                            </a>
                                        @endif
                                    </td>
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-end">
                        <div class="input-group input-group-sm">
                            <a href="{{ route('admin.groups.create') }}" class="btn btn-primary waves-effect waves-light btn-sm">Create new Group <i class="mdi mdi-chevron-right ms-1"></i></a>
                        </div>
                    </div>
                    <h4 class="card-title mb-4">Groups</h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>description</th>
                            <th>date</th>
                            <th>status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($groups as $key => $group)
                            <tr data-entry-id="{{ $school->id }}">
                                <td>
                                    {{ $group->id ?? '' }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.groups.show', $group->id) }}">{{ $group->name }}</a>
                                </td>
                                <td>
                                    {{ $group->description ?? '' }}
                                </td>
                                <td>
                                    {{ $group->created_at->format('d-m-Y') ?? '' }}
                                </td>
                                <td>
                                    @if($group->status)
                                        <span class="badge badge-pill badge-soft-success font-size-11">Active</span>
                                    @else
                                        <span class="badge badge-pill badge-soft-danger font-size-11">Blocked</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" class="p-0">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    age
                                                </th>
                                                <th>
                                                    status
                                                </th>
                                                <th>
                                                    Parents
                                                </th>
                                                <th>
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($group->children as $key => $child)
                                                <tr data-entry-id="{{ $child->id }}">
                                                    
                                                    <td>
                                                        {{ $child->id ?? '' }}
                                                    </td>
                                                    <td>
                                                        <h5 class="font-size-14 mb-1">{{ $child->name ?? '' }} </h5>
                                                    </td>
                                                    <td>
                                                        <li class="list-inline-item">
                                                            {{ $child->age}} Year
                                                        </li>
                                                    </td>
                                                    <td>
                                                        
                                                        @if($child->status)
                                                            <a href="#" class="badge bg-danger">Block</a>
                                                        @else
                                                            <a href="#" class="badge bg-info">Unblock</a>
                                                        @endif
                    
                                                    </td>
                                                    <td>
                                                        <ul class="verti-timeline list-unstyled">
                                                        @foreach ($child->fathers as $parent)
                                                        
                                                        <li class="">
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0 me-3">
                                                                    <h5 class="font-size-14 mb-0">{{ $parent->name }}</h5>
                                                                    <p class="mb-0">{{ $parent->phone }} </p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        
                                                        @endforeach
                                                        </ul>

                                                    </td>
                                                    <td>
                                                        @if (auth()->user()->hasRole('Admin'))
                                                            <a class="badge bg-info" href="">
                                                                Edit
                                                            </a>
                                                        @endif
                                                    </td>
                    
                                                </tr>
                                                @empty
                                                <div class="p-3 text-center">
                                                    <h5 class="text-dark fw-bold">there is no children !</h5>
                                                    <a class="font-size-17 mb-0" href="{{ route('admin.groups.show', $group->id) }}">try to Add <i class="mdi mdi-account-plus-outline"></i></a>.
                                                </div>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
    
                </div>

            </div>
            
        </div>
        {{ $groups->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>


@endsection

@section('scripts')

@endsection