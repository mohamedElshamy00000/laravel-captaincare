@extends('layouts.backend')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Fathers List
            </div>
            @can('user_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route('admin.fathers.create') }}">
                        Add Father
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
                            Subscription
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fathers as $key => $father)
                        <tr data-entry-id="{{ $father->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $father->id ?? '' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.fathers.show', $father->id) }}"><h5 class="font-size-14 mb-1">{{ $father->name ?? '' }} </h5></a>
                                <p class="text-muted mb-0">{{ $father->email ?? '' }}</p>
                            </td>
                            <td>
                                {{ $father->phone ?? '' }}
                            </td>
                            <td>
                                <p class="mb-2"><strong>State</strong> : {{ $father->state ?? '' }} <strong>City</strong> : {{ $father->city ?? '' }}</p>
                            </td>
                            <td>
                                @if ($father->hasActiveSubscription())
                                @php
                                    $Subscription = $father->activeSubscription();
                                @endphp
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item me-1">
                                            <span class="badge bg-success px-2 py-1">{{ $Subscription->plan->name }}</span>
                                        </li>
                                        <li class="list-inline-item me-1">
                                            <h5 class="font-size-14" data-toggle="tooltip" data-placement="top" title="Amount"><i class="bx bx-money me-1 text-muted"></i>{{ $Subscription->plan->price }} {{ $Subscription->plan->currency }}</h5>
                                        </li>
                                        <li class="list-inline-item">
                                            <h5 class="font-size-14" data-toggle="tooltip" data-placement="top" title="Due Date">/ {{ $Subscription->plan->duration }} Day</h5>
                                        </li>
                                    </ul>
                                @else
                                    Not Active Subscription
                                @endif
                            </td>
                            <td>
                                @can('user_edit')
                                    <a class="badge bg-info"
                                       href="{{ route('admin.fathers.edit', $father->id) }}">
                                        Edit
                                    </a>
                                @endcan

                                @if (auth()->user()->hasRole('Admin'))
                                    @if($father->status)
                                        <a href="{{ route('admin.father.banUnban', ['id' => $father->id, 'status' => 0]) }}" class="badge bg-danger">Block</a>
                                    @else
                                        <a href="{{ route('admin.father.banUnban', ['id' => $father->id, 'status' => 1]) }}" class="badge bg-info">Unblock</a>
                                    @endif
                                @endif

                                @if($father->children->count() >= 1)
                                    {{-- <a href="{{ route('admin.child.show', ['father' => $father->id]) }}" class="badge bg-dark">Children</a> --}}
                                @else
                                    {{-- <a href="{{ route('admin.father.add.child', ['id' => $driver->id]) }}" class="badge bg-success">Add Child</a> --}}
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
