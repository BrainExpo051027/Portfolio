<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "library"; // your actual database name

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection Error: " . mysqli_connect_error());
}
?>
