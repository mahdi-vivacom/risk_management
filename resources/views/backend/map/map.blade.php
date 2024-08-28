@extends( 'backend.layout.master' )
@section( 'content' )
    <section id="gmaps-utils" class="section">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <!-- <div class="card-header">
                        <select onchange="getDriverLocationo(this.value)"
                                class="c-select form-control"
                                id="driver_marker"
                                name="driver_marker">
                            <option value="1">@lang('admin.message387')</option>
                            <option value="2">@lang('admin.message388')</option>
                            <option value="3">@lang('admin.message389')</option>
                            <option value="4">@lang('admin.message390')</option>
                            <option value="5">@lang('admin.message391')</option>
                            <option value="6">@lang('admin.message392')</option>
                        </select>
                    </div> -->
                    <div class="card-body container-fluid">
                        <div id="context-menu" style="width: 100%;height: 550px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://maps.googleapis.com/maps/api/js?key={{$config->google_key}}&libraries=visualization"></script>
@endsection
@section( 'custom_js' )
    <script>
        let map;
        let markers = [];
        let marker;
        let markerslocations;
        let infowindow;

        function initialize() {
            map = new google.maps.Map(document.getElementById('context-menu'), {
                zoom: 2,
                center: {lat: 8.7832, lng: 34.5085},
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            getDriverLocationo(1);
            // directionsDisplay.setMap(map);
        }

        function getDriverLocationo(type) {
            var token = $('[name="_token"]').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': token
                },
                method: 'POST',
                url: "{{ route ( 'map' ) }}",
                data: {
                    type: type,
                },
                success: function (data) {
                    markerslocations = JSON.parse(data);
                    infowindow = new google.maps.InfoWindow();
                    for (var f = 0; f < markers.length; f++) {
                        markers[f].setMap(null);
                    }
                    for (var i = 0; i < markerslocations.length; i++) {
                        newName = markerslocations[i]['marker_name'];
                        marker_number = markerslocations[i]['marker_number'];
                        icon = markerslocations[i]['marker_icon'];
                        marker_image = markerslocations[i]['marker_image'];
                        email = markerslocations[i]['marker_email'];
                        newLatitude = markerslocations[i]['marker_latitude'];
                        newLongitude = markerslocations[i]['marker_longitude'];
                        markerlatlng = new google.maps.LatLng(newLatitude, newLongitude);
                        content = '<table><tr><td rowspan="4"><img src="' + marker_image + '" height="60" width="60"></td></tr><tr><td>&nbsp;&nbsp;Email: </td><td><b>' + email + '</b></td></tr><tr><td>&nbsp;&nbsp;Mobile: </td><td><b>' + marker_number + '</b></td></tr></table>';
                        var marker = new google.maps.Marker({
                            map: map,
                            title: newName,
                            position: markerlatlng,
                            icon: icon
                        });
                        google.maps.event.addListener(marker, 'click', (function (marker, content, infowindow) {
                            return function () {
                                infowindow.setContent(content);
                                infowindow.open(map, marker);
                                map.panTo(this.getPosition());
                                //map.setZoom(21);
                            };
                        })(marker, content, infowindow));
                        markers.push(marker);
                    }
                }, error: function (e) {
                    console.log(e);
                }

            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);
        // initialize();
        //google.maps.event.addDomListener(window, 'load', initialize);

    </script>
@endsection
