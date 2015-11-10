<?php
session_start();
if(!isset($_SESSION["role"]) and isset($_COOKIE["role"])) {
    echo "A returning user - setting you up...";
    $_SESSION["role"] = $_COOKIE["role"];
}
if(!array_key_exists("role", $_SESSION) or $_SESSION["role"] == "anonymous") {
    if(!isset($_GET["password"]) and !isset($_GET["username"])) {
        echo "Please login... No security measurements active, do not use real passwords.";
    } else {
        echo "Setting cookie";
        setcookie("role", "administration");
    }
}
if (isset($_SESSION["role"])) {
    $role = $_SESSION["role"];
} else {
    $role = "anonymous";
}

echo "You are " . $role . ". Congratulations.";



