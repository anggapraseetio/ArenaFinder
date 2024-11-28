<?php

/**
 * Digunakan untuk mengupdate photo profile user.
 */

require "../../koneksi.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $rawData = file_get_contents("php://input");
    $response = array();

    $data = json_decode($rawData, true);

    if (isset($data['email'])) {
        $email = $data['email'];
    } else {
        $email = '';
    }

    if (isset($data['device_token'])) {
        $deviceToken = $data['device_token'];
    } else {
        $deviceToken = '';
    }

    // cek email exist atau tidak
    $sql = "SELECT email FROM session 
        WHERE email = '$email' AND device_token = '$deviceToken' 
        LIMIT 1
    ";
    if ($conn->query($sql)->num_rows == 1) {
        $sql = "DELETE FROM session WHERE email = '$email' AND device_token = '$deviceToken'";
        $conn->query($sql);
        $response = array("status" => "success", "message" => "logout berhasil");
    } else {
        $response = array("status" => "error", "message" => "Email belum login");
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "method is not post");
}

// show response
echo json_encode($response);
