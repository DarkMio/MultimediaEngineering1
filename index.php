<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Tattoostudio</title>
    <link rel="stylesheet" href="static/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
<?php include "header.php" ?>
<div class="container-fluid">
    <div class="row">
        <div id="map_wrapper" style="display: block;">
            <div id="map"></div>
        </div>
        <div class="hovercraft">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="studioSearchbox" placeholder="Adresse suchen">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="container">
            <br />
            <h1 class="center-block">Gefunded Studios</h1>
            <br />
            <table class="table table-striped table-hover" id="tattoo-table">
                <thead>
                <tr>
                    <th>Plz</th>
                    <th>Name</th>
                    <th>Hausnummer</th>
                    <th>Ort</th>
                    <th>Bewertung</th>
                </tr>
                </thead>
                <tbody id="resultsBody">
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include "footer.php" ?>
</body>
<script src="./static/js/jquery.raty.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initialize" async defer></script>
<script>
    $.fn.raty.defaults.path = './static/images';
    $.fn.raty.defaults.half = true;
    $('#rating-2').raty({score: 5.});
    $('#rating-1').raty({score: 3.25});
    function initialize() {
        var mapCanvas = document.getElementById('map');
        var mapOptions = {
            center: new google.maps.LatLng(52.52001, 13.40495),
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(mapCanvas, mapOptions);
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                map.setCenter(initialLocation);
            });
        }
        var input = document.getElementById('studioSearchbox');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function(evt) {

            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // Clear out the old markers.
            markers.forEach(function(marker) {
                marker.setMap(null);
            });
            markers = [];
            // dispatch the request and get the studios.
            var mainLocation = searchBox.getPlaces()[0].geometry.location;
            findStudios(mainLocation.lng(), mainLocation.lat(), 50, function(response) {
                $('#resultsBody').empty();
                if(response['status'] == 200) {
                    var table = $("#tattoo-table").find("tbody:last");
                    $.each(response["response"], function(key, value) {
                        table.append(
                            "<tr>" +
                            "<td>" + value["zip"] + "</td>" +
                            "<td>" + value["studio_name"] + "</td>" +
                            "<td>" + value["street_name"] + " " + value["street_number"] + "</td>" +
                            "<td>" + value["location"] + " (" + Number(value["distance"]).toFixed(2) + "km)</td>" +
                            "<td><div id=\"rating-" + value["id"] + "\"></div>"+ "</td>" +
                            "</tr>"
                        );
                        $('#rating-' + value["id"]).raty({score:Math.random() * 5});
                    });
                } else {
                    spawnRibbon('get-studios-fail', 'alert', 'Sorry! Wir konnten unsere Datenbank nicht erreichen.<br>Probier\'s doch später nocheinaml.');
                }
                console.log(response);
            });

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                // Create a marker for each place.
                markers.push(new google.maps.Marker({
                    map: map,
                    icon: icon,
                    title: place.name,
                    position: place.geometry.location
                }));

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
    }
</script>
</html>