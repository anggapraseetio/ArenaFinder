<?php

require "../../../../koneksi.php";
require "../../../feature/venues/Venue.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $venue = new Venue();

    $vSport = $venue->fetchVenueBerbayar($conn, 30);

    if ($vSport['status'] == 'error') {
        $vSport["data"] = [];
    }

    $response = array("status" => "success", "message" => "data didapatkan", "data"=> $vSport["data"]);
    
} else {
    $response = array("status" => "error", "message" => "not get method");
}

echo json_encode($response);
