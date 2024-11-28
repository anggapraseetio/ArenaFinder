<?php

require "../../../../koneksi.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $rawData = file_get_contents("php://input");
    $response = array();

    $data = json_decode($rawData, true);

    if (isset($data['id_venue'])) {
        $idVenue = $data['id_venue'];
    }else {
        $idVenue = '0';
    }

    if (isset($data['id_users'])) {
        $idUsers = $data['id_users'];
    }else {
        $idUsers = '0';
    }

    $sql = "DELETE FROM venue_review WHERE id_venue = '$idVenue' AND id_users = '$idUsers'";

    if (mysqli_query($conn, $sql) === true) {
        $response = array("status" => "success","message"=> "Ulasan berhasil dihapus");
    }else {
        $response = array("status"=> "error","message"=> "Ulasan gagal dihapus");
    }

}else{
    $response = "no delete method";
}

echo json_encode($response);