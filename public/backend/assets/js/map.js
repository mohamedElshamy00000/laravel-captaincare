// Define the main function
function initializeMap(driver, children, school) {
    var map = L.map('map').setView([31.4178, 31.8147], 10); // Centered on Egypt New Damietta City

    // Function to change map style
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

    // Load children markers
    function loadChildrenMarkers(children) {
        children.forEach(function (child) {
            var marker = L.marker([child.Latitude, child.Longitude]).addTo(map).bindPopup(child.name);
            childrenMarkers.push(marker);
        });
        calculateAndDisplayRoute(); // Call route calculation after loading children
    }

    // Update map marker (school or driver)
    function updateMapMarker(entity, type) {
        var iconUrl = '';
        var popupContent = '';

        if (type === 'school') {
            iconUrl = "../../../backend/assets/images/education.png"; // school icon URL
            popupContent = entity.name;
            if (schoolMarker) {
                map.removeLayer(schoolMarker);
            }
            schoolMarker = L.marker([entity.Latitude, entity.Longitude], { icon: L.icon({ iconUrl: iconUrl, iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -32] }) }).addTo(map).bindPopup(popupContent);
        } else if (type === 'driver') {
            iconUrl = 'https://scontent.fcai21-3.fna.fbcdn.net/v/t39.30808-1/353402738_683491690456187_5615274815862490281_n.jpg?stp=c22.0.440.440a_dst-jpg_p480x480&_nc_cat=100&ccb=1-7&_nc_sid=f4b9fd&_nc_ohc=Q5Dua5_NBrsQ7kNvgFu8rh6&_nc_ht=scontent.fcai21-3.fna&oh=00_AYC6FajaGc1ZUu4tW35nrWvnRz7IiXivDx22-4N3I47VjA&oe=669395DA'; // Replace with your driver icon URL
            popupContent = entity.name;
            if (driverMarker) {
                map.removeLayer(driverMarker);
            }
            driverMarker = L.marker([entity.Latitude, entity.Longitude], { icon: L.icon({ iconUrl: iconUrl, iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -32] }) }).addTo(map).bindPopup(popupContent);
            calculateAndDisplayRoute(); // Call route calculation after updating driver
        }
    }

    // Function to calculate and display route
    function calculateAndDisplayRoute() {
        if (routeControl) {
            map.removeControl(routeControl);
        }

        var waypoints = [];

        if (driverMarker) {
            waypoints.push(L.latLng(driverMarker.getLatLng())); // Add driver location first
        }

        childrenMarkers.forEach(function (childMarker) {
            waypoints.push(childMarker.getLatLng()); // Add all children locations
        });

        if (schoolMarker) {
            waypoints.push(L.latLng(schoolMarker.getLatLng())); // Add school location last
        }

        routeControl = L.Routing.control({
            waypoints: waypoints,
            createMarker: function () {
                return null;
            }, // Hide default markers
            routeWhileDragging: false, // Disable route editing with mouse
            draggableWaypoints: false, // Disable dragging of waypoints
        }).addTo(map);

        routeControl.on('routesfound', function (e) {
            var routes = e.routes;
            var summary = routes[0].summary;

            var routeData = {
                waypoints: waypoints.map(function (waypoint) {
                    return {
                        lat: waypoint.lat,
                        lng: waypoint.lng,
                    };
                }),
                totalDistance: summary.totalDistance,
                totalTime: summary.totalTime,
            };

            console.log(routeData);
        });
    }

    // Initialize markers and load children markers
    if (school) {
        updateMapMarker(school, 'school');
    }

    if (children && children.length > 0) {
        loadChildrenMarkers(children);
    }

    if (driver) {
        updateMapMarker(driver, 'driver');
    }
}




// main function
// $(document).ready(function () {
//     var map = L.map('map').setView([31.4178, 31.8147], 10); // Centered on Egypt New Damietta City

