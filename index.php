<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Hey Internet Explorer: Fuck you. Sincerely, everyone. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tattoofrei.de</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap-theme.min.css">
    <style>
        body {
        }
        .welcome {
            height: 400px;
            background-color: #CCCCCC;
            padding: 40px 15px;
            text-align: center;
        }
        .hovercraft {
            max-width: 750px;
            margin-left: auto;
            margin-right: auto;
            padding: 15px;
            background-color: white;
        }
        .jumbotron {
            background-color: #fafafa;
        }
    </style>
    <script src="./bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
    <header class="navbar">
        <div class="container">
            <div id="navbar" class="collapse navbar-collapse">
                <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-text-background"></span></a>
                <a class="navbar-brand" href="#">tattoliste.de</a>
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Karte</a></li>
                    <li><a href="#">Bruh</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="container-fluid">
        <div class="row">
            <div class="welcome">
                <h1>Super coole GMap</h1>
                <div class="hovercraft">
                    <div class="row">
                        <div class="col-lg-6">
                            Lorem
                        </div>
                        <div class="col-lg-6">
                            Ipsum
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <br />
                <div class="jumbotron">
                   <h1 class="center-block">Gefunded Studios</h1>
                    <br />
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
    <footer class="panel-footer">
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-inline">
                    <li><a href="#">Kontakt</a></li>
                    <li><a href="#">Werte</a></li>
                    <li><a href="#">Impressum</a></li>
                </ul>
                <p>From Russia with love. This is also a footer.
                <span class="label label-success">
                    &pi; <?php echo round((microtime(TRUE)-$_SERVER['REQUEST_TIME_FLOAT']), 4); ?>
                </span>
                </p>
            </div>
        </div>
    </footer>
</body>
</html>