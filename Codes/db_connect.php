<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "dairy_farm";

// Create connection
$con = new mysqli($host, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}
?>
