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
</head>
<?php include 'nav.php';?>
<div class="container-fluid">
    <div class="row">
        <div class="container">
            <br />
            <div class="jumbotron">
                <h1 class="center-block">Gefunded Studios</h1>
                <br />
                <ul class="pager">
                    <li><a href="locations.php?offset=<?php echo offset_minus(); ?>">Last 100</a></li>
                    <li><a href="locations.php?offset=<?php echo offset_plus(); ?>">Next 100</a></li>
                </ul>
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Plz</th>
                        <th>Ort</th>
                    </tr>
                    </thead>
                    <?php
                    if(isset($_GET['offset'])) {
                        location($_GET['offset']);
                    } else {
                        location("");
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>

<?php function location($offset) {
    $servername = "localhost";
    $username = "mio";
    $password = "";
    $dbname = "tattooliste";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");

    if ($offset !== '') {
        $sql_query = "SELECT * FROM locations LIMIT 100 OFFSET ?";
        $sql = $conn->prepare($sql_query);
        $sql->bind_param('i', $offset);;
    } else {
        $sql_query = "SELECT * FROM locations LIMIT 100";
        $sql = $conn->prepare($sql_query);
    }

    $sql->execute();
    $result = $sql->get_result();

    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row["id"] . "</td><td>" .$row["zip_code"] . "</td><td>" . $row["location_name"] . "</td></tr>";
    }

    mysqli_close($conn);
}

function offset_plus() {
    if(isset($_GET['offset'])) {
        return $_GET['offset'] + 100;
    } else {
        return "100";
    }
}

function offset_minus() {
    if(isset($_GET['offset']) && $_GET['offset'] != '0') {
        return $_GET['offset'] - 100;
    } else {
        return "0";
    }
}
?>
