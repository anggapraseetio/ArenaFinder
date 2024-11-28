<?php

require "../../../../koneksi.php";

function fetchMyComment($conn, $idVenue, $idUser)
{
    $sql = "SELECT r.id_review, u.username, u.full_name, u.user_photo, 
        r.rating, r.date, r.comment
        FROM venue_review AS r
        JOIN users AS u 
        ON r.id_users = u.id_users
        WHERE u.id_users = $idUser AND r.id_venue = $idVenue
    ";

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_assoc();
        if (!empty($data)) {
            return array("status" => "success", "message" => "Data berhasil didapatkan", "data" => $data);
        } else {
            return array("status" => "error", "message" => "Data tidak ditemukan");
        }
    } else {
        return array("status" => "error", "message" => "Perintah gagal dijalankan" . $conn->error);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $idUser = $_GET['id_user'];
    $idVenue = $_GET['id_venue'];

    $myComment = fetchMyComment($conn, $idVenue, $idUser);

    if ($myComment["status"] == "error") {
        $myComment = array("data" => ["id_review" => "0"]);
    }
    $data = array(
        "my_comment" => $myComment["data"],
    );

    // response sukses
    $response = array("status" => "success", "message" => "Data venue sukses didapatkan", "data" => $data);
} else {
    $response = array("status" => "error", "message" => "not put method");
}

echo json_encode($response);
