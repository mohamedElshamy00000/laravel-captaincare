@extends('layouts.backend')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" crossorigin='' />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            Create Group
        </div>
        <form action="{{ route("admin.school.store.group") }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="title">Name*</label>
                        <input type="text" id="title" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('title', isset($group) ? $group->name : '') }}" required>
                        @error('name')
                        <span class="invalid-feedback" group="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="description">Description*</label>
                        <input type="description" id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                               value="{{ old('description', isset($group) ? $group->description : '') }}" required>
                        @error('description')
                        <span class="invalid-feedback" group="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 ajax-select mt-3 mt-lg-0 col-md-3">
                        <label class="form-label">School</label>
                        <select class="form-control select2-ajax" name="school_id" required></select>
                        @error('school_id')
                        <span class="invalid-feedback" group="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-3 ajax-select mt-3 mt-lg-0 col-md-6">
                        <label class="form-label">Group Childrens</label>
                        <select class="form-control select2-ajax-children" name="childrens[]" multiple="multiple" required></select>
                        @error('childrens')
                        <span class="invalid-feedback" group="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-3 mt-3 mt-lg-0 col-md-3">
                        <label for="driver">Group Driver* </label>
                        <select name="driver" id="driver" class="form-control select2 @error('driver') is-invalid @enderror" required>
                            <option selected>choose driver</option>
                            @foreach($drivers as $id => $driver)
                                <option value="{{ $driver->id }}" data-lat="{{ $driver->Latitude }}" data-lng="{{ $driver->Longitude }}">{{ $driver->name }}</option>
                            @endforeach
                        </select>
                        @error('driver')
                        <span class="invalid-feedback" group="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <input type="hidden" name="waypoints" value="" id="waypoints">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div id="map" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Save</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>

