<?php

/**
 * Digunakan untuk mengirimkan kode otp ke email user
 */

require "../../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idAktivitas = $_POST['id_aktivitas'];
    $date = $_POST['date'];

    $sql = "UPDATE `venue_aktivitas` 
        SET date = '$date' 
        WHERE id_aktivitas = $idAktivitas;
    ";

    $result = $conn->query($sql);

    if($result == true){
        $response = array("status" => "success", "message" => "data diupdate");
    }else{
        $response = array("status" => "error", "message" => "data gagal diupdate");
    }
} else {
    $response = array("status" => "error", "message" => "method bukan post");
}

echo json_encode($response);
