<script>
    //  
    // DOCUMENTATION
    // https://leafletjs.com/reference.html
    //

    console.log(<?php echo $javascriptCityLat; ?>);
    console.log(<?php echo $javascriptCityLat; ?> + 0.2);
    var lats1 = <?php echo $javascriptLats; ?>;
    var lons1 = <?php echo $javascriptLons; ?>;

    var southWest = L.latLng(<?php echo $javascriptCityLat; ?> - 0.2, <?php echo $javascriptCityLon; ?> - 0.2),
        northEast = L.latLng(<?php echo $javascriptCityLat; ?> + 0.2, <?php echo $javascriptCityLon; ?> + 0.2),
        bounds = L.latLngBounds(southWest, northEast);
    console.log(bounds.northEast);
    // set view to city coords
    var map = L.map('map-<?php echo $cityID ?>', {
        // maxBounds: bounds,
        // center: [51.505, -0.09],
        zoomControl: false,
        maxZoom: 15,
        minZoom: 11,
        scrollWheelZoom: false,
        doubleClickZoom: false,
        touchZoom: false
        // dragging: false
        // trackResize: true
    // }).setView([51.505, -0.09]);
    }).setView([lats1[1], lons1[2], 4]);

    map.fitBounds(bounds);
    var tiles = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 20,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1
    }).addTo(map);

    function truncate(str, n) {
        return (str.length > n) ? str.substr(0, n - 1) + '&hellip;' : str;
    };

    var poiIDs = <?php echo $javascriptPOIIDs; ?>;
    var names = <?php echo $javascriptNames; ?>;
    var descriptions = <?php echo $javascriptDescriptions; ?>;

    var lats = <?php echo $javascriptLats; ?>;
    var lons = <?php echo $javascriptLons; ?>;

    for (i = 0; i < lats.length; i++) {

        var icon = L.icon({
            iconUrl: 'marker.png',
            shadowUrl: 'leaf-shadow.png',

            iconSize: [17, 20], // size of the icon
            shadowSize: [50, 64], // size of the shadow
            iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
            shadowAnchor: [4, 62], // the same for the shadow
            popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
        });
        //console.log(lats[i]);
        var marker = L.marker([lats[i], lons[i]], {
            icon: icon
        }).addTo(map);

        // Assign the marker a POI name
        marker.poiID = poiIDs[i];

        marker.bindPopup("<h3>" + names[i] + "</h3><p>" + truncate(descriptions[i], 150) + "</p>");

        marker.on('mouseover', function(e) {
            this.openPopup();
        });

        marker.on('mouseout', function(e) {
            this.closePopup();
        });

        marker.on('click', function(e) {
            var currentPOI = e.target.poiID;
            // Comment out window.open when using single page functionality
            window.open('poi.php?poi=' + currentPOI, '_blank');
            // Uncomment these next two lines to enable single page functionality
            //$("#poi-info").load('poi.php?poi=' + currentPOI);
            //document.getElementById('poi-info').scrollIntoView({behavior: "smooth", block: "start", inline: "nearest"});
        });
    }
</script>