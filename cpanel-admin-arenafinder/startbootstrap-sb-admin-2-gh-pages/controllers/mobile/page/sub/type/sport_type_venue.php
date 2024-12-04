<?php

require "../../../../koneksi.php";
require "../../../feature/venues/Venue.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $sport = $_GET['sport'];

    $venue = new Venue();

    $vSport = $venue->fetchVenueSport($conn, $sport, 30);

    if ($vSport['status'] == 'error') {
        $vSport["data"] = [];
    }

    // $img = "https://arenafinder.tifnganjuk.com/public/img/types/";
    $img = "";

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
        "venues" => $vSport["data"]
    ];

    $response = array("status" => "success", "message" => "data didapatkan", "data" => $data);
} else {
    $response = array("status" => "error", "message" => "not get method");
}

echo json_encode($response);
