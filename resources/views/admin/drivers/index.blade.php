@extends('layouts.backend')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Drivers List
            </div>
            @can('user_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route('admin.drivers.create') }}">
                        Add Driver
                    </a>
                </div>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="10"></th>
                        <th>
                            ID
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Phone
                        </th>
                        <th>
                            Address
                        </th>
                        <th>
                            License
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($drivers as $key => $driver)
                        <tr data-entry-id="{{ $driver->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $driver->id ?? '' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.drivers.show', $driver->id) }}"><h5 class="font-size-14 mb-1">{{ $driver->name ?? '' }} </h5></a>
                                <p class="text-muted mb-0">{{ $driver->email ?? '' }}</p>
                            </td>
                            <td>
                                {{ $driver->phone ?? '' }}
                            </td>
                            <td>
                                <p class="text-muted mb-0">{{ $driver->address ?? '-'}}</p>
                            </td>
                            <td>
                                <p class="mb-2"><i class="mdi mdi-circle align-middle font-size-10 me-2 text-primary"></i> {{ $driver->license ?? '' }}</p>
                            </td>
                            <td>
                                @can('user_edit')
                                    <a class="badge bg-info"
                                       href="{{ route('admin.drivers.edit', $driver->id) }}">
                                        Edit
                                    </a>
                                @endcan

                                @if (auth()->user()->hasRole('Admin'))
                                    @if($driver->status)
                                        <a href="{{ route('admin.driver.banUnban', ['id' => $driver->id, 'status' => 0]) }}" class="badge bg-danger">Block</a>
                                    @else
                                        <a href="{{ route('admin.driver.banUnban', ['id' => $driver->id, 'status' => 1]) }}" class="badge bg-info">Unblock</a>
                                    @endif
                                @endif

                                @if($driver->cars->count() >= 1)
                                    <a href="{{ route('admin.cars.edit', ['car' => $driver->cars->first()->id]) }}" class="badge bg-dark">Edit Car</a>
                                @else
                                    <a href="{{ route('admin.driver.add.car', ['id' => $driver->id]) }}" class="badge bg-success">Add car</a>
                                @endif

                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
