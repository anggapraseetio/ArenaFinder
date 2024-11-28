<?php

require "../../../../koneksi.php";

header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $idVenue = $_GET['id_venue'];

    $sql = "SELECT id_lapangan FROM venue_lapangan WHERE id_venue = $idVenue";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $response = array("status" => "success", "message" => "data didapat", "data"=>$result->fetch_all(MYSQLI_ASSOC));
    }

} else {
    $response = array("status" => "error", "message" => "not put method");
}

echo json_encode($response);