//     function changeMapStyle(style) {
//         L.tileLayer(style, {
//             attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
//         }).addTo(map);
//     }

//     changeMapStyle('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png');

//     var group = @json($group);
//     var schoolMarker;
//     var driverMarker;
//     var childrenMarkers = [];
//     var routeControl;
//     var driverLocation = null;

//     // Load children markers
//     function loadChildren(children) {
//         children.forEach(function (child) {
//             var marker = L.marker([child.Latitude, child.Longitude]).addTo(map).bindPopup(child.name);
//             childrenMarkers.push(marker);
//         });
//         calculateAndDisplayRoute(); // Call route calculation after loading children
//     }

//     // Update map marker (school or driver)
//     function updateMapMarker(entity, type) {
//         var iconUrl = '';
//         var popupContent = '';

//         if (type === 'school') {
//             iconUrl = "{{ asset('backend/assets/images/education.png') }}"; // Replace with your school icon URL
//             popupContent = entity.name;
//             if (schoolMarker) {
//                 map.removeLayer(schoolMarker);
//             }
//             schoolMarker = L.marker([entity.Latitude, entity.Longitude], { icon: L.icon({ iconUrl: iconUrl, iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -32] }) }).addTo(map).bindPopup(popupContent);
//         } else if (type === 'driver') {
//             iconUrl = 'https://scontent.fcai21-3.fna.fbcdn.net/v/t39.30808-1/353402738_683491690456187_5615274815862490281_n.jpg?stp=c22.0.440.440a_dst-jpg_p480x480&_nc_cat=100&ccb=1-7&_nc_sid=f4b9fd&_nc_ohc=Q5Dua5_NBrsQ7kNvgFu8rh6&_nc_ht=scontent.fcai21-3.fna&oh=00_AYC6FajaGc1ZUu4tW35nrWvnRz7IiXivDx22-4N3I47VjA&oe=669395DA'; // Replace with your driver icon URL
//             popupContent = entity.name;
//             if (driverMarker) {
//                 map.removeLayer(driverMarker);
//             }
//             driverMarker = L.marker([entity.Latitude, entity.Longitude], { icon: L.icon({ iconUrl: iconUrl, iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -32] }) }).addTo(map).bindPopup(popupContent);
//         }
//     }

//     // Assuming $groups is available with school, driver, and children data
//     if (group.school) {
//         var school = group.school;
//         updateMapMarker(school, 'school');
//         loadChildren(group.children);
//     }

//     if (group.driver) {
//         var driver = group.driver;
//         updateMapMarker(driver, 'driver');
//     }

//     // Function to calculate and display route
//     function calculateAndDisplayRoute() {
//         if (routeControl) {
//             map.removeControl(routeControl);
//         }

//         var waypoints = [];

//         if (driverMarker) {
//             waypoints.push(L.latLng(driverMarker.getLatLng())); // Add driver location first
//         }

//         childrenMarkers.forEach(function (childMarker) {
//             waypoints.push(childMarker.getLatLng()); // Add all children locations
//         });

//         if (schoolMarker) {
//             waypoints.push(L.latLng(schoolMarker.getLatLng())); // Add school location last
//         }

//         routeControl = L.Routing.control({
//             waypoints: waypoints,
//             createMarker: function () {
//                 return null;
//             }, // Hide default markers
//             routeWhileDragging: false, // Disable route editing with mouse
//             draggableWaypoints: false, // Disable dragging of waypoints
//         }).addTo(map);

//         routeControl.on('routesfound', function (e) {
//             var routes = e.routes;
//             var summary = routes[0].summary;

//             var routeData = {
//                 waypoints: waypoints.map(function (waypoint) {
//                     return {
//                         lat: waypoint.lat,
//                         lng: waypoint.lng,
//                     };
//                 }),
//                 totalDistance: summary.totalDistance,
//                 totalTime: summary.totalTime,
//             };

//             console.log(routeData);
//         });
//     }
// });