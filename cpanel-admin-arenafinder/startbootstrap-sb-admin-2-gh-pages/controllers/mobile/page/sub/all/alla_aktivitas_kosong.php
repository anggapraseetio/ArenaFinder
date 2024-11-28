<?php

require "../../../../koneksi.php";

header("Content-Type: application/json");

function fetchAktivitas($conn, $limit)
{
    $sql = "SELECT 
            a.id_aktivitas, a.nama_aktivitas, v.venue_name, a.date, a.photo, a.max_member,
        IFNULL(
            COUNT(am.id_aktivitas), 0
        ) AS jumlah_member 
        FROM venue_aktivitas AS a 
        LEFT JOIN venues AS v 
        ON a.id_venue = v.id_venue
        LEFT JOIN venue_aktivitas_member AS am 
        ON a.id_aktivitas = am.id_aktivitas 
        GROUP BY a.id_aktivitas
        HAVING a.date >= NOW() AND jumlah_member < a.max_member
        ORDER BY a.date ASC 
        LIMIT $limit
    ";

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
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

    $aktivitas = fetchAktivitas($conn, 30);

    if ($aktivitas['status'] == 'error') {
        $aktivitas["data"] = [];
    }

    $response = array("status" => "success", "message" => "data didapatkan", "data"=> $aktivitas["data"]);
    
} else {
    $response = array("status" => "error", "message" => "not get method");
}

echo json_encode($response);
