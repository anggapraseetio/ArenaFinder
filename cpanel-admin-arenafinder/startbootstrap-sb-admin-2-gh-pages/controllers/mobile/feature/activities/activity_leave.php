<?php
require "../../../koneksi.php";

header("Content-Type: application/json");



if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $rawData = file_get_contents("php://input");
    $response = array();

    $data = json_decode($rawData, true);

    if (isset($data['id_aktivitas'])) {
        $idAktivitas = $data['id_aktivitas'];
    } else {
        $idAktivitas = '0';
    }

    if (isset($data['email'])) {
        $email = $data['email'];
    } else {
        $email = '-';
    }

    $sql = "DELETE FROM venue_aktivitas_member 
        WHERE id_aktivitas = $idAktivitas AND email = '$email'
        ";

    if ($conn->query($sql) === true) {
        $response = array("status" => "success", "message" => "Berhasil keluar dalam aktivitas");
    } else {
        $response = array("status" => "error", "message" => "Gagal keluar dalam aktivitas");
    }
} else {
    $response = array("status" => "error", "message" => "not delete method");
}

echo json_encode($response);
