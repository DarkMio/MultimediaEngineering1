<?php

$servername = "localhost";
$username = "mio";
$password = "";
$dbname = "tattooliste";
$conn = new mysqli($servername, $username, $password, $dbname);

list($path, $qs) = explode("?", $_SERVER["REQUEST_URI"], 2);
parse_str($qs, $output);

if (array_key_exists("offset", $output)) {
    $sql_query = "SELECT * FROM locations LIMIT 100 OFFSET ?";
    $sql = $conn->prepare($sql_query);
    $sql->bind_param('i', $output["offset"]);
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

?>