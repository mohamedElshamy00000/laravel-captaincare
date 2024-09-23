@extends('layouts.backend')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex flex-wrap">
                <h4 class="card-title">Official Holidays List</h4>
                <div class="ms-auto">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.official.holiday.create') }}">Create New</a>
                        </li>
                    </ul>
                </div>
            </div>
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
                            Date
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($holidays as $key => $holiday)
                        <tr data-entry-id="{{ $holiday->id }}">
                            <td>
                                {{ $holiday->id ?? '' }}
                            </td>
                            <td>
                                {{ $holiday->name ?? '' }}
                            </td>
                            <td>
                                {{ $holiday->date }}
                            </td>
                            <td>                                
                                <a href="{{ route('admin.official.holiday.destroy', $holiday->id) }}" class="btn btn-outline-danger btn-sm" >Destroy</a>
                                <a href="{{ route('admin.official.holiday.edit', $holiday->id) }}" class="btn btn-outline-info btn-sm" >Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        {{ $holidays->links('vendor.pagination.bootstrap-5') }}
    </div>
    
@endsection
