@extends('backend.layout.master')
@section('content')
    <section id="gmaps-utils" class="section">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body container-fluid">
                        <div id="context-menu" style="width: 100%;height: 550px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_js')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $config->google_key }}&libraries=visualization"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let map;
            let regions = @json($regions); // Convert PHP array to JavaScript array

            function initialize() {
                const mapDiv = document.getElementById('context-menu');
                if (!mapDiv) {
                    console.error('Map container not found');
                    return;
                }

                map = new google.maps.Map(mapDiv, {
                    zoom: 6,
                    center: {
                        lat: 5.1521,
                        lng: 46.1996
                    },
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                // Draw each region
                regions.forEach(function(region) {
                    const coordinates = JSON.parse(region.coordinates).map(coord => ({
                        lat: parseFloat(coord.latitude),
                        lng: parseFloat(coord.longitude)
                    }));

                    const regionPolygon = new google.maps.Polygon({
                        paths: coordinates,
                        strokeColor: region.color ||
                            '#FF0000', // Use a default color if none is provided
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: region.color || '#FF0000',
                        fillOpacity: 0.35
                    });

                    regionPolygon.setMap(map);

                    const infoWindow = new google.maps.InfoWindow({
                        content: `<div style="margin: 0; padding: 0; font-size: 14px;">${region.name}</div>`,
                    });

                    // Show infoWindow on hover
                    google.maps.event.addListener(regionPolygon, 'mouseover', function(event) {
                        infoWindow.setPosition(event.latLng);
                        infoWindow.open(map);
                        removeInfoWindowCloseButton(); // Attempt to remove the close button
                    });

                    // Hide infoWindow when not hovering
                    google.maps.event.addListener(regionPolygon, 'mouseout', function() {
                        infoWindow.close();
                    });

                    // Zoom in and center on the region when clicked
                    google.maps.event.addListener(regionPolygon, 'click', function() {
                        const bounds = new google.maps.LatLngBounds();
                        regionPolygon.getPath().forEach(function(coord) {
                            bounds.extend(coord);
                        });
                        map.fitBounds(bounds); // Zooms and centers the map to the selected region
                    });
                });
            }

            // Function to remove the close button from the InfoWindow with retries
            function removeInfoWindowCloseButton() {
                const interval = setInterval(() => {
                    const closeButton = document.querySelector('.gm-style-iw button');
                    if (closeButton) {
                        closeButton.style.display = 'none';
                        clearInterval(interval); // Stop checking once the button is hidden
                    }
                }, 50); // Check every 50ms until the button is hidden
            }

            // Initialize map after the window has loaded
            google.maps.event.addDomListener(window, 'load', initialize);
        });
    </script>
@endsection
