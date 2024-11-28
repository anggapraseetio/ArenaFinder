<?php

/**
 * Digunakan untuk login manual dengan menginputkan username/email dan password.
 */

require "../../koneksi.php";
require "Validator.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $tokenOld = $_POST['token_old'];
    $tokenNew = $_POST['token_new'];

    $sql = "UPDATE session 
        SET device_token = '$tokenNew' 
        WHERE email = '$email' 
        AND device_token = '$tokenOld'
    ";

    $result = $coon->query($sql);

    if($result){
        $response = array("status" => "success", "message" => "device token diupdate");
    }else{
        $response = array("status" => "error", "message" => "device token gagal diupdate");
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "not post method");
}

// show response
echo json_encode($response);

// https://firebase.google.com/docs/android/setup