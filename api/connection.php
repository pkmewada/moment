<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "moments_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    $conn = null;
} else {
    $conn->set_charset("utf8mb4");
}

?>