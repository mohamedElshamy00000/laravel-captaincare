{{-- @extends('layouts.backend')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <!-- Custom CSS -->
    <style>
        #map { height: 600px; } /* Adjust map height as needed */
    </style>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <div id="map"></div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
$(document).ready(function () {
    var map = L.map('map').setView([31.4178, 31.8147], 10); // Centered on Egypt New Damietta City

    // Initialize map style
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Store the map in maps object
    var maps = {};

    // Loop through each group to add markers and routes
    @foreach ($groups as $group)
        var group_{{ $group->id }} = @json($group);

        // Create markers for school and driver
        var schoolMarker_{{ $group->id }} = L.marker([{{ $group->school->Latitude }}, {{ $group->school->Longitude }}]).addTo(map)
            .bindPopup('{{ $group->school->name }}');

        var driverMarker_{{ $group->id }} = L.marker([{{ $group->driver->Latitude }}, {{ $group->driver->Longitude }}]).addTo(map)
            .bindPopup('{{ $group->driver->name }}');

        // Create children markers
        var childrenMarkers_{{ $group->id }} = [];
        @foreach ($group->children as $child)
            var childMarker_{{ $child->id }} = L.marker([{{ $child->Latitude }}, {{ $child->Longitude }}]).addTo(map)
                .bindPopup('{{ $child->name }}');
            childrenMarkers_{{ $group->id }}.push(childMarker_{{ $child->id }});
        @endforeach

        // Generate random color for the route
        var randomColor = getRandomColor();

        // Create route control for the group
        var routeControl_{{ $group->id }} = L.Routing.control({
            waypoints: [
                L.latLng({{ $group->school->Latitude }}, {{ $group->school->Longitude }}),
                L.latLng({{ $group->driver->Latitude }}, {{ $group->driver->Longitude }}),
                @foreach ($group->children as $child)
                    L.latLng({{ $child->Latitude }}, {{ $child->Longitude }}),
                @endforeach
            ],
            lineOptions: {
                styles: [{ color: randomColor, opacity: 0.8, weight: 5 }]
            },
            createMarker: function () { return null; }, // Hide default markers
            routeWhileDragging: false, // Disable route editing with mouse
            draggableWaypoints: false, // Disable dragging of waypoints
        }).addTo(map);

        // Store map and route control in maps object
        maps[{{ $group->id }}] = {
            map: map,
            routeControl: routeControl_{{ $group->id }}
        };

    @endforeach

    // Function to generate a random color for route lines
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

});

</script>
@endsection --}}
@extends('layouts.backend')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <!-- Custom CSS -->
    <style>
        #map { height: 600px; } /* Adjust map height as needed */
        .btn-group-toggle label.btn.active {
            background-color: #007bff;
            color: #fff;
        }
        .leaflet-routing-container{
            display: none;
        }
    </style>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <div id="map"></div>
        <div class="mt-3">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-primary active" id="showAllRoutesBtn">
                    <input type="radio" name="options" id="showAllRoutes" autocomplete="off" checked> Show All Routes
                </label>
                <label class="btn btn-primary" id="hideAllRoutesBtn">
                    <input type="radio" name="options" id="hideAllRoutes" autocomplete="off"> Hide All Routes
                </label>
            </div>
            <div class="mt-3">
                <select id="groupDropdown" class="form-control">
                    <option value="">Select Group</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
$(document).ready(function () {
    var map = L.map('map').setView([31.4178, 31.8147], 10); // Centered on Egypt New Damietta City

    // Initialize map style
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Store the map in maps object
    var maps = {};

    // Define an array to hold all route controls
    var routeControls = [];

    // Loop through each group to add markers and routes
    @foreach ($groups as $group)
        var group_{{ $group->id }} = @json($group);

        // Create markers for school and driver
        var schoolMarker_{{ $group->id }} = L.marker([{{ $group->school->Latitude }}, {{ $group->school->Longitude }}]).addTo(map)
            .bindPopup('{{ $group->school->name }}');

        var driverMarker_{{ $group->id }} = L.marker([{{ $group->driver->Latitude }}, {{ $group->driver->Longitude }}]).addTo(map)
            .bindPopup('{{ $group->driver->name }}');

        // Create children markers
        var childrenMarkers_{{ $group->id }} = [];
        @foreach ($group->children as $child)
            var childMarker_{{ $child->id }} = L.marker([{{ $child->Latitude }}, {{ $child->Longitude }}]).addTo(map)
                .bindPopup('{{ $child->name }}');
            childrenMarkers_{{ $group->id }}.push(childMarker_{{ $child->id }});
        @endforeach

        // Generate random color for the route
        var randomColor = getRandomColor();

        // Create route control for the group
        var routeControl_{{ $group->id }} = L.Routing.control({
            waypoints: [
                L.latLng({{ $group->school->Latitude }}, {{ $group->school->Longitude }}),
                L.latLng({{ $group->driver->Latitude }}, {{ $group->driver->Longitude }}),
                @foreach ($group->children as $child)
                    L.latLng({{ $child->Latitude }}, {{ $child->Longitude }}),
                @endforeach
            ],
            lineOptions: {
                styles: [{ color: randomColor, opacity: 0.8, weight: 5 }]
            },
            createMarker: function () { return null; }, // Hide default markers
            routeWhileDragging: false, // Disable route editing with mouse
            draggableWaypoints: false, // Disable dragging of waypoints
        });

        // Add route control to map and store in routeControls array
        routeControl_{{ $group->id }}.addTo(map);
        routeControls.push(routeControl_{{ $group->id }});

        // Store map and route control in maps object
        maps[{{ $group->id }}] = {
            map: map,
            routeControl: routeControl_{{ $group->id }}
        };

    @endforeach

    // Function to generate a random color for route lines
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Toggle show/hide all routes
    $('#showAllRoutesBtn').click(function () {
        routeControls.forEach(function (control) {
            control.addTo(map);
        });
    });

    $('#hideAllRoutesBtn').click(function () {
        routeControls.forEach(function (control) {
            map.removeControl(control);
        });
    });

    // Change route control based on dropdown selection
    $('#groupDropdown').change(function () {
        var groupId = $(this).val();
        if (groupId === '') {
            routeControls.forEach(function (control) {
                control.addTo(map); // Show all routes if no group selected
            });
        } else {
            routeControls.forEach(function (control) {
                map.removeControl(control); // Hide all routes
            });
            maps[groupId].routeControl.addTo(map); // Add selected group's route to map
        }
    });

});

</script>
@endsection
