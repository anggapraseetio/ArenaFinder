<?php

require "../../../../koneksi.php";

header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $email = $_GET["email"];
    $status = $_GET["status"];

    $sql = "SELECT vb.id_booking, vb.id_venue, v.venue_name, v.venue_photo, vb.total_price, 
        vb.payment_method, vb.payment_status, vd.date AS tanggal_pesan, 
        DATE(vb.date_confirmed) AS tanggal_konfirmasi,
        COUNT(vd.id_price) AS total_jadwal
        FROM venue_booking AS vb 
        JOIN venues AS v 
        ON vb.id_venue = v.id_venue 
        JOIN venue_booking_detail AS vd 
        ON vb.id_booking = vd.id_booking 
        WHERE vb.email = '$email' AND vb.payment_status = '$status' 
        GROUP BY vb.id_booking 
        ORDER BY vb.created_at DESC 
        
    ";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $response = array("status" => "success", "message" => "Data status booking didapatkan",  "data" => $result->fetch_all(MYSQLI_ASSOC));
    }else{
        $response = array("status" => "success", "message" => "Data status booking didapatkan",  "data" => []);
    }
} else {
    $response = array("status" => "error", "message" => "not put method");
}

echo json_encode($response);
