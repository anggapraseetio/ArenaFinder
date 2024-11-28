<?php

/**
 * Digunakan untuk mengupdate photo profile user.
 */

require "../../koneksi.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // cek email exist atau tidak
    $sql = "DELETE FROM venue_booking where email = 'hakiahmad756@gmail.com'";
    $result = $conn->query($sql);

    if($result){
        $response = array("status" => "success", "message" => "data berhasil dihapus");
    }else{
        $response = array("status" => "error", "message" => "data gagal dihapus");
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "method is not post");
}

// show response
echo json_encode($response);
