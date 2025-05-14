$(document).ready(function () {
    function initLeaflet() {
        $('.leaflet-map').each(function (index, object1) {

            var init = false;
            initMap();

            function initMap() {
                setTimeout(function () {
                    if (!init) {
                        init = true;
                        var coordinates = $('.leaflet-value').val();
                        if (coordinates !== undefined) {
                            coordinates = coordinates.split(',');

                            if (coordinates.length === 3) {
                                //long/lat/zoom
                                var options = {
                                    center: [coordinates[0], coordinates[1]],
                                    zoom: coordinates[2],
                                }

                                var map = L.map(object1, options);
                            } else if (coordinates.length === 2) {
                                var options = {
                                    center: [coordinates[0], coordinates[1]],
                                    zoom: 12,
                                }

                                var map = L.map(object1, options);
                            }
                        } else {
                            var options = {
                                center: [55.329905, 23.905512],
                                zoom: 7,
                            }
                            var map = L.map(object1, options);
                        }
                        L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png").addTo(map);
                        var customMarker = L.icon({
                            iconUrl: '/public/vendor/leaflet/dist/images/marker-icon.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                        });
                        eventMarker = L.marker(options.center, {icon: customMarker}).addTo(map);

                        map.on('click', function (e) {
                            var coord = e.latlng.toString().split(',');
                            var lat = coord[0].split('(');
                            var lng = coord[1].split(')');
                            var zoom = map.getZoom();
                            var newLatLng = new L.LatLng(lat[1], lng[0]);
                            eventMarker.setLatLng(newLatLng);
                            $('.leaflet-value').val(lat[1] + ',' + lng[0] + ',' + zoom);
                        });
                    }

                }, 1000);
            }
        });


        //preview
        if ($('.leaflet-map-preview').length) {
            var object = $('.leaflet-map-preview')[0];
            var coordinates = $('.leaflet-map-preview').attr('data-coordinates');

            coordinates = coordinates.split(',');

            if (coordinates.length === 3) {
                //long/lat/zoom
                var options = {
                    center: [coordinates[0], coordinates[1]],
                    zoom: coordinates[2],
                }

                var map = L.map(object, options);


                L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png").addTo(map);
                var customMarker = L.icon({
                    iconUrl: '/public/vendor/leaflet/dist/images/marker-icon.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                });
                eventMarker = L.marker(options.center, {icon: customMarker}).addTo(map);
            }
        }
    }

    initLeaflet();
});