$(document).ready(function () {
    var map = L.map('map').setView([31.4178, 31.8147], 10); // Centered on Egypt New Damietta City

    function changeMapStyle(style) {
        L.tileLayer(style, {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    }

    changeMapStyle('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png');


    var schoolMarker;
    var driverMarker;
    var childrenMarkers = [];
    var routeControl;
    var driverLocation = null;

    $(".select2-ajax").select2({
        ajax: {
            url: "{{ route('admin.getSchools') }}",
            dataType: "json",
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.map(function (school) {
                        return {
                            id: school.id,
                            text: school.name,
                            latitude: school.Latitude,
                            longitude: school.Longitude,
                            address: school.address,
                        };
                    })
                };
            },
            cache: true
        },
        placeholder: "Select a school",
        minimumInputLength: 1,
        templateResult: function (school) {
            if (school.loading) return school.text;

            var $container = $(
                "<div class='select2-result-school clearfix'>" +
                "<div class='select2-result-school__meta'>" +
                "<div class='select2-result-school__title'></div>" +
                "</div>" +
                "</div>"
            );

            $container.find(".select2-result-school__title").text(school.text);

            return $container;
        },
        templateSelection: function (school) {
            if (school.id) {
                updateMapMarkers(school.latitude, school.longitude);
                loadChildren(school.id);

            }
            return school.text;
        }
    });

    $(".select2-ajax-children").select2({
        placeholder: "Select children",
        minimumInputLength: 1,
        multiple: true
    });

    function loadChildren(schoolId) {
        $.ajax({
            url: "{{ route('admin.getChildren') }}",
            data: { school_id: schoolId },
            success: function (data) {
                var newOptions = data.map(function (child) {
                    return {
                        id: child.id,
                        text: child.name + ' - ' + child.phone,
                        phone: child.phone,
                        latitude: child.Latitude,
                        longitude: child.Longitude
                    };
                });

                $(".select2-ajax-children").empty().select2({
                    data: newOptions,
                    placeholder: "Select children",
                    minimumInputLength: 1, // Minimum characters before showing results
                    allowClear: true, // Option to clear selection
                    multiple: true, // Enable multiple selection if needed
                    closeOnSelect: false, // Keep dropdown open after selection
                    tags: false, // Disable tagging of new options
                    dropdownAutoWidth: true, // Auto-width for dropdown
                    width: "100%", // Set width of the select box

                    // Customizing how results are displayed in the dropdown
                    templateResult: function (child) {
                        return child.text; // Display text of each result item
                    },

                    // Customizing the selection display
                    templateSelection: function (child) {
                        return child.text; // Display selected option text
                    }
                });

                // Attach an event handler for when children are selected or unselected
                $(".select2-ajax-children").off("select2:select select2:unselect").on("select2:select select2:unselect", function (e) {
                    var selectedChildren = $(this).select2("data");
                    addChildrenMarkers(selectedChildren);
                    calculateAndDisplayRoute(selectedChildren);
                });
            }
        });
    }


    $(".select2-ajax-classes").select2({
        placeholder: "Select a class",
        minimumInputLength: 1
    });

    function loadclasses(schoolId) {
        $.ajax({
            url: "{{ route('admin.getClasses') }}",
            data: { school_id: schoolId },
            success: function (data) {
                var newOptions = data.map(function (classe) {
                    return {
                        id: classe.id,
                        text: classe.name
                    };
                });

                $(".select2-ajax-classes").empty().select2({
                    data: newOptions
                });
            }
        });
    }

    $(".select2-ajax-classes").on("select2:select", function (e) {
        var schoolId = $(".select2-ajax").val();
        var classId = e.params.data.id;
        loadChildren(schoolId, classId);
    });

    function updateMapMarkers(lat, lng) {
        if (schoolMarker) {
            map.removeLayer(schoolMarker);
        }
        schoolMarker = L.marker([lat, lng]).addTo(map);
        map.setView([lat, lng], 10);
    }

    function addChildrenMarkers(children) {
        childrenMarkers.forEach(function (marker) {
            map.removeLayer(marker);
        });
        childrenMarkers = children.map(function (child) {
            return L.marker([child.latitude, child.longitude]).addTo(map).bindPopup(child.text);
        });
    }

    var driverIcon = L.icon({
        iconUrl: 'https://scontent.fcai21-3.fna.fbcdn.net/v/t39.30808-1/353402738_683491690456187_5615274815862490281_n.jpg?stp=c22.0.440.440a_dst-jpg_p480x480&_nc_cat=100&ccb=1-7&_nc_sid=f4b9fd&_nc_ohc=Q5Dua5_NBrsQ7kNvgFu8rh6&_nc_ht=scontent.fcai21-3.fna&oh=00_AYC6FajaGc1ZUu4tW35nrWvnRz7IiXivDx22-4N3I47VjA&oe=669395DA',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });

    function addDriverMarker(driver) {
        if (driverMarker) {
            map.removeLayer(driverMarker);
        }
        var driverLat = driver.latitude;
        var driverLng = driver.longitude;
        var driverName = driver.text;
        driverLocation = { lat: driverLat, lng: driverLng };
        driverMarker = L.marker([driverLat, driverLng], { icon: driverIcon }).addTo(map).bindPopup(driverName);
    }



    // Event listener for driver selection
    $('#driver').change(function () {
        var selectedDriver = $(this).find(':selected');
        var driverLat = selectedDriver.data('lat');
        var driverLng = selectedDriver.data('lng');
        var driverName = selectedDriver.text();

        // Add driver marker
        addDriverMarker({ latitude: driverLat, longitude: driverLng, text: driverName });
        calculateAndDisplayRoute($(".select2-ajax-children").select2("data"));
    });

    function calculateAndDisplayRoute(children) {
        if (routeControl) {
            map.removeControl(routeControl);
        }

        var waypoints = [];

        if (driverLocation) {
            waypoints.push(L.latLng(driverLocation.lat, driverLocation.lng));
        }

        children.forEach(function (child) {
            waypoints.push(L.latLng(child.latitude, child.longitude));
        });

        if (schoolMarker) {
            waypoints.push(L.latLng(schoolMarker.getLatLng()));
        }

        routeControl = L.Routing.control({
            waypoints: waypoints,
            createMarker: function() { return null; }, // Hide default markers
            routeWhileDragging: false, // Disable route editing with mouse
            draggableWaypoints: false, // Disable dragging of waypoints
        }).addTo(map);

        routeControl.on('routesfound', function(e) {
            var routes = e.routes;
            var summary = routes[0].summary;

            var routeData = {
                waypoints: waypoints.map(function(waypoint) {
                    return {
                        waypoint : waypoint,
                    };
                }),
                totalDistance: summary.totalDistance,
                totalTime: summary.totalTime
                // Add more data as needed
            };

            var waypointsString = JSON.stringify(routeData.waypoints);
            // Set JSON string in the input field
            $('#waypoints').val(waypointsString);

        });
    }
});
</script>

</body>
@endsection
