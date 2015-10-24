<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Hey Internet Explorer: Fuck you. Sincerely, everyone. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tattooliste.de</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap-theme.min.css">
    <style>
        body {
        }
        #map {
            width: 100%;
            height: 400px;
        }
        .welcome {
            height: 400px;
            background-color: #CCCCCC;
            padding: 40px 15px;
            text-align: center;
        }
        .hovercraft {
            max-width: 550px;
            margin-left: auto;
            margin-right: auto;
            padding: 15px;
            position: relative;
            bottom: 125px;
        }
        .jumbotron {
            background-color: #fafafa;
        }
    </style>
    <script src="./bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container">
        <div id="navbar-header" class="collapse navbar-collapse">
            <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-text-background"></span></a>
            <a class="navbar-brand" href="#">tattoliste.de</a>
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Karte</a></li>
                <li><a href="#">Bruh</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="container">
            <form class="form-horizontal">
                <fieldset>
                    <legend>Ein neues Studio registrieren:</legend>
                    <div class="form-group">
                        <label for="Name" class="col-lg-2 control-label">Name</label>
                        <div class="col-lg-10">
                            <input class="form-control" id="Name" placeholder="Studioname" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Ort" class="col-lg-2 control-label">Plz Ort</label>
                        <div class="col-lg-10">
                            <input class="form-control" id="Ort" placeholder="Postleitzahl Ort" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Straße" class="col-lg-2 control-label">Anschrift (Str., Hausnummer)</label>
                        <div class="col-lg-10">
                            <input class="form-control" id="Straße" placeholder="Stra&szlig;e Hausnummer" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Submitter" class="col-lg-2 control-label">Eingetragen als</label>
                        <div class="col-lg-10">
                            <select class="form-control" id="select">
                                <option>Studio Inhaber</option>
                                <option>Gast</option>
                                <option>Anonym</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </form>
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