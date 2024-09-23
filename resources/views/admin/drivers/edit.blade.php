@extends('layouts.backend')
@section('styles')

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet JavaScript -->

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

<link rel='stylesheet' href='https://unpkg.com/leaflet@1.8.0/dist/leaflet.css' crossorigin='' />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

@endsection

@section('content')

    <div class="card">
        <div class="card-header ps-0">
            <h2>Edit Driver</h2>
        </div>
        <form action="{{ route('admin.drivers.update', [$driver->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body">
                                                        
                <div class="mt-3">
                    <label for="formFile" class="form-label">Photo</label>
                    <input class="form-control" type="file" name="photo" id="formFile">
                    @error('photo')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="card-body">
                <div class="mb-2">
                    <label for="title">Name*</label>
                    <input type="text" id="title" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', isset($driver) ? $driver->name : '') }}" required>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <label for="email">Email*</label>
                    <input type="text" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', isset($driver) ? $driver->email : '') }}" required>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <label for="phone">Phone*</label>
                    <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone', isset($driver) ? $driver->phone : '') }}" required>
                    @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <label for="license">License*</label>
                    <input type="text" id="license" name="license" class="form-control @error('license') is-invalid @enderror"
                           value="{{ old('license', isset($driver) ? $driver->license : '') }}" required>
                    @error('license')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row card-body">
                <div class="col-4">
                    <div class="mb-2">
                        <label for="address">Address*</label>
                        <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address', isset($driver) ? $driver->address : '') }}" required>
                        @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-2">
                        <label for="Longitude">Longitude*</label>
                        <input type="text" id="Longitude" readonly name="Longitude"
                            class="form-control @error('Longitude') is-invalid @enderror"
                            value="{{ $driver->Longitude }}" required>
                        @error('Longitude')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-2">
                        <label for="Latitude">Latitude*</label>
                        <input type="text" id="Latitude" name="Latitude"
                        readonly
                            class="form-control @error('Latitude') is-invalid @enderror"
                            value="{{$driver->Latitude  }}" required>
                        @error('Latitude')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div id="map" style="height: 400px;"></div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Update</button>
                <a class="btn btn-secondary" href="{{ route('admin.drivers.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection


@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>


<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"></script>
{{--<!-- Leaflet Control Geocoder plugin -->--}}
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<!-- Leaflet Routing Machine JavaScript -->
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
    // Initialize Leaflet map
    var map = L.map('map').setView([{{ $driver->Latitude }}, {{ $driver->Longitude }}], 10); // Default view centered on the school's coordinates

    // Add a tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Initialize marker
    var marker = L.marker([{{ $driver->Latitude }}, {{ $driver->Longitude }}], {
        draggable: true // Make the marker draggable
    }).addTo(map);

    // Update latitude and longitude fields based on marker position
    marker.on('dragend', function(event) {
        var position = marker.getLatLng();
        document.getElementById('Latitude').value = position.lat.toFixed(6);
        document.getElementById('Longitude').value = position.lng.toFixed(6);
    });
</script>
@endsection
