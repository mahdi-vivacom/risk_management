@extends( 'backend.layout.master' )
@section( 'custom_css' )
@endsection
@section( 'content' )
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body pt-3">
                    <form class="row g-3" action="{{ route ( 'country-areas.store' ) }}" method="post" novalidate>
                        @csrf
                        <div class="col-md-6">
                            <label for="name" class="form-label">@lang ( 'admin_fields.area' )</label>
                            <input id="name" type="text" name="name"
                                class="form-control @error( 'name' ) is-invalid @enderror"
                                placeholder="@lang ( 'admin_fields.area' )" autocomplete="name"
                                value="{{ old ( 'name' ) }}">
                            @error( 'name' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="skill" class="form-label">@lang ( 'admin_fields.skill' )</label>
                            <select name="skill[]" id="skill" multiple="multiple"
                                class="form-select select2 @error( 'skill' ) is-invalid @enderror">
                                <option value="">@lang ( 'admin_fields.select' )</option>
                                @foreach ( $skills as $skill )
                                    <option value="{{ $skill->id }}" {{ old ( 'skill' ) == $skill->id ? 'selected' : '' }}>
                                        {{ $skill->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error( 'skill' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="country_id" class="form-label">@lang ( 'admin_fields.country' )</label>
                            <select name="country_id" id="country_id"
                                class="form-select select2 @error( 'country_id' ) is-invalid @enderror">
                                <option value="">@lang ( 'admin_fields.select' )</option>
                                @foreach ( $countries as $country )
                                    <option value="{{ $country->id }}" {{ old ( 'country_id' ) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error( 'country_id' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="timezone" class="form-label">@lang ( 'admin_fields.timezone' )</label>
                            <select name="timezone" id="timezone"
                                class="form-select select2 @error( 'timezone' ) is-invalid @enderror">
                                <option value="">@lang ( 'admin_fields.select' )</option>
                                @foreach ( $timezones as $time )
                                    <option value="{{ $time }}" {{ old ( 'timezone' ) == $time ? 'selected' : '' }}>
                                        {{ $time }}
                                    </option>
                                @endforeach
                            </select>
                            @error( 'timezone' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="lat" class="form-label">@lang('admin_fields.draw_map')</label>
                            <div id="polygons" style="height: 500px;width: 100%"></div>
                            <input type="hidden" class="form-control @error( 'lat' ) is-invalid @enderror" id="lat"
                                name="lat">
                            @error( 'lat' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-sm">@lang ( 'admin_fields.save' )</button>
                            <button type="reset" class="btn btn-outline-warning btn-sm">@lang ( 'admin_fields.clear'
                                )</button>
                            <a href="{{ route ( 'country-areas.index' ) }}" type="button"
                                class="btn btn-outline-secondary btn-sm">@lang (
                                'admin_fields.back' )</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section( 'custom_js' )
<script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key={{$config->google_key}}&libraries=places,drawing"></script>
<script type="text/javascript">

    $(document).on('keypress', '#manual_toll_price', function (event) {
        if (event.keyCode == 46 || event.keyCode == 8) {
        }
        else {
            if (event.keyCode < 48 || event.keyCode > 57) {
                event.preventDefault();
            }
        }
    });

    function changeBill(type) {
        switch (type) {
            case "1":
                document.getElementById('start_time').style.display = 'block';
                document.getElementById('start_day').style.display = 'none';
                document.getElementById('start_date').style.display = 'none';
                break;
            case "2":
                document.getElementById('start_time').style.display = 'none';
                document.getElementById('start_day').style.display = 'block';
                document.getElementById('start_date').style.display = 'none';
                break;
            case "3":
                document.getElementById('start_time').style.display = 'none';
                document.getElementById('start_day').style.display = 'none';
                document.getElementById('start_date').style.display = 'block';
                break;
            default:
                document.getElementById('start_time').style.display = 'none';
                document.getElementById('start_day').style.display = 'none';
                document.getElementById('start_date').style.display = 'none';
        }
    }

    var map;
    var polygonArray = [];
    let inputSerach;
    let polygon;
    var drawingManager;
    let triangleCoords = [];
    var AreaLatlong = [];
    var bounds = new google.maps.LatLngBounds();

    function initMap() {
        map = new google.maps.Map(
            document.getElementById("polygons"), {
            center: new google.maps.LatLng(37.4419, -122.1419),
            zoom: 10,
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
        drawingManager.setMap(map);
        var options = {
            types: ['(cities)'],
        };
        inputSerach = document.getElementById('newOpenstreet');
        autoPlace = document.getElementById('google_area');
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(inputSerach);
        var autocomplete = new google.maps.places.Autocomplete(autoPlace, options);
        autocomplete.bindTo('bounds', map);
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }
            map.setCenter(place.geometry.location);
            map.setZoom(10);
            var shortName = place.address_components[0] && place.address_components[0].short_name || '';
            var long_name = place.address_components[0] && place.address_components[0].long_name || '';
            var url = "https://nominatim.openstreetmap.org/search.php?polygon_geojson=1&format=json&q=" + shortName;
            $.getJSON(url, function (result) {
                console.log(result);
                var arrayLength = result.length;
                document.getElementById('lat').value = "";
                for (var i = 0; i < polygonArray.length; i++) {
                    polygonArray[i].setMap(null);
                }
                for (var i = 0; i < arrayLength; i++) {
                    if (result[i].geojson.type === "Polygon" || result[i].geojson.type === "MultiPolygon") {
                        var PlaceId = result[i].place_id;
                        break;
                    }
                }
                if (PlaceId) {
                    var bounds = new google.maps.LatLngBounds();
                    var url = "https://nominatim.openstreetmap.org/details.php?polygon_geojson=1&format=json&place_id=" + PlaceId;
                    $.getJSON(url, function (result) {
                        var data;
                        if (result.geometry.type === "Polygon") {
                            data = result.geometry.coordinates[0];
                        } else if (result.geometry.type === "MultiPolygon") {
                            data = result.geometry.coordinates[0][0];
                        } else {
                        }
                        if (data) {
                            var myObject = JSON.stringify(data);
                            var count = Object.keys(myObject).length;
                            console.log('object has a length of ' + count);

                            triangleCoords = [];
                            for (var i = 0; i < data.length; i++) {
                                item = {}
                                item["latitude"] = data[i][1].toString();
                                item["longitude"] = data[i][0].toString();
                                AreaLatlong.push(item);
                                triangleCoords.push(new google.maps.LatLng(data[i][1], data[i][0]));
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
                            if (count > 50000) {
                                alert("This area can't be draw. Please create manually.");
                            } else {
                                polygon.setMap(map);
                            }
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
        var centerControlDiv = document.createElement('div');
        var centerControl = new CenterControl(centerControlDiv, map);
        centerControlDiv.index = 1;
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);

        google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
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
            google.maps.event.addListener(polygon.getPath(), "insert_at", function () {
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
            google.maps.event.addListener(polygon.getPath(), "set_at", function () {
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

        google.maps.event.addListener(drawingManager, "drawingmode_changed", function () {
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
    function getEventTarget(e) {
        e = e || window.event;
        return e.target || e.srcElement;
    }
    function openStreetMap() {
        var query = $('#google_area').val();
        var url = "https://nominatim.openstreetmap.org/search.php?polygon_geojson=1&format=json&q=" + query;
        $.getJSON(url, function (result) {
            var arrayLength = result.length;
            $('.list-gpfrm').empty();
            for (var i = 0; i < arrayLength; i++) {
                var myhtml = "<li value=" + result[i].place_id + ">" + result[i].display_name + "</li>";
                $(".list-gpfrm").append(myhtml);
            }
        });
    }

    function changeCanter(s) {
        var country = s[s.selectedIndex].id;
        if (country != "") {
            var geocoder;
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'address': country }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    //alert(results[0].geometry.location);
                    map.setZoom(6);
                    map.setCenter(results[0].geometry.location)
                }
            });
        }
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

        // Setup the click event listeners: simply set the map to Chicago.
        controlUI.addEventListener('click', function () {
            document.getElementById('lat').value = "";
            for (var i = 0; i < polygonArray.length; i++) {
                polygonArray[i].setMap(null);
            }
            polygonArray = [];
            AreaLatlong = [];
        });

    }

    initMap();

    $(function () {
        $("#pool_enable").click(function () {
            if ($(this).is(":checked")) {
                $("#pool_postion").show();
            } else {
                $("#pool_postion").hide();
            }
        });
    });

    function changeId(that) {
        var val = that.value;
        if (val == 1) {
            $('#deliveryarea').removeClass('hidden');
        } else {
            $('#deliveryarea').addClass('hidden');
        }
    }
    $(document).ready(function () {
        $('form#areaForm').submit(function () {
            $(this).find(':input[type=submit]').prop('disabled', true);
        });
    });
</script>
@endsection