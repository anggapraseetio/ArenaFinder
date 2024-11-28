<?php
require "../../../koneksi.php";

header("Content-Type: application/json");



if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $rawData = file_get_contents("php://input");
    $response = array();

    $data = json_decode($rawData, true);

    if (isset($data['id_aktivitas'])) {
        $idAktivitas = $data['id_aktivitas'];
    }else {
        $idAktivitas = '0';
    }

    if (isset($data['email'])) {
        $email = $data['email'];
    }else {
        $email = '-';
    }

    $sql = "SELECT email FROM venue_aktivitas_member 
        WHERE id_aktivitas = $idAktivitas AND email = '$email' 
        LIMIT 1
    ;";

    if ($conn->query($sql)->num_rows >= 1) {
        $response = array("status" => "success", "message" => "Anda sudah bergabung dalam aktivitas");
    } else {
        $sql = "INSERT INTO venue_aktivitas_member (id_aktivitas, email) 
        VALUES ($idAktivitas, '$email')
    ";

        if ($conn->query($sql) === true) {
            $response = array("status" => "success", "message" => "Berhasil bergabung dalam aktivitas");
        } else {
            $response = array("status" => "error", "message" => "Gagal bergabung dalam aktivitas");
        }
    }
} else {
    $response = array("status" => "error", "message" => "not put method");
}

echo json_encode($response);
