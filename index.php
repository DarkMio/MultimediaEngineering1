<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Hey Internet Explorer: Fuck you. Sincerely, everyone. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tattoofrei.de</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <style>
        body {
        }
        .welcome {
            height: 450px;
            background-color: #CCCCCC;
            padding: 40px 15px;
            text-align: center;
        }
        .container-maxwidth {
            width: 100%;
            padding-left: 0;
            padding-right: 0;
        }
    </style>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
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
                <h1>Hier könnte meine Map sein.</h1>
                <div class="hovercraft">
                    <div class="row">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="container">
            <h3>From russian with love. This is also a footer.</h3>
            <span class="label label-success">
                &pi; <?php echo $total_time = round((microtime(TRUE)-$_SERVER['REQUEST_TIME_FLOAT']), 4); ?>
            </span>
        </div>
    </footer>
</body>
</html>