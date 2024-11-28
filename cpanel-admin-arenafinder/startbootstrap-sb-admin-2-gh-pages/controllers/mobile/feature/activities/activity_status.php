<?php
require "../../../koneksi.php";

header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $email = $_GET["email"];
    $status = $_GET["status"];
    
    if ($status === "ongoing") {
        $code = ">=";
    }
    else if ($status === "finished") {
        $code = "<";
    }
    else {
        $code = "==";
    }

    $sql = "SELECT va.id_aktivitas, va.nama_aktivitas, v.venue_name, va.price, va.jam_main, va.date, va.photo
        FROM venue_aktivitas AS va 
        JOIN venues AS v 
        ON v.id_venue = va.id_venue
        JOIN venue_aktivitas_member AS vm 
        ON va.id_aktivitas = vm.id_aktivitas 
        WHERE vm.email = '$email' AND va.date $code NOW()
    ;";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $response = array("status" => "success","message"=> "Data sukses diambil", "data" => $result->fetch_all(MYSQLI_ASSOC));
    }else{
        $response = array("status"=> "success","message"=> "Data gagal diambil", "data" => []);
    }


} else {
    $response = array("status" => "error", "message" => "not delete method");
}

echo json_encode($response);
