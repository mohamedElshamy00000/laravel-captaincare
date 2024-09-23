@extends('layouts.backend')
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>


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

<div class="card border-0 shadow-sm">
    <div class="card-header">
        Create school
    </div>
    <form action="{{ route("admin.schools.store") }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="mb-2">
                <label for="name">Name*</label>
                <input type="text" id="name" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="mb-2">
                <label for="phone">Phone*</label>
                <input type="text" id="phone" name="phone"
                       class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone') }}" required>
                @error('phone')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="mb-2">
                <label for="address">Address*</label>
                <input type="text" id="address" name="address"
                       class="form-control @error('address') is-invalid @enderror"
                       value="{{ old('address') }}" required>
                @error('address')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="mb-2">
                <label for="email">Email*</label>
                <input type="email" id="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-2">
                <label for="Latitude">Latitude*</label>
                <input type="text" id="Latitude" name="Latitude"
                       class="form-control @error('Latitude') is-invalid @enderror"
                       value="{{ old('Latitude') }}" required>
                @error('Latitude')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-2">
                <label for="Longitude">Longitude*</label>
                <input type="text" id="Longitude" name="Longitude"
                       class="form-control @error('Longitude') is-invalid @enderror"
                       value="{{ old('Longitude') }}" required>
                @error('Longitude')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-4">
                <div id="map" style="height: 400px;"></div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary me-2" type="submit">Save</button>
            <a class="btn btn-secondary" href="{{ route('admin.schools.index') }}">Back to list</a>
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
    var map = L.map('map').setView([31.4178, 31.8147], 10); // Default view centered on Egypt New Damietta City

    // Add a tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Initialize marker
    var marker = L.marker([31.4178, 31.8147], {
        draggable: true // Make the marker draggable
    }).addTo(map);

    // Update latitude and longitude fields based on marker position
    marker.on('dragend', function(event) {
        var position = marker.getLatLng();
        $('#Latitude').val(position.lat.toFixed(6));
        $('#Longitude').val(position.lng.toFixed(6));
    });

</script>
@endsection
