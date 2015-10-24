<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Hey Internet Explorer: Fuck you. Sincerely, everyone. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tattooliste.de</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script>
        function initialize() {
            var mapCanvas = document.getElementById('map');
            var mapOptions = {
                center: new google.maps.LatLng(52.52001, 13.40495),
                zoom: 12,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(mapCanvas, mapOptions)
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
</head>
<body>
<?php $context = 'foo'; include 'nav.php';?>
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
                            <span class="input-group-addon">Adresse</span>
                            <input type="text" class="form-control" id="inputDefault">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <br />
            <div class="jumbotron">
               <h1 class="center-block">Gefunded Studios</h1>
                <br />
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt
                    ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo
                    dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor
                    sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod
                    tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                    At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren,
                    no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet,
                    consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore
                    magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea
                    rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Plz</th>
                        <th>Name</th>
                        <th>Hausnummer</th>
                        <th>Ort</th>
                        <th>Bewertung</th>
                    </tr>
                    </thead>
                    <tr>
                        <td>12103</td>
                        <td>Mio Moto</td>
                        <td>Blumenthalstr. 15</td>
                        <td>Berlin-Tempelhof</td>
                        <td>8/8 m8</td>
                    </tr>
                    <tr>
                        <td>16784</td>
                        <td>Testdata</td>
                        <td>67 a</td>
                        <td>Berlin</td>
                        <td>4/8</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
</body>
</html>