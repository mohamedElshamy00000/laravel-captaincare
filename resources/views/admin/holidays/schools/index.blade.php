@extends('layouts.backend')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                School Holidays List
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
                                <a href="{{ route('admin.school.holiday.destroy', $holiday->id) }}" class="btn btn-outline-danger btn-sm" >Destroy</a>
                                <a href="{{ route('admin.school.holiday.edit', $holiday->id) }}" class="btn btn-outline-info btn-sm" >Edit</a>
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
