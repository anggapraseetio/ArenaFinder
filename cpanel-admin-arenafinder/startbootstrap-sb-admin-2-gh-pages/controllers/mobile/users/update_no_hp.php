<?php

/**
 * Digunakan untuk mengupdate photo profile user.
 */

require "../../koneksi.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // post request
    $sql = "UPDATE users set no_hp = '082233671927' WHERE username = 'alkautsar'";
    $result = $conn->query($sql);

    if($result){
        $response = array("status" => "success");
    }else{
        $response = array("status" => "error");
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "method is not post");
}

// show response
echo json_encode($response);
