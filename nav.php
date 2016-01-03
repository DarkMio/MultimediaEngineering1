<nav class="navbar navbar-default">
    <div class="container">
        <div id="navbar-header" class="collapse navbar-collapse">
            <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-text-background"></span></a>
            <a class="navbar-brand" href="#">tattoliste.de</a>
            <?php Navigation() ?>
        </div>
    </div>
</nav>

<?php function Navigation(){
    $base = basename($_SERVER['PHP_SELF']);
    $rows = file('database.txt');                                   // will be in a database - later.
    echo '<ul class="nav navbar-nav">';
    foreach($rows as $row) {
        $nav = explode(":", $row);                                  // data set now ['name', 'target']
        $page = trim($nav[0]);                                      // get rid of whitespaces before and after
        $link = trim($nav[1]);                                      // same here
        if($link == $base) {                                        // compare link with base api_url
            echo '<li class="active"><a>' . $page . '</a></li>';    // concat - kek, with a dot...
        } else {
            echo '<li><a href="' . $link . '">' . $page . '</a></li>';
        }
    }
    echo '</ul>';
}?>