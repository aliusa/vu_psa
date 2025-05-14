document.addEventListener('DOMContentLoaded', function () {
    function initLeaflet() {
        document.querySelectorAll('.leaflet-map').forEach(function (mapEl) {
            let init = false;

            function initMap() {
                setTimeout(function () {
                    if (!init) {
                        init = true;
                        let inputEl = document.querySelector('.leaflet-value');
                        let coordinates = inputEl ? inputEl.value : '';

                        let options;
                        if (coordinates !== '') {
                            let parts = coordinates.split(',');

                            if (parts.length === 3) {
                                options = {
                                    center: [parts[0], parts[1]],
                                    zoom: parseInt(parts[2], 10),
                                };
                            } else if (parts.length === 2) {
                                options = {
                                    center: [parts[0], parts[1]],
                                    zoom: 12,
                                };
                            }
                        }

                        if (!options) {
                            options = {
                                center: [55.329905, 23.905512],
                                zoom: 7,
                            };
                        }

                        const map = L.map(mapEl, options);
                        L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png").addTo(map);

                        const customMarker = L.icon({
                            iconUrl: '/public/vendor/leaflet/dist/images/marker-icon.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                        });

                        const eventMarker = L.marker(options.center, { icon: customMarker }).addTo(map);

                        map.on('click', function (e) {
                            const lat = e.latlng.lat.toFixed(6);
                            const lng = e.latlng.lng.toFixed(6);
                            const zoom = map.getZoom();
                            eventMarker.setLatLng([lat, lng]);

                            if (inputEl) {
                                inputEl.value = `${lat},${lng},${zoom}`;
                            }
                        });
                    }








                }, 1000);
            }

            initMap();
        });

        // Preview map
        const previewEl = document.querySelector('.leaflet-map-preview');
        if (previewEl) {
            let coords = previewEl.getAttribute('data-coordinates');
            if (coords) {
                let parts = coords.split(',');
                if (parts.length === 3) {
                    let options = {
                        center: [parts[0], parts[1]],
                        zoom: parseInt(parts[2], 10),
                    };

                    const map = L.map(previewEl, options);
                    L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png").addTo(map);

                    const customMarker = L.icon({
                        iconUrl: '/public/vendor/leaflet/dist/images/marker-icon.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                    });

                    L.marker(options.center, { icon: customMarker }).addTo(map);
                }
            }
        }

        const allCoords = document.querySelector('.leaflet-map-all');
        if (allCoords) {
            let coordsAll = allCoords.getAttribute('data-coordinates');
            let coords = coordsAll.split(';');

            let options = {
                center: [55.329905, 23.905512],
                zoom: 7,
            };

            const map = L.map(allCoords, options);
            L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png").addTo(map);


            coords.forEach(function (element, index, array) {
                console.log(element);
                //array.length = index + 1;// Behaves like `break`

                const customMarker = L.icon({
                    iconUrl: '/public/vendor/leaflet/dist/images/marker-icon.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                });
                let parts = element.split(',');

                L.marker([parts[0], parts[1]], { icon: customMarker }).addTo(map);
            });
        }

    }

    initLeaflet();
});
