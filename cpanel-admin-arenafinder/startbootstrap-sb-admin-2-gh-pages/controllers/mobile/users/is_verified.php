<?php

require "../../koneksi.php";
require "Validator.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $email = $_GET['email'];

    $sql = "SELECT is_verified FROM users WHERE email = '$email'";
    $result = $conn->query($sql)->fetch_assoc();
    $status = $result["is_verified"];

    if($result["is_verified"] == 1){
        $response = array("status" => "success", "message" => "Akun terverifikasi");
    }else{
        $response = array("status" => "error", "message" => "Akun tidak terverifikasi");
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "not get method");
}

// show response
echo json_encode($response);
