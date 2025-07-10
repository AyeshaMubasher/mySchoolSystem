<?php

define('UPLOAD_DIR', 'uploads/');

$host = "localhost";
$user = "root";
$password = "";
$databas = "myschooldb";

$conn = new mysqli($host, $user, $password, $databas);

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

?>