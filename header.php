<script src="./static/js/jquery-1.11.3.min.js"></script>
<script src="static/bootstrap/js/bootstrap.min.js"></script>
<script src="./static/js/request_implementation.js"></script>
<script src="./static/js/js.cookie.js"></script>
<nav class="navbar navbar-default">
    <div class="navbar-inner">
        <div class="container">
            <div id="navbar-header" class="collapse navbar-collapse">
                <a class="navbar-brand" href="./"><span class="glyphicon glyphicon-text-background"></span></a>
                <a class="navbar-brand" href="./">tattoliste.de</a>
                <ul class="nav navbar-nav" id="navbar">
                    <li id="studios-link-list">
                        <a href="./index.php" id="studios-link">Karte</a>
                    </li>
                    <li id="addStudio-link-list">
                        <a href="./addStudio.php" id="addStudio-link">Eintragen</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown" id="menuLogin">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="navLogin">Login</a>
                        <div class="dropdown-menu" style="padding:17px;">
                            <form id="login">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="username" placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" id="password" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-block btn-default">Anmelden</button>
                                </div>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<script>
    $(function () {
        function setActive(element) {
            $('#' + element + '-link-list').addClass('active');
            $('#' + element + '-link').attr('href', '#');
        }
        $("#login").submit(function (e) {
            e.preventDefault();
            login($("#username").val(), $("#password").val(), function (bool) {
                if (bool) {
                    spawnRibbon('login-success', 'success', '<strong>Success!</strong> You are now logged in for 24 hours.');
                } else {
                    spawnRibbon('login-failure', 'danger', '<strong>Failure!</strong> Your username or password is incorrect.');
                }
            })
        });

        {
            if(hasLogin()) {
                $('#navbar').append(
                    '<li id="staged-link-list"><a href="viewStaged.php" id="staged-link">Staged Studios</a></li>'
                )
            }
        }

        {   // hackery to make active elements
            var path = window.location.pathname.split('/');
            var file = path[path.length - 1];
            if (file == 'index.php' || file == '') {
                setActive('studios');
            } else if (file == 'addStudio.php') {
                setActive('addStudio');
            } else if (file == 'viewStaged.php') {
                setActive('staged');
            }
        }

    })
</script>