@extends('layouts.backend')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-xxl-3 d-flex mb-4">
            <div class="card illustration flex-fill">
                <div class="card-body p-0 d-flex flex-fill">
                    <div class="row g-0 w-100">
                        <div class="col-6">
                            <div class="illustration-text p-3 m-1">
                                <h4 class="illustration-text">Welcome Back, {{ auth()->user()->name }}!</h4>
                                @php
                                    $roles = auth()->user()->getRoleNames()->toArray();
                                @endphp
                                <p class="mb-0">{{ implode(" ", $roles) }}</p>
                            </div>
                        </div>
                        <div class="col-6 align-self-end text-end">
                            <img src="{{ asset('images/admin/customer-support.png') }}" alt="Customer Support"
                                 class="img-fluid illustration-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(auth()->user()->hasRole('Admin'))
            <div class="col-12 col-sm-6 col-xxl-3 d-flex mb-4">
                <div class="card flex-fill">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <h3 class="mb-2">{{ $count['users'] ?? 0 }}</h3>
                                <p class="mb-2">Total Users</p>
                                <div class="mb-0">
                                    {{--<span class="badge badge-soft-success me-2"> +5.35% </span>
                                    <span class="text-muted">Since last week</span>--}}
                                </div>
                            </div>
                            <div class="d-inline-block ms-3">
                                <div class="stat">
                                    <svg style="width: 35px; height: 35px;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="feather feather-users align-middle text-success">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-12 col-sm-6 col-xxl-3 d-flex mb-4">
            <div class="card flex-fill">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2">{{ $count['trips'] ?? 0 }}</h3>
                            <p class="mb-2">Total Trips</p>
                            <div class="mb-0">
                            </div>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat">
                                <svg style="width: 35px; height: 35px;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-bar-chart-2 align-middle text-primary">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(auth()->user()->hasRole('Admin'))
        <div class="col-12 col-sm-6 col-xxl-3 d-flex mb-4">
            <div class="card flex-fill">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2">{{ $count['groups'] ?? 0 }}</h3>
                            <p class="mb-2">Total Groups</p>
                            <div class="mb-0">
                            </div>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat">
                                <svg style="width: 35px; height: 35px;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-book-open align-middle text-primary">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @endif
    </div>
    
@endsection
