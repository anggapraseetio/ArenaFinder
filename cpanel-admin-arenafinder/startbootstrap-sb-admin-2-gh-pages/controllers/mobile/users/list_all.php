<?php

/**
 * Digunakan untuk login manual dengan menginputkan username/email dan password.
 */

require "../../koneksi.php";
require "Validator.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $query = $_GET['query'];

    $sql = $query;
    $result = $conn->query($sql);

    if($result->num_rows >= 1){
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $response = array("status" => "success", "message" => "data kosong", "data" => $data);
    }else{
        $response = array("status" => "error", "message" => "data kosong");
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "not post method");
}

// show response
echo json_encode($response);

// https://firebase.google.com/docs/android/setup