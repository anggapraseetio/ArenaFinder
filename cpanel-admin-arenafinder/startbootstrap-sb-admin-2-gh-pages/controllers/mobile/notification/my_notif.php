<?php

/**
 * Digunakan untuk login manual dengan menginputkan username/email dan password.
 */

require "../../koneksi.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $email = $_GET['email'];

    $sql = "SELECT 
        DATE(vb.date_confirmed) AS tanggal, 
        vb.payment_status AS status,
        DATE(vb.date_confirmed) AS tanggal_konfirmasi
        FROM venue_booking AS vb 
        JOIN users AS u 
        ON u.email = vb.email 
        WHERE vb.payment_status != 'Pending' 
        AND u.email = '$email'
        ORDER BY tanggal DESC
    ";

    $result = $conn->query($sql);

    if ($result->num_rows >= 1) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $response = array("status" => "success", "message" => "data kosong", "data" => $data);
    } else {
        $response = array("status" => "error", "message" => "data kosong");
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "not post method");
}

// show response
echo json_encode($response);