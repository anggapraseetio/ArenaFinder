<?php

/**
 * Digunakan untuk mengupdate password dari users.
 */

require "../../koneksi.php";
require "Validator.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // get data user
    $sql = "UPDATE users SET level = 'ADMIN' WHERE email = 'e41222905@student.polije.ac.id'";
    $result = $conn->query($sql);

    // jika password berhasil terupdate
    if ($result == true) {
        $response = array("status" => "success", "message" => "data berhasil diupdate");
    } else {
        $response = array("status" => "error", "message" => "data gagal diupdate");
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "method is not post");
}

// show response
echo json_encode($response);