@extends('layouts.backend')

@section('content')


<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    {{-- <div class="flex-shrink-0 me-4">
                        @if ($driver->photo)
                        <img src="{{ asset('backend/assets/images/companies/img-1.png') }}" alt="" class="avatar-sm">
                        @endif
                    </div> --}}

                    <div class="flex-grow-1 overflow-hidden">
                        <h2 class="text-truncate font-size-30">{{ $driver->name }}
                            @if ($driver->status == 1)
                                <span class="badge badge-pill badge-soft-success font-size-11">active</span>
                            @else
                                <span class="badge badge-pill badge-soft-danger font-size-11">not active</span>
                            @endif
                        </h2>
                    </div>
                </div>

                <h5 class="font-size-15 mt-4">Details :</h5>

                <p class="text-muted">{{ $driver->address }},</p>

                <div class="text-muted mt-4">
                    <p><i class="mdi mdi-chevron-right text-primary me-1"></i> {{ $driver->email }}</p>
                    <p><i class="mdi mdi-chevron-right text-primary me-1"></i> {{ $driver->phone }}</p>
                    <p><i class="mdi mdi-chevron-right text-primary me-1"></i> {{ $driver->license }}</p>
                </div>
                
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-lg-4">

        <div class="row">
            @foreach ($driver->cars as $car)
                <div class="card col-12 p-0">
                    <img class="card-img-top img-fluid rounded" src="{{ asset('assets/files/drivers/car/' . $car->photo) }}" alt="Card image cap">
                    <div class="card-body">
                        <h4 class="card-title">model : {{ $car->model }}</h4>
                        <p class="card-text">male : {{ $car->make }}.</p>
                        @if ($car->status == 1)
                            <span class="badge badge-pill badge-soft-success font-size-11">active</span>
                        @else
                            <span class="badge badge-pill badge-soft-danger font-size-11">not active</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <a href="{{ asset('assets/files/drivers/car/' . $car->license) }}" class="card-link">License Download</a>
                    </div>
                </div>
            @endforeach
                    
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end row -->

@endsection
