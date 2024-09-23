@extends('layouts.backend')
@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
@endsection
@section('title', 'Edit Child Information')

@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Child Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.children.update', $child->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $child->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ $child->address }}" required>
                        </div>
                    </div>
                    {{-- <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="gender">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="male" {{ $child->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $child->gender == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="birth_date">Date of Birth</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ $child->birth_date }}" required>
                        </div>
                    </div> --}}

                    <div class="mb-3">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ $child->phone }}">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="Latitude">Latitude</label>
                            <input type="number" step="any" class="form-control" id="Latitude" name="Latitude" value="{{ $child->Latitude }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="Longitude">Longitude</label>
                            <input type="number" step="any" class="form-control" id="Longitude" name="Longitude" value="{{ $child->Longitude }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="map">Location</label>
                        <div id="map" style="height: 400px; width: 100%;" class="border rounded"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([{{ $child->Latitude ?? 0 }}, {{ $child->Longitude ?? 0 }}], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([{{ $child->Latitude ?? 0 }}, {{ $child->Longitude ?? 0 }}], {
                draggable: true
            }).addTo(map);

            marker.on('dragend', function(event) {
                var position = marker.getLatLng();
                document.getElementById('Latitude').value = position.lat;
                document.getElementById('Longitude').value = position.lng;
            });

            // Update marker position when Latitude or Longitude inputs change
            document.getElementById('Latitude').addEventListener('change', updateMarkerPosition);
            document.getElementById('Longitude').addEventListener('change', updateMarkerPosition);

            function updateMarkerPosition() {
                var lat = parseFloat(document.getElementById('Latitude').value);
                var lng = parseFloat(document.getElementById('Longitude').value);
                marker.setLatLng([lat, lng]);
                map.panTo([lat, lng]);
            }
        });
    </script>
@endsection
