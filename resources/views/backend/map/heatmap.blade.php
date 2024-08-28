@extends( 'backend.layout.master' )
@section( 'content' )
    <section class="section">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <!-- <div class="card-header">
                        <button type="button" class="btn btn-outline-primary btn-min-width box-shadow-1 mr-1 mb-1 float-right"
                                onclick="changeGradient()">@lang( 'admin.change_gradient' )
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-min-width box-shadow-1 mr-1 mb-1 float-right"
                                onclick="changeRadius()">@lang( 'admin.change_radius' )
                        </button>
                    </div> -->
                    <div class="card-body container-fluid">
                        <div id="context-menu" style="width: 100%;height: 550px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section( 'custom_js' )
    <script>

        var map, heatmap;

        function initMap() {
            map = new google.maps.Map(document.getElementById('context-menu'), {
                zoom: 2,
                center: {lat:28.7041, lng: 77.1025},
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            heatmap = new google.maps.visualization.HeatmapLayer({
                data: getPoints(),
                map: map
            });
        }

        function toggleHeatmap() {
            heatmap.setMap(heatmap.getMap() ? null : map);
        }

        function changeGradient() {
            var gradient = [
                'rgba(0, 255, 255, 0)',
                'rgba(0, 255, 255, 1)',
                'rgba(0, 191, 255, 1)',
                'rgba(0, 127, 255, 1)',
                'rgba(0, 63, 255, 1)',
                'rgba(0, 0, 255, 1)',
                'rgba(0, 0, 223, 1)',
                'rgba(0, 0, 191, 1)',
                'rgba(0, 0, 159, 1)',
                'rgba(0, 0, 127, 1)',
                'rgba(63, 0, 91, 1)',
                'rgba(127, 0, 63, 1)',
                'rgba(191, 0, 31, 1)',
                'rgba(255, 0, 0, 1)'
            ]
            heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);
        }

        function changeRadius() {
            heatmap.set('radius', heatmap.get('radius') ? null : 20);
        }

        function changeOpacity() {
            heatmap.set('opacity', heatmap.get('opacity') ? null : 0.2);
        }

        function getPoints() {
            return [
                @foreach( $bookings as $booking )
                new google.maps.LatLng({{$booking->latitude}},{{$booking->longitude}}),
                @endforeach
            ];
        }
    </script>
    {{--    <script async defer--}}
    {{--            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDkKetQwosod2SZ7ZGCpxuJdxY3kxo5Po&libraries=visualization&callback=initMap">--}}
    {{--    </script>--}}
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{$config->google_key}}&libraries=visualization&callback=initMap">
    </script>
@endsection
