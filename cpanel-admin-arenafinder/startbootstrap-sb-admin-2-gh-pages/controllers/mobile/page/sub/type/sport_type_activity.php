<?php

require "../../../../koneksi.php";

header("Content-Type: application/json");

function fetchData($conn, $status, $limit)
{
    $sql = "SELECT 
            a.id_aktivitas, a.nama_aktivitas, v.venue_name, a.date, a.photo, a.max_member,
            a.jam_main, a.price,
        IFNULL(
            COUNT(am.id_aktivitas), 0
        ) AS jumlah_member 
        FROM venue_aktivitas AS a 
        LEFT JOIN venues AS v 
        ON a.id_venue = v.id_venue
        LEFT JOIN venue_aktivitas_member AS am 
        ON a.id_aktivitas = am.id_aktivitas 
        WHERE a.sport = '$status'
        GROUP BY a.id_aktivitas
        ORDER BY jumlah_member DESC
        LIMIT $limit
    ";

    // echo $sql;

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

    $sport = $_GET['sport'];

    $vSport = fetchData($conn, $sport, 30);

    if ($vSport['status'] == 'error') {
        $vSport["data"] = [];
    }

    $img = "https://arenafinder.tifnganjuk.com/public/img/types/";

    switch ($sport) {
        case "Futsal":
            $img = $img . "ic-type-futsall.png";
            break;
        case "Bulu Tangkis":
            $img = $img . "ic-type-badminton.png";
            break;
        case "Sepak Bola":
            $img = $img . "ic-type-football.png";
            break;
        case "Bola Basket":
            $img = $img . "ic-type-basket.png";
            break;
        case "Bola Voli":
            $img = $img . "ic-type-voli.png";
            break;
        case "Tenis":
            $img = $img . "ic-type-tennis.png";
            break;
        case "Tenis Meja":
            $img = $img . "ic-type-tabletennis.png";
            break;
        case "Atletik":
            $img = $img . "ic-type-atletik.png";
            break;
        case "Fitness":
            $img = $img . "ic-type-fitnes.png";
            break;
        case "Renang":
            $img = $img . "ic-type-swiming.png";
            break;
        case "Silat":
            $img = $img . "ic-type-silat.png";
            break;
        default:
            $img = $img . "ic-type-football.png";
            break;
    }

    $data = [
        "img_url" => $img,
        "activities" => $vSport["data"]
    ];

    $response = array("status" => "success", "message" => "data didapatkan", "data" => $data);
} else {
    $response = array("status" => "error", "message" => "not get method");
}

echo json_encode($response);
