<?php

$server = "localhost"; 
$username = "root";
$password = "";
$database = "arenafinder";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_errno) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

return $conn;
?>