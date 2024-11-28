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

    if($result === true){
        $response = array("status" => "success", "message" => "query di eksekusi");
    }else{
        $response = array("status" => "error", "message" => "query gagal di eksekusi");
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "not post method");
}

// show response
echo json_encode($response);

// https://firebase.google.com/docs/android/setup