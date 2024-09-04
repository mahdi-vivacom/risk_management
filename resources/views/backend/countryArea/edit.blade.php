@extends('backend.layout.master')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <form action="{{ route('country-areas.update', ['country_area' => $countryArea->id]) }}"
                            method="post" class="row g-3" novalidate>
                            @csrf
                            @method('patch')
                            <div class="col-md-6">
                                <label for="name" class="form-label">@lang ( 'admin_fields.area' )</label>
                                <input id="name" type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="@lang ( 'admin_fields.area' )" autocomplete="name"
                                    value="{{ old('name', $countryArea->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="color" class="form-label">@lang ( 'admin_fields.color' )</label>
                                <input id="color" type="text" name="color"
                                    class="form-control @error('color') is-invalid @enderror"
                                    placeholder="@lang ( 'admin_fields.color' )" autocomplete="color"
                                    value="{{ old('color', $countryArea->color) }}">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="lat" class="form-label">@lang('admin_fields.draw_map')</label>
                                <div id="polygons" style="height: 500px;width: 100%"></div>
                                <input type="hidden" class="form-control @error('lat') is-invalid @enderror" id="lat"
                                    name="lat">
                                @error('lat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-center">
                                <a href="{{ route('country-areas.index') }}" type="button"
                                    class="btn btn-outline-secondary btn-sm">@lang ( 'admin_fields.back' )</a>
                                <button type="submit" class="btn btn-outline-success btn-sm"
                                    onClick="this.form.submit(); this.disabled=true; this.innerText='Updating .....';">@lang('admin_fields.update')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_js')
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key={{ $config->google_key }}&libraries=places,drawing"></script>
    <script>
        var map;
        let polygon;
        var NewJson;
        var polygonArray = [];
        let data = {!! $countryArea->coordinates !!};
        let triangleCoords = [];
        var bounds = new google.maps.LatLngBounds();
        var drawingManager;
        var AreaLatlong = [];

        function initMap() {
            map = new google.maps.Map(
                document.getElementById("polygons"), {
                    center: new google.maps.LatLng(data[0].latitude, data[0].longitude),
                    zoom: 8,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: ['polygon']
                },
                polygonOptions: {
                    fillColor: '#93BE52',
                    fillOpacity: 0.5,
                    strokeWeight: 2,
                    strokeColor: '#000000',
                    clickable: false,
                    editable: true,
                    draggable: true,
                    zIndex: 1
                }
            });

            var centerControlDiv = document.createElement('div');
            var centerControl = new CenterControl(centerControlDiv, map);
            centerControlDiv.index = 1;
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);
            for (var i = 0; i < data.length; i++) {
                triangleCoords.push(new google.maps.LatLng(data[i].latitude, data[i].longitude));
            }
            for (i = 0; i < triangleCoords.length; i++) {
                bounds.extend(triangleCoords[i]);
            }
            var latlng = bounds.getCenter();
            map.setCenter(latlng)
            polygon = new google.maps.Polygon({
                paths: triangleCoords,
                strokeColor: '#FF0000',
                draggable: true,
                editable: true,
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35
            });
            polygon.setMap(map);
            polygonArray.push(polygon);
            map.fitBounds(bounds);
            google.maps.event.addListener(polygon.getPath(), "insert_at", getPolygonCoords);
            google.maps.event.addListener(polygon.getPath(), "set_at", getPolygonCoords);
            inputSerach = document.getElementById('newOpenstreet');
            autoPlace = document.getElementById('google_area');
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(inputSerach);
            var autocomplete = new google.maps.places.Autocomplete(autoPlace);
            autocomplete.bindTo('bounds', map);
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }
                map.setCenter(place.geometry.location);
                map.setZoom(10);
                var shortName = place.address_components[0] && place.address_components[0].short_name || '';
                var long_name = place.address_components[0] && place.address_components[0].long_name || '';
                var url = "https://nominatim.openstreetmap.org/search.php?polygon_geojson=1&format=json&q=" +
                    shortName;
                $.getJSON(url, function(result) {
                    var arrayLength = result.length;
                    document.getElementById('lat').value = "";
                    for (var i = 0; i < polygonArray.length; i++) {
                        polygonArray[i].setMap(null);
                    }
                    for (var i = 0; i < arrayLength; i++) {
                        if (result[i].geojson.type === "Polygon" || result[i].geojson.type ===
                            "MultiPolygon") {
                            var PlaceId = result[i].place_id;
                            break;
                        }
                    }
                    if (PlaceId) {
                        var bounds = new google.maps.LatLngBounds();
                        var url =
                            "https://nominatim.openstreetmap.org/details.php?polygon_geojson=1&format=json&place_id=" +
                            PlaceId;
                        $.getJSON(url, function(result) {
                            var data;
                            if (result.geometry.type === "Polygon") {
                                data = result.geometry.coordinates[0];
                            } else if (result.geometry.type === "MultiPolygon") {
                                data = result.geometry.coordinates[0][0];
                            } else {}
                            if (data) {
                                triangleCoords = [];
                                for (var i = 0; i < data.length; i++) {
                                    item = {}
                                    item["latitude"] = data[i][1].toString();
                                    item["longitude"] = data[i][0].toString();
                                    AreaLatlong.push(item);
                                    triangleCoords.push(new google.maps.LatLng(data[i][1], data[i][
                                        0]));
                                }
                                for (i = 0; i < triangleCoords.length; i++) {
                                    bounds.extend(triangleCoords[i]);
                                }
                                var latlng = bounds.getCenter();
                                polygon = new google.maps.Polygon({
                                    paths: triangleCoords,
                                    strokeColor: '#FF0000',
                                    draggable: true,
                                    editable: true,
                                    strokeOpacity: 0.8,
                                    strokeWeight: 2,
                                    fillColor: '#FF0000',
                                    fillOpacity: 0.35
                                });
                                polygonArray.push(polygon);
                                polygon.setMap(map);
                                map.fitBounds(bounds);
                                map.setCenter(latlng)
                                drawingManager.setDrawingMode(null);
                                drawingManager.setOptions({
                                    // drawingControl: false
                                });
                                let NewJson = JSON.stringify(AreaLatlong);
                                document.getElementById('lat').value = NewJson;
                                AreaLatlong = [];
                            }
                        });
                    }
                });


            });
        }

        function getPolygonCoords() {
            var len = polygon.getPath().getLength();
            var AreaLatlong = [];
            for (var i = 0; i < polygon.getPath().getLength(); i++) {
                var xy = polygon.getPath().getAt(i);
                item = {}
                item["latitude"] = xy.lat().toString();
                item["longitude"] = xy.lng().toString();
                AreaLatlong.push(item);
            }
            NewJson = JSON.stringify(AreaLatlong);
            document.getElementById('lat').value = NewJson;
            AreaLatlong = [];
        }

        function CenterControl(controlDiv, map) {
            var controlUI = document.createElement('div');
            controlUI.style.backgroundColor = '#fff';
            controlUI.style.border = '2px solid #fff';
            controlUI.style.borderRadius = '3px';
            controlUI.style.marginRight = '1px';
            controlUI.style.marginTop = '5px';
            controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
            controlUI.style.cursor = 'pointer';
            controlUI.style.marginBottom = '22px';
            controlUI.style.textAlign = 'center';
            controlUI.title = 'Delete Polygon';
            controlUI.id = 'delete_polygon';
            controlDiv.appendChild(controlUI);
            var controlText = document.createElement('div');
            controlText.style.color = 'rgb(25,25,25)';
            controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
            controlText.style.fontSize = '16px';
            controlText.style.lineHeight = '20px';
            controlText.style.paddingLeft = '5px';
            controlText.style.paddingRight = '5px';
            controlText.innerHTML = '<i class="fa fa-trash" aria-hidden="true"></i>';
            controlUI.appendChild(controlText);
            var count = 0;
            // Setup the click event listeners: simply set the map to Chicago.
            controlUI.addEventListener('click', function() {
                $('#delete_polygon').hide();
                count += 1;
                polygon.setMap(null);
                if (count <= 1) {
                    drawingManager = new google.maps.drawing.DrawingManager({
                        drawingMode: google.maps.drawing.OverlayType.POLYGON,
                        drawingControl: true,
                        drawingControlOptions: {
                            position: google.maps.ControlPosition.TOP_CENTER,
                            drawingModes: ['polygon']
                        },
                        polygonOptions: {
                            fillColor: '#93BE52',
                            fillOpacity: 0.5,
                            strokeWeight: 2,
                            strokeColor: '#000000',
                            clickable: false,
                            editable: true,
                            draggable: true,
                            zIndex: 1
                        }
                    });
                    drawingManager.setMap(map);

                    google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
                        for (var i = 0; i < polygon.getPath().getLength(); i++) {
                            // document.getElementById('lat').value += polygon.getPath().getAt(i).toUrlValue(6) + "|";
                            var xy = polygon.getPath().getAt(i);
                            item = {}
                            item["latitude"] = xy.lat().toString();
                            item["longitude"] = xy.lng().toString();
                            AreaLatlong.push(item);
                        }
                        let NewJson = JSON.stringify(AreaLatlong);
                        document.getElementById('lat').value = NewJson;
                        AreaLatlong = [];
                        polygonArray.push(polygon);
                        drawingManager.setDrawingMode(null);
                        drawingManager.setOptions({
                            // drawingControl: false
                        });

                        google.maps.event.addListener(polygon.getPath(), "insert_at", function() {
                            for (var i = 0; i < polygon.getPath().getLength(); i++) {
                                // document.getElementById('lat').value += polygon.getPath().getAt(i).toUrlValue(6) + "|";
                                var xy = polygon.getPath().getAt(i);
                                item = {}
                                item["latitude"] = xy.lat().toString();
                                item["longitude"] = xy.lng().toString();
                                AreaLatlong.push(item);
                            }
                            let NewJson = JSON.stringify(AreaLatlong);
                            document.getElementById('lat').value = NewJson;
                            AreaLatlong = [];
                        });
                        google.maps.event.addListener(polygon.getPath(), "set_at", function() {
                            for (var i = 0; i < polygon.getPath().getLength(); i++) {
                                // document.getElementById('lat').value += polygon.getPath().getAt(i).toUrlValue(6) + "|";
                                var xy = polygon.getPath().getAt(i);
                                item = {}
                                item["latitude"] = xy.lat().toString();
                                item["longitude"] = xy.lng().toString();
                                AreaLatlong.push(item);
                            }
                            let NewJson = JSON.stringify(AreaLatlong);
                            document.getElementById('lat').value = NewJson;
                            AreaLatlong = [];
                        });
                    });
                    google.maps.event.addListener(drawingManager, "drawingmode_changed", function() {
                        if (drawingManager.getDrawingMode() != null) {
                            document.getElementById('lat').value = "";
                            for (var i = 0; i < polygonArray.length; i++) {
                                polygonArray[i].setMap(null);
                            }
                            polygonArray = [];
                            AreaLatlong = [];
                        }
                    });
                }
            });
        }

        initMap();
    </script>
@endsection
